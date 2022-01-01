<?php

namespace Addwiki\Mediawiki\Api\Tests\Integration\Service;

use Addwiki\Mediawiki\Api\CategoryLoopException;
use Addwiki\Mediawiki\Api\MediawikiFactory;
use Addwiki\Mediawiki\Api\Service\CategoryTraverser;
use Addwiki\Mediawiki\Api\Tests\Integration\TestEnvironment;
use Addwiki\Mediawiki\DataModel\Content;
use Addwiki\Mediawiki\DataModel\Page;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Mediawiki\DataModel\Title;
use PHPUnit\Framework\TestCase;

class CategoryTraverserTest extends TestCase {

	protected ?TestEnvironment $testEnvironment = null;

	protected ?MediawikiFactory $factory = null;

	protected ?CategoryTraverser $traverser = null;

	protected function setUp(): void {
		parent::setUp();
		$this->testEnvironment = TestEnvironment::newInstance();
		$this->factory = new MediawikiFactory( $this->testEnvironment->getApi() );
		$this->traverser = $this->factory->newCategoryTraverser();
	}

	/**
	 * A convenience wrapper to blank pages.
	 * TODO actually delete them?
	 * @param string[] $titles The titles to blank.
	 */
	public function blankPages( array $titles ): void {
		foreach ( $titles as $t ) {
			$this->savePage( $t, '' );
		}
	}

	/**
	 * A convenience wrapper to a RevisionSaver.
	 * @param string $title The title of the new page.
	 * @param string $content The wikitext to save to the page.
	 * @return Page The saved Page.
	 */
	protected function savePage( string $title, string $content ): Page {
		$pageIdentifier = new PageIdentifier( new Title( $title ) );
		$revision = new Revision( new Content( $content ), $pageIdentifier );
		$this->factory->newRevisionSaver()->save( $revision );
		return $this->factory->newPageGetter()->getFromPageIdentifier( $pageIdentifier );
	}

	/**
	 * Get a list of all pages in a category or any of its descendants.
	 */
	public function testDescendants(): void {
		$rootCat = $this->savePage( 'Category:Root category', '' );
		$this->savePage( 'Category:Sub category B', '[[Category:Root category]]' );
		$this->savePage( 'Category:Sub category C', '[[Category:Root category]]' );
		$this->savePage( 'Test page A1', 'Testing. [[Category:Root category]]' );
		$this->savePage( 'Test page B1', 'Testing. [[Category:Sub category B]]' );
		$this->savePage( 'Test page B2', 'Testing. [[Category:Sub category B]]' );
		$this->savePage( 'Test page C1', 'Testing. [[Category:Sub category C]]' );
		$this->testEnvironment->runJobs();

		$callback = function ( Page $pageInfo, Page $parentCat ): void {
			$parentCatName = $parentCat->getPageIdentifier()->getTitle()->getText();
			$thisPageName = $pageInfo->getPageIdentifier()->getTitle()->getText();
			if ( $parentCatName === 'Category:Root category' ) {
				$this->assertEquals( 'Test page A1', $thisPageName );
			}

			if ( $parentCatName === 'Category:Sub category C' ) {
				$this->assertEquals( 'Test page C1', $thisPageName );
			}
		};
		$this->traverser->addCallback( CategoryTraverser::CALLBACK_PAGE, $callback );
		$decendants = $this->traverser->descend( $rootCat );
		$this->assertCount( 4, $decendants->toArray() );
		$this->blankPages( [
			'Category:Root category',
			'Category:Sub category B',
			'Category:Sub category C',
			'Test page A1',
			'Test page B1',
			'Test page B2',
			'Test page C1',
		] );
	}

