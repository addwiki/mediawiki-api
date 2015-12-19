<?php

namespace Mediawiki\Api\Test\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Service\PagePurger;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Revisions;
use Mediawiki\DataModel\Title;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @author Addshore
 * @covers Mediawiki\Api\Service\PagePurger
 */
class PagePurgerTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		/** @var MediawikiApi|PHPUnit_Framework_MockObject_MockObject $mock */
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstruction() {
		new PagePurger( $this->getMockApi() );
		$this->assertTrue( true );
	}

	public function testPurgePage() {
		$api = $this->getMockApi();
		$api->expects( $this->once() )
			->method( 'postRequest' )
			->with(
				$this->isInstanceOf( '\Mediawiki\Api\SimpleRequest' )
			)
			->will( $this->returnValue( 'SOME no error RESULT' ) );

		$service = new PagePurger( $api );

		$page = new Page(
			new PageIdentifier(
				new Title( 'Foo', 0 ),
				123
			),
			new Revisions( array() )
		);

		$this->assertTrue( $service->purge( $page ) );
	}

} 