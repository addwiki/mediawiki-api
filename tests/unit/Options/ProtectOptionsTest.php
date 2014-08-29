<?php

namespace Mediawiki\Api\Test\Options;

use Mediawiki\Api\Options\ProtectOptions;

/**
 * @author Adam Shorland
 * @covers Mediawiki\Api\Options\ProtectOptions
 */
class ProtectOptionsTest extends \PHPUnit_Framework_TestCase {

	public function testExpiry() {
		$obj = new ProtectOptions();
		$this->assertEquals( 'infinite', $obj->getExpiry() );
		$this->assertEquals( $obj, $obj->setExpiry( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getExpiry() );
	}

	public function testReason() {
		$obj = new ProtectOptions();
		$this->assertEquals( '', $obj->getReason() );
		$this->assertEquals( $obj, $obj->setReason( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getReason() );
	}

	public function testCascade() {
		$obj = new ProtectOptions();
		$this->assertEquals( false, $obj->getCascade() );
		$this->assertEquals( $obj, $obj->setCascade( true ) );
		$this->assertEquals( true, $obj->getCascade() );
	}

	public function testWatchlist() {
		$obj = new ProtectOptions();
		$this->assertEquals( 'preferences', $obj->getWatchlist() );
		$this->assertEquals( $obj, $obj->setWatchlist( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getWatchlist() );
	}

} 