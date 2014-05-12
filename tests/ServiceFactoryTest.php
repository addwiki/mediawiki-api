<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\Service\ServiceFactory;

/**
 * @covers Mediawiki\Api\ServiceFactory
 */
class ServiceFactoryTest extends \PHPUnit_Framework_TestCase {

	public function getMockMediawikiApi() {
		return $this->getMockBuilder( 'Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
	}

	public function testNewPageRepo() {
		$factory = new ServiceFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( 'Mediawiki\Api\Service\PageRepo', $factory->newPageRepo() );
	}

	public function testNewUserRepo() {
		$factory = new ServiceFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( 'Mediawiki\Api\Service\UserRepo', $factory->newUserRepo() );
	}

	public function testNewRevisionSaver() {
		$factory = new ServiceFactory( $this->getMockMediawikiApi() );
		$this->assertInstanceOf( 'Mediawiki\Api\Service\RevisionSaver', $factory->newRevisionSaver() );
	}

} 