<?php

namespace Mediawiki\Api\Test;

use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Revision;
use Mediawiki\DataModel\Title;
use Mediawiki\DataModel\WikitextContent;

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
					new WikitextContent( 'testCreatePage_content' ),
					self::$localPageIdentifier
				)
			),
			'Failed to Create Page ' . self::$localPageIdentifier->getTitle()->getTitle()
		);
	}

}