<?php

namespace Addwiki\Mediawiki\Api\Tests\Unit;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\MediawikiFactory;
use Addwiki\Mediawiki\Api\Service\FileUploader;
use Addwiki\Mediawiki\Api\Service\ImageRotator;
use Addwiki\Mediawiki\Api\Service\LogListGetter;
use Addwiki\Mediawiki\Api\Service\PageDeleter;
use Addwiki\Mediawiki\Api\Service\PageGetter;
use Addwiki\Mediawiki\Api\Service\PageListGetter;
use Addwiki\Mediawiki\Api\Service\PageMover;
use Addwiki\Mediawiki\Api\Service\PageProtector;
use Addwiki\Mediawiki\Api\Service\PagePurger;
use Addwiki\Mediawiki\Api\Service\PageRestorer;
use Addwiki\Mediawiki\Api\Service\PageWatcher;
use Addwiki\Mediawiki\Api\Service\RevisionDeleter;
use Addwiki\Mediawiki\Api\Service\RevisionPatroller;
use Addwiki\Mediawiki\Api\Service\RevisionRestorer;
use Addwiki\Mediawiki\Api\Service\RevisionRollbacker;
use Addwiki\Mediawiki\Api\Service\RevisionSaver;
use Addwiki\Mediawiki\Api\Service\RevisionUndoer;
use Addwiki\Mediawiki\Api\Service\UserBlocker;
use Addwiki\Mediawiki\Api\Service\UserCreator;
use Addwiki\Mediawiki\Api\Service\UserGetter;
use Addwiki\Mediawiki\Api\Service\UserRightsChanger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers Mediawiki\Api\MediawikiFactory
 */
class MediawikiFactoryTest extends TestCase {

	/**
	 * @return ActionApi&MockObject
	 */
	public function getMockMediawikiApi() {
		return $this->getMockBuilder( ActionApi::class )
			->disableOriginalConstructor()
			->getMock();
	}

	public function provideFactoryMethodsTest(): array {
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
	public function testFactoryMethod( string $class, string $method ): void {
		$factory = new MediawikiFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( $class, $factory->$method() );
	}

}
