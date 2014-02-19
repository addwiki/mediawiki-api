<?php

use Mediawiki\Api\MediawikiApi;

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

	/**
	 * @param array $methods any methods that will be used in the mock
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getMockClient( $methods = array() ) {
		$mock = $this->getMockBuilder( 'Guzzle\Service\Mediawiki\MediawikiApiClient' )
			->disableOriginalConstructor()
			->setMethods( $methods )
			->getMock();
		return $mock;
	}

} 