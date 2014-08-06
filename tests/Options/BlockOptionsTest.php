<?php

namespace Mediawiki\Api\Test\Options;

use Mediawiki\Api\Options\BlockOptions;

/**
 * @author Adam Shorland
 * @covers Mediawiki\Api\Options\BlockOptions
 */
class BlockOptionsTest  extends \PHPUnit_Framework_TestCase {

	public function testExpiry() {
		$obj = new BlockOptions();
		$this->assertEquals( 'never', $obj->getExpiry() );
		$this->assertEquals( $obj, $obj->setExpiry( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getExpiry() );
	}

	public function testReason() {
		$obj = new BlockOptions();
		$this->assertEquals( '', $obj->getReason() );
		$this->assertEquals( $obj, $obj->setReason( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getReason() );
	}

	public function testAllowusertalk() {
		$obj = new BlockOptions();
		$this->assertEquals( false, $obj->getAllowusertalk() );
		$this->assertEquals( $obj, $obj->setAllowusertalk( true ) );
		$this->assertEquals( true, $obj->getAllowusertalk() );
	}

	public function testReblock() {
		$obj = new BlockOptions();
		$this->assertEquals( false, $obj->getReblock() );
		$this->assertEquals( $obj, $obj->setReblock( true ) );
		$this->assertEquals( true, $obj->getReblock() );
	}

	public function testAnononly() {
		$obj = new BlockOptions();
		$this->assertEquals( false, $obj->getAnononly() );
		$this->assertEquals( $obj, $obj->setAnononly( true ) );
		$this->assertEquals( true, $obj->getAnononly() );
	}

	public function testAutoblock() {
		$obj = new BlockOptions();
		$this->assertEquals( false, $obj->getAutoblock() );
		$this->assertEquals( $obj, $obj->setAutoblock( true ) );
		$this->assertEquals( true, $obj->getAutoblock() );
	}

	public function testHidename() {
		$obj = new BlockOptions();
		$this->assertEquals( false, $obj->getHidename() );
		$this->assertEquals( $obj, $obj->setHidename( true ) );
		$this->assertEquals( true, $obj->getHidename() );
	}

	public function testNocreate() {
		$obj = new BlockOptions();
		$this->assertEquals( false, $obj->getNocreate() );
		$this->assertEquals( $obj, $obj->setNocreate( true ) );
		$this->assertEquals( true, $obj->getNocreate() );
	}

	public function testNoemail() {
		$obj = new BlockOptions();
		$this->assertEquals( false, $obj->getNoemail() );
		$this->assertEquals( $obj, $obj->setNoemail( true ) );
		$this->assertEquals( true, $obj->getNoemail() );
	}

	public function testWatchuser() {
		$obj = new BlockOptions();
		$this->assertEquals( false, $obj->getWatchuser() );
		$this->assertEquals( $obj, $obj->setWatchuser( true ) );
		$this->assertEquals( true, $obj->getWatchuser() );
	}

}