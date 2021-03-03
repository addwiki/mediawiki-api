<?php

namespace Addwiki\Mediawiki\Api\Tests\Integration\Service;

use Addwiki\Mediawiki\Api\MediawikiFactory;
use Addwiki\Mediawiki\Api\Tests\Integration\TestEnvironment;
use Addwiki\Mediawiki\DataModel\Content;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Mediawiki\DataModel\Title;
use PHPUnit\Framework\TestCase;

class PageIntegrationTest extends TestCase {

	private static ?PageIdentifier $localPageIdentifier = null;

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		$title = new Title( 'TestPage - ' . strval( time() ) );
		self::$localPageIdentifier = new PageIdentifier( $title );
	}

	public function testCreatePage(): void {
		$factory = new MediawikiFactory( TestEnvironment::newInstance()->getApi() );
		$this->assertTrue(
			$factory->newRevisionSaver()->save(
				new Revision(
					new Content( 'testCreatePage_content' ),
					self::$localPageIdentifier
				)
			),
			'Failed to Create Page ' . self::$localPageIdentifier->getTitle()->getText()
		);
	}

	/**
	 * This is testGetPageUsingTitle as currently we only know the title
	 * @depends testCreatePage
	 */
	public function testGetPageUsingTitle(): void {
		$factory = new MediawikiFactory( TestEnvironment::newInstance()->getApi() );
		$page = $factory->newPageGetter()->getFromPageIdentifier( self::$localPageIdentifier );
		$this->assertTrue( is_int( $page->getPageIdentifier()->getId() ) );
		$title = $page->getPageIdentifier()->getTitle();
		$this->assertEquals( self::$localPageIdentifier->getTitle(), $title );
		$content = $page->getRevisions()->getLatest()->getContent()->getData();
		$this->assertEquals( 'testCreatePage_content', $content );
		self::$localPageIdentifier = $page->getPageIdentifier();
	}

	/**
	 * @depends testGetPageUsingTitle
	 */
	public function testGetPageUsingId(): void {
		$factory = new MediawikiFactory( TestEnvironment::newInstance()->getApi() );
		$page = $factory->newPageGetter()->getFromPageId( self::$localPageIdentifier->getId() );
		$this->assertEquals( self::$localPageIdentifier->getId(), $page->getPageIdentifier()->getId() );
		$title = $page->getPageIdentifier()->getTitle();
		$this->assertEquals( self::$localPageIdentifier->getTitle(), $title );
	}

}
