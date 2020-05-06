<?php

namespace Addwiki\MediaWikiApi\Test;

use Addwiki\MediaWikiApi\MediaWikiFactory;

/**
 * @covers \Addwiki\MediaWikiApi\MediaWikiFactory
 *
 * @author Addshore
 */
class MediawikiFactoryTest extends \PHPUnit_Framework_TestCase {

	public function getMockMediawikiApi() {
		return $this->getMockBuilder( 'Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
	}

	public function provideFactoryMethodsTest() {
		return [
			[ \Addwiki\MediaWikiApi\Service\RevisionSaver::class, 'newRevisionSaver' ],
			[ \Addwiki\MediaWikiApi\Service\RevisionUndoer::class, 'newRevisionUndoer' ],
			[ \Addwiki\MediaWikiApi\Service\PageGetter::class, 'newPageGetter' ],
			[ \Addwiki\MediaWikiApi\Service\UserGetter::class, 'newUserGetter' ],
			[ \Addwiki\MediaWikiApi\Service\PageDeleter::class, 'newPageDeleter' ],
			[ \Addwiki\MediaWikiApi\Service\PageMover::class, 'newPageMover' ],
			[ \Addwiki\MediaWikiApi\Service\PageListGetter::class, 'newPageListGetter' ],
			[ \Addwiki\MediaWikiApi\Service\PageRestorer::class, 'newPageRestorer' ],
			[ \Addwiki\MediaWikiApi\Service\PagePurger::class, 'newPagePurger' ],
			[ \Addwiki\MediaWikiApi\Service\RevisionRollbacker::class, 'newRevisionRollbacker' ],
			[ \Addwiki\MediaWikiApi\Service\RevisionPatroller::class, 'newRevisionPatroller' ],
			[ \Addwiki\MediaWikiApi\Service\PageProtector::class, 'newPageProtector' ],
			[ \Addwiki\MediaWikiApi\Service\PageWatcher::class, 'newPageWatcher' ],
			[ \Addwiki\MediaWikiApi\Service\RevisionDeleter::class, 'newRevisionDeleter' ],
			[ \Addwiki\MediaWikiApi\Service\RevisionRestorer::class, 'newRevisionRestorer' ],
			[ \Addwiki\MediaWikiApi\Service\UserBlocker::class, 'newUserBlocker' ],
			[ \Addwiki\MediaWikiApi\Service\UserRightsChanger::class, 'newUserRightsChanger' ],
			[ \Addwiki\MediaWikiApi\Service\UserCreator::class, 'newUserCreator' ],
			[ \Addwiki\MediaWikiApi\Service\LogListGetter::class, 'newLogListGetter' ],
			[ \Addwiki\MediaWikiApi\Service\FileUploader::class, 'newFileUploader' ],
			[ \Addwiki\MediaWikiApi\Service\ImageRotator::class, 'newImageRotator' ],
		];
	}

	/**
	 * @dataProvider provideFactoryMethodsTest
	 */
	public function testFactoryMethod( $class, $method ) {
		$factory = new MediaWikiFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( $class, $factory->$method() );
	}

}
