<?php

namespace Mediawiki\Api\Test;

use Mediawiki\DataModel\Content;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Revision;
use Mediawiki\DataModel\Title;

class IntegrationTest extends IntegrationTestBase {

	/**
	 * @var PageIdentifier
	 */
	private static $localPageIdentifier;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		self::$localPageIdentifier = new PageIdentifier( new Title( 'TestPage - ' . strval( time() ) ) );
	}

	public function testCreatePage() {
		$this->assertTrue(
			$this->factory->newRevisionSaver()->save(
				new Revision(
					new Content( 'testCreatePage_content' ),
					self::$localPageIdentifier
				)
			),
			'Failed to Create Page ' . self::$localPageIdentifier->getTitle()->getTitle()
		);
	}

	/**
	 * This is testGetPageUsingTitle as currently we only know the title
	 * @depends testCreatePage
	 */
	public function testGetPageUsingTitle() {
		$page = $this->factory->newPageGetter()->getFromPageIdentifier( self::$localPageIdentifier );
		$this->assertTrue( is_int( $page->getPageIdentifier()->getId() ) );
		$this->assertEquals( self::$localPageIdentifier->getTitle(), $page->getPageIdentifier()->getTitle() );
		$this->assertEquals( 'testCreatePage_content', $page->getRevisions()->getLatest()->getContent()->getData() );
		self::$localPageIdentifier = $page->getPageIdentifier();
	}

	/**
	 * @depends testGetPageUsingTitle
	 */
	public function testGetPageUsingId() {
		$page = $this->factory->newPageGetter()->getFromPageId( self::$localPageIdentifier->getId() );
		$this->assertEquals( self::$localPageIdentifier->getId(), $page->getPageIdentifier()->getId() );
		$this->assertEquals( self::$localPageIdentifier->getTitle(), $page->getPageIdentifier()->getTitle() );
	}

}