<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\MediawikiFactory;
use Mediawiki\Api\Service\FileUploader;
use Mediawiki\Api\Service\ImageRotator;
use Mediawiki\Api\Service\LogListGetter;
use Mediawiki\Api\Service\PageDeleter;
use Mediawiki\Api\Service\PageGetter;
use Mediawiki\Api\Service\PageListGetter;
use Mediawiki\Api\Service\PageMover;
use Mediawiki\Api\Service\PageProtector;
use Mediawiki\Api\Service\PagePurger;
use Mediawiki\Api\Service\PageRestorer;
use Mediawiki\Api\Service\PageWatcher;
use Mediawiki\Api\Service\RevisionDeleter;
use Mediawiki\Api\Service\RevisionPatroller;
use Mediawiki\Api\Service\RevisionRestorer;
use Mediawiki\Api\Service\RevisionRollbacker;
use Mediawiki\Api\Service\RevisionSaver;
use Mediawiki\Api\Service\RevisionUndoer;
use Mediawiki\Api\Service\UserBlocker;
use Mediawiki\Api\Service\UserCreator;
use Mediawiki\Api\Service\UserGetter;
use Mediawiki\Api\Service\UserRightsChanger;
use PHPUnit\Framework\TestCase;

/**
 * @covers Mediawiki\Api\MediawikiFactory
 *
 * @author Addshore
 */
class MediawikiFactoryTest extends TestCase {

	public function getMockMediawikiApi() {
		return $this->getMockBuilder( MediawikiApi::class )
			->disableOriginalConstructor()
			->getMock();
	}

	public function provideFactoryMethodsTest() {
		return [
			[ RevisionSaver::class, 'newRevisionSaver' ],
			[ RevisionUndoer::class, 'newRevisionUndoer' ],
			[ PageGetter::class, 'newPageGetter' ],
			[ UserGetter::class, 'newUserGetter' ],
			[ PageDeleter::class, 'newPageDeleter' ],
			[ PageMover::class, 'newPageMover' ],
			[ PageListGetter::class, 'newPageListGetter' ],
			[ PageRestorer::class, 'newPageRestorer' ],
			[ PagePurger::class, 'newPagePurger' ],
			[ RevisionRollbacker::class, 'newRevisionRollbacker' ],
			[ RevisionPatroller::class, 'newRevisionPatroller' ],
			[ PageProtector::class, 'newPageProtector' ],
			[ PageWatcher::class, 'newPageWatcher' ],
			[ RevisionDeleter::class, 'newRevisionDeleter' ],
			[ RevisionRestorer::class, 'newRevisionRestorer' ],
			[ UserBlocker::class, 'newUserBlocker' ],
			[ UserRightsChanger::class, 'newUserRightsChanger' ],
			[ UserCreator::class, 'newUserCreator' ],
			[ LogListGetter::class, 'newLogListGetter' ],
			[ FileUploader::class, 'newFileUploader' ],
			[ ImageRotator::class, 'newImageRotator' ],
		];
	}

	/**
	 * @dataProvider provideFactoryMethodsTest
	 */
	public function testFactoryMethod( $class, $method ) {
		$factory = new MediawikiFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( $class, $factory->$method() );
	}

}
