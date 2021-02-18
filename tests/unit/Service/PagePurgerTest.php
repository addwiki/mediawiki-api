<?php

namespace Addwiki\Mediawiki\Api\Tests\Unit\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\Api\Service\PagePurger;
use Addwiki\Mediawiki\DataModel\Page;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Pages;
use Addwiki\Mediawiki\DataModel\Title;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Addshore
 * @covers Mediawiki\Api\Service\PagePurger
 */
class PagePurgerTest extends TestCase {

	private function getMockApi() {
		/** @var MediawikiApi|MockObject $mock */
		$mock = $this->getMockBuilder( MediawikiApi::class )
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
				$this->isInstanceOf( SimpleRequest::class )
			)
			->will( $this->returnValue(
			[
				"batchcomplete" => "",
				"purge" => [ [ "ns" => 0, "title" => "Foo", "purged" => "" ] ]
			] ) );

		$service = new PagePurger( $api );

		$page = new Page(
			new PageIdentifier(
				new Title( 'Foo', 0 ),
				123
			)
		);

		$this->assertTrue( $service->purge( $page ) );
	}

	public function testIncorrectPurgePage() {
		$api = $this->getMockApi();
		$api->expects( $this->once() )
			->method( 'postRequest' )
			->with(
				$this->isInstanceOf( SimpleRequest::class )
			)
			->will( $this->returnValue( [
				"batchcomplete" => "",
				"purge" =>
					[ [
						"ns" => 0,
						"title" => "This page really does not exist",
						"missing" => ""
					] ]
			] ) );

		$service = new PagePurger( $api );

		$page = new Page(
			new PageIdentifier(
				new Title( 'Foo', 0 ),
				123
			)
		);

		$this->assertFalse( $service->purge( $page ) );
	}

	public function testPurgePages() {
		$api = $this->getMockApi();
		$api->expects( $this->once() )
			->method( 'postRequest' )
			->with(
				$this->isInstanceOf( SimpleRequest::class )
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
						],
				]
			]
			) );

		$service = new PagePurger( $api );

		$pages = new Pages( [
			new Page(
				new PageIdentifier(
					new Title( 'Foo', 0 ),
					100
				)
			), new Page(
				new PageIdentifier(
					new Title( 'Bar', 1 ),
					101
				)
			) ] );

			$this->assertEquals( $service->purgePages( $pages ), $pages );
	}

	public function testIncorrectPurgePages() {
		$api = $this->getMockApi();
		$api->expects( $this->once() )
			->method( 'postRequest' )
			->with(
				$this->isInstanceOf( SimpleRequest::class )
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
					],
					[
						"ns" => 0,
						"title" => "This page really does not exist",
						"missing" => ""
					],
				]
			]
			) );

		$service = new PagePurger( $api );

		$pages = new Pages( [
			new Page(
				new PageIdentifier(
					new Title( 'Foo', 0 ),
					100
				)
			), new Page(
				new PageIdentifier(
					new Title( 'Bar', 1 ),
					101
				)
			), new Page(
				new PageIdentifier(
					new Title( 'MissingPage', 1 ),
					103
				)
			) ] );

		// MissingPage is not in the pages that are returned by purgePages
		$pagesArray = $pages->toArray();
		array_pop( $pagesArray );
		$result = new Pages( $pagesArray );

		$this->assertEquals( $service->purgePages( $pages ), $result );
	}

}
