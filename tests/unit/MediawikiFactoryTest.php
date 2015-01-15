<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\MediawikiFactory;

/**
 * @covers Mediawiki\Api\MediawikiFactory
 *
 * @author Adam Shorland
 */
class ServiceFactoryTest extends \PHPUnit_Framework_TestCase {

	public function getMockMediawikiApi() {
		return $this->getMockBuilder( 'Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
	}

	public function testNewPageGetter() {
		$factory = new MediawikiFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( 'Mediawiki\Api\Service\PageGetter', $factory->newPageGetter() );
	}

	public function testNewUserGetter() {
		$factory = new MediawikiFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( 'Mediawiki\Api\Service\UserGetter', $factory->newUserGetter() );
	}

	public function testNewRevisionSaver() {
		$factory = new MediawikiFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( 'Mediawiki\Api\Service\RevisionSaver', $factory->newRevisionSaver() );
	}

	public function testNewRevisionUndoer() {
		$factory = new MediawikiFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( 'Mediawiki\Api\Service\RevisionUndoer', $factory->newRevisionUndoer() );
	}

} 