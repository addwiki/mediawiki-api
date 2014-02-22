<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\UsageException;
use stdClass;

/**
 * @covers Mediawiki\Api\MediawikiApi
 */
class MediawikiApiTest extends \PHPUnit_Framework_TestCase {

	public function provideValidConstruction() {
		return array(
			array( 'localhost' ),
			array( 'http://en.wikipedia.org/w/api.php' ),
			array( '127.0.0.1/foo/bar/wwwwwwwww/api.php' ),
		);
	}

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testValidConstruction( $apilocation ) {
		new MediawikiApi( $apilocation );
		$this->assertTrue( true );
	}

	public function provideInvalidConstruction() {
		return array(
			array( null ),
			array( 12345678 ),
			array( array() ),
			array( new stdClass() ),
		);
	}

	/**
	 * @dataProvider provideInvalidConstruction
	 */
	public function testInvalidConstruction( $apilocation ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		new MediawikiApi( $apilocation );
	}

	public function getMediawikiApi( $client ) {
		return new MediawikiApi( $client );
	}

	private function getMockClient() {
		$mock = $this->getMockBuilder( 'Guzzle\Service\Mediawiki\MediawikiApiClient' )
			->disableOriginalConstructor()
			->setMethods( array( 'getAction', 'postAction' ) )
			->getMock();
		return $mock;
	}

	public function testGetActionThrowsUsageExceptionOnError() {
		$client = $this->getMockClient();
		$client->expects( $this->once() )
			->method( 'getAction' )
			->will( $this->returnValue(
				array( 'error' => array(
					'code' => 'imacode',
					'info' => 'imamsg',
				) )
			) );
		$api = new MediawikiApi( $client );

		try{
			$api->getAction( 'foo' );
			$this->fail( 'No Usage Exception Thrown' );
		}
		catch( UsageException $e ) {
			$this->assertEquals( 'imacode', $e->getApiCode() );
			$this->assertEquals( 'imamsg', $e->getMessage() );
		}
	}

	public function testPostActionThrowsUsageExceptionOnError() {
		$client = $this->getMockClient();
		$client->expects( $this->once() )
			->method( 'postAction' )
			->will( $this->returnValue(
				array( 'error' => array(
					'code' => 'imacode',
					'info' => 'imamsg',
				) )
			) );
		$api = new MediawikiApi( $client );

		try{
			$api->postAction( 'foo' );
			$this->fail( 'No Usage Exception Thrown' );
		}
		catch( UsageException $e ) {
			$this->assertEquals( 'imacode', $e->getApiCode() );
			$this->assertEquals( 'imamsg', $e->getMessage() );
		}
	}

} 