<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\Test\TestEnvironment;
use Mediawiki\Api\Service\CategoryTraverser;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Title;
use Mediawiki\DataModel\Revision;
use Mediawiki\DataModel\Content;

class CategoryTraverserTest extends \PHPUnit_Framework_TestCase
{

	/** @var \Mediawiki\Api\MediawikiFactory */
	protected $factory;

	/** @var \Mediawiki\Api\Service\CategoryTraverser */
	protected $traverser;

	public function setUp() {

		parent::setUp();
		$this->factory = TestEnvironment::newDefault()->getFactory();
		$this->traverser = $this->factory->newCategoryTraverser();
		$this->savePage( 'Category:Root category', '' );
		$this->savePage( 'Category:Sub category B', '[[Category:Root category]]' );
		$this->savePage( 'Category:Sub category C', '[[Category:Root category]]' );
		$this->savePage( 'Test page A1', 'Testing. [[Category:Root category]]' );
		$this->savePage( 'Test page B1', 'Testing. [[Category:Sub category B]]' );
		$this->savePage( 'Test page B2', 'Testing. [[Category:Sub category B]]' );
		$this->savePage( 'Test page C1', 'Testing. [[Category:Sub category C]]' );
	}

	public function tearDown() {

		// $this->factory->newPageDeleter()->deleteFromPageTitle('Category:Root category');
		parent::tearDown();
	}

	/**
	 * A convenience wrapper to a RevisionSaver.
	 *
	 * @param type $name
	 * @param type $content
	 */
	protected function savePage( $name, $content ) {

		$pageId = new PageIdentifier( new Title( $name ) );
		$rev = new Revision( new Content( $content ), $pageId );
		$this->factory->newRevisionSaver()->save( $rev );
		// $this->factory->newPagePurger()->purge(new Page($pageId));
	}

	/**
	 * Get a list of all pages in a category or any of its descendants.
	 */
	public function testDescendants() {

		$ident = new PageIdentifier( new Title( 'Category:Root category' ) );
		$decendants = $this->traverser->descend( $ident, function ( $pageInfo, $parentCatName ) {
			if ( $parentCatName === 'Category:Root category' ) {
				$this->assertEquals( 'Test page A1', $pageInfo['title'] );
			}
			if ( $parentCatName === 'Category:Sub category C' ) {
				$this->assertEquals( 'Test page C1', $pageInfo['title'] );
			}
		} );
		$this->assertCount( 4, $decendants->toArray() );
	}

	/**
	 * Make sure there aren't duplicate results when there are multiple paths to
	 * the same page.
	 */
	public function testDescendantsWithMultiplePaths() {

		$this->savePage( 'Category:Grandparent', '' );
		$this->savePage( 'Category:Parent 1', '[[Category:Grandparent]]' );
		$this->savePage( 'Category:Parent 2', '[[Category:Grandparent]]' );
		$this->savePage( 'Parent 1', '[[Category:Grandparent]]' );
		$this->savePage( 'Child 1', '[[Category:Parent 1]]' );
		$this->savePage( 'Child 2', '[[Category:Parent 1]]' );
		$this->savePage( 'Child 3', '[[Category:Parent 2]]' );
		$ident = new PageIdentifier( new Title( 'Category:Grandparent' ) );
		$decendants = $this->traverser->descend( $ident );
		$this->assertCount( 4, $decendants->toArray() );
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
	 * @group desc
	 */
	public function testDescendantsOnlyVisitCatsOnce() {

		global $wgVisitedCats;
		$wgVisitedCats = [];
		$this->savePage( 'Category:A cat', '' );
		$this->savePage( 'Category:B cat', 'Testing. [[Category:A cat]]' );
		$this->savePage( 'Category:C cat', 'Testing. [[Category:A cat]][[Category:B cat]]' );
		$this->savePage( 'Category:D cat', 'Testing. [[Category:C cat]]' );
		$this->traverser->addCallback( CategoryTraverser::CALLBACK_CATEGORY, function (
			Page $pageInfo, PageIdentifier $parentCatName ) {
			global $wgVisitedCats;
			$wgVisitedCats[] = $parentCatName;
		} );
		$ident = new PageIdentifier( new Title( 'Category:A cat' ) );
		$decendants = $this->traverser->descend( $ident );
		$this->assertCount( 0, $decendants->toArray() );
		$this->assertCount( 3, $wgVisitedCats );
	}

}
