<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\CategoryTraverser;

class CategoryTraverserTest extends PHPUnit_Framework_TestCase {

	/** @var \Samwilson\MediawikiCatTraverse\Traverser */
	protected $traverser;

	public function setUp() {
		parent::setUp();
		$this->traverser = new Traverser( $this->api );
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
	 * Get a list of all pages in a category or any of its descendants.
	 */
	public function testDescendants() {
		$decendants = $this->traverser->descendants( 'Category:Root category', function ( $pageInfo, $parentCatName ) {
			if ( $parentCatName === 'Category:Root category' ) {
				$this->assertEquals( 'Test page A1', $pageInfo['title'] );
			}
			if ( $parentCatName === 'Category:Sub category C' ) {
				$this->assertEquals( 'Test page C1', $pageInfo['title'] );
			}
		} );
		$this->assertCount( 4, $decendants );
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
		$decendants = $this->traverser->descendants( 'Category:Grandparent' );
		$this->assertCount( 4, $decendants );
	}

	/**
	 * Categories should only ve traversed once.
	 *
	 *     A
	 *    |  \
	 *    |   B
	 *    |  /
	 *    C
	 *    |
	 *    D
	 * @group desc
	 */
	public function testDescendantsOnlyVisitCatsOnce() {
		global $wgVisitedCats;
		$wgVisitedCats = [];
		$this->savePage( 'Category:A cat', '' );
		$this->savePage( 'Category:B cat', 'Testing. [[Category:A cat]]' );
		$this->savePage( 'Category:C cat', 'Testing. [[Category:A cat]][[Category:B cat]]' );
		$this->savePage( 'Category:D cat', 'Testing. [[Category:C cat]]' );
		$this->traverser->addCallback( Traverser::CALLBACK_CATEGORY, function( $pageInfo, $parentCatName ) {
			global $wgVisitedCats;
			print_r( $pageInfo );
//            if (!isset($visitedCats)) {
//                $visitedCats = [];
//            }
			$wgVisitedCats[] = $parentCatName;
		} );
		$decendants = $this->traverser->descendants( 'Category:A cat' );
		$this->assertCount( 0, $decendants );
		$this->assertCount( 4, $wgVisitedCats );
	}

}
