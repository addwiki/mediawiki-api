<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\MediawikiFactory;
use Mediawiki\DataModel\Content;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Revision;
use Mediawiki\DataModel\Title;
use PHPUnit_Framework_TestCase;

class IntegrationTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var PageIdentifier
	 */
	private static $localPageIdentifier;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		self::$localPageIdentifier = new PageIdentifier( new Title( 'TestPage - ' . strval( time() ) ) );
	}

	public function testCreatePage() {
		$factory = new MediawikiFactory( new MediawikiApi( 'http://localhost/w/api.php' ) );
		$this->assertTrue(
			$factory->newRevisionSaver()->save(
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
		$factory = new MediawikiFactory( new MediawikiApi( 'http://localhost/w/api.php' ) );
		$page = $factory->newPageGetter()->getFromPageIdentifier( self::$localPageIdentifier );
		$this->assertTrue( is_int( $page->getPageIdentifier()->getId() ) );
		$this->assertEquals( self::$localPageIdentifier->getTitle(), $page->getPageIdentifier()->getTitle() );
		$this->assertEquals( 'testCreatePage_content', $page->getRevisions()->getLatest()->getContent()->getData() );
		self::$localPageIdentifier = $page->getPageIdentifier();
	}

	/**
	 * @depends testGetPageUsingTitle
	 */
	public function testGetPageUsingId() {
		$factory = new MediawikiFactory( new MediawikiApi( 'http://localhost/w/api.php' ) );
		$page = $factory->newPageGetter()->getFromPageId( self::$localPageIdentifier->getId() );
		$this->assertEquals( self::$localPageIdentifier->getId(), $page->getPageIdentifier()->getId() );
		$this->assertEquals( self::$localPageIdentifier->getTitle(), $page->getPageIdentifier()->getTitle() );
	}

}