<?php

namespace Mediawiki\Api\Test\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Service\PagePurger;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Pages;
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
			->will( $this->returnValue( [ "batchcomplete" => "", "purge" => [ [ "ns" => 0, "title" => "Foo", "purged" => "" ] ] ] ) );

		$service = new PagePurger( $api );

		$page = new Page(
			new PageIdentifier(
				new Title( 'Foo', 0 ),
				123
			),
			new Revisions( [] )
		);

		$this->assertTrue( $service->purge( $page ) );
	}

	public function testPurgePages() {
		$api = $this->getMockApi();
		$api->expects( $this->once() )
			->method( 'postRequest' )
			->with(
				$this->isInstanceOf( '\Mediawiki\Api\SimpleRequest' )
			)
			->will( $this->returnValue(
				[
	    		"batchcomplete" => "",
	    		"purge" => [
	        	[
	          	"ns" => 0,
	            "title" => "Foo",
	            "purged" => ""
	        	],
	        	[
	            "ns" => 0,
	            "title" => "Bar",
	            "purged" => ""
	        	]
	    		]
	    	]
			) );

			$service = new PagePurger( $api );

			$pages = new Pages( [
				new Page(
				new PageIdentifier(
					new Title( 'Foo', 0 ),
					123
				),
				new Revisions( [] )
			), new Page(
				new PageIdentifier(
					new Title( 'Bar', 1 ),
					123
				),
				new Revisions( [] )
			) ] );
			
			$this->assertEquals( $service->purgePages( $pages ) , $pages);
	}

}
