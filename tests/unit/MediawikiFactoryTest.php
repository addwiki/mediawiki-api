<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\MediawikiFactory;

/**
 * @covers Mediawiki\Api\MediawikiFactory
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
		return array(
			array( 'Mediawiki\Api\Service\RevisionSaver', 'newRevisionSaver' ),
			array( 'Mediawiki\Api\Service\RevisionUndoer', 'newRevisionUndoer' ),
			array( 'Mediawiki\Api\Service\PageGetter', 'newPageGetter' ),
			array( 'Mediawiki\Api\Service\UserGetter', 'newUserGetter' ),
			array( 'Mediawiki\Api\Service\PageDeleter', 'newPageDeleter' ),
			array( 'Mediawiki\Api\Service\PageMover', 'newPageMover' ),
			array( 'Mediawiki\Api\Service\PageListGetter', 'newPageListGetter' ),
			array( 'Mediawiki\Api\Service\PageRestorer', 'newPageRestorer' ),
			array( 'Mediawiki\Api\Service\PagePurger', 'newPagePurger' ),
			array( 'Mediawiki\Api\Service\RevisionRollbacker', 'newRevisionRollbacker' ),
			array( 'Mediawiki\Api\Service\RevisionPatroller', 'newRevisionPatroller' ),
			array( 'Mediawiki\Api\Service\PageProtector', 'newPageProtector' ),
			array( 'Mediawiki\Api\Service\PageWatcher', 'newPageWatcher' ),
			array( 'Mediawiki\Api\Service\RevisionDeleter', 'newRevisionDeleter' ),
			array( 'Mediawiki\Api\Service\RevisionRestorer', 'newRevisionRestorer' ),
			array( 'Mediawiki\Api\Service\UserBlocker', 'newUserBlocker' ),
			array( 'Mediawiki\Api\Service\UserRightsChanger', 'newUserRightsChanger' ),
			array( 'Mediawiki\Api\Service\UserCreator', 'newUserCreator' ),
			array( 'Mediawiki\Api\Service\LogListGetter', 'newLogListGetter' ),
			array( 'Mediawiki\Api\Service\FileUploader', 'newFileUploader' ),
			array( 'Mediawiki\Api\Service\ImageRotator', 'newImageRotator' ),
		);
	}

	/**
	 * @dataProvider provideFactoryMethodsTest
	 */
	public function testFactoryMethod( $class, $method ) {
		$factory = new MediawikiFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( $class, $factory->$method() );
	}

} 