	/**
	 * Make sure there aren't duplicate results when there are multiple paths to
	 * the same page.
	 */
	public function testDescendantsWithMultiplePaths(): void {
		$grandparent = $this->savePage( 'Category:Grandparent', '' );
		$this->savePage( 'Category:Parent 1', '[[Category:Grandparent]]' );
		$this->savePage( 'Category:Parent 2', '[[Category:Grandparent]]' );
		$this->savePage( 'Parent 1', '[[Category:Grandparent]]' );
		$this->savePage( 'Child 1', '[[Category:Parent 1]]' );
		$this->savePage( 'Child 2', '[[Category:Parent 1]]' );
		$this->savePage( 'Child 3', '[[Category:Parent 2]]' );
		$this->testEnvironment->runJobs();
		$decendants = $this->traverser->descend( $grandparent );
		$this->assertCount( 4, $decendants->toArray() );
		$this->blankPages( [
			'Category:Grandparent',
			'Category:Parent 1',
			'Category:Parent 2',
			'Child 1',
			'Child 2',
			'Child 3',
		] );
	}

	/**
	 * Categories should only be traversed once. For example, in the following graph, 'C' can be
	 * reached as a child of 'A' or of 'B', but only the first arrival will proceed to 'D':
	 *
	 *     A
	 *    |  \
	 *    |   B
	 *    |  /
	 *    C
	 *    |
	 *    D
	 *
	 */
	public function testDescendantsOnlyVisitCatsOnce(): void {
		global $wgVisitedCats;
		$wgVisitedCats = [];
		$catA = $this->savePage( 'Category:A cat', '' );
		$this->savePage( 'Category:B cat', 'Testing. [[Category:A cat]]' );
		$this->savePage( 'Category:C cat', 'Testing. [[Category:A cat]][[Category:B cat]]' );
		$this->savePage( 'Category:D cat', 'Testing. [[Category:C cat]]' );
		$this->testEnvironment->runJobs();
		$callback = static function ( Page $pageInfo, Page $parentCat ): void {
			global $wgVisitedCats;
			$wgVisitedCats[] = $parentCat->getPageIdentifier()->getTitle()->getText();
		};
		$this->traverser->addCallback( CategoryTraverser::CALLBACK_CATEGORY, $callback );
		$descendants = $this->traverser->descend( $catA );
		$this->assertCount( 0, $descendants->toArray() );
		$this->assertCount( 3, $wgVisitedCats );
		$this->blankPages( [
			'Category:A cat',
			'Category:B cat',
			'Category:C cat',
			'Category:D cat',
		] );
	}

	/**
	 * Category loops are caught on descent.
	 *
	 *          E
	 *        /  \
	 *       F    G
	 *     /  \
	 *    H    I
	 *    |
	 *    E    <-- throw an Exception when we get to this repetition
	 *
	 */
	public function testDescendIntoLoop(): void {
		$catA = $this->savePage( 'Category:E cat', '[[Category:H cat]]' );
		$this->savePage( 'Category:F cat', '[[Category:E cat]]' );
		$this->savePage( 'Category:G cat', '[[Category:E cat]]' );
		$this->savePage( 'Category:H cat', '[[Category:F cat]]' );
		$this->savePage( 'Category:I cat', '[[Category:F cat]]' );
		$this->testEnvironment->runJobs();
		$haveCaught = false;
		try {
			$this->traverser->descend( $catA );
		} catch ( CategoryLoopException $categoryLoopException ) {
			$haveCaught = true;
			$expectedCatLoop = [
				'Category:E cat',
				'Category:F cat',
				'Category:H cat',
			];
			// Build a simplified representation of the thrown loop pages, to get around different
			// revision IDs.
			$actualCatLoop = [];
			foreach ( $categoryLoopException->getCategoryPath()->toArray() as $p ) {
				$actualCatLoop[] = $p->getPageIdentifier()->getTitle()->getText();
			}

			$this->assertEquals( $expectedCatLoop, $actualCatLoop );
		}

		$this->assertTrue( $haveCaught );
		$this->blankPages( [
			'Category:E cat',
			'Category:F cat',
			'Category:G cat',
			'Category:H cat',
			'Category:I cat',
		] );
	}

}
