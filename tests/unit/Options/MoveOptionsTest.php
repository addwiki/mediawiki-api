<?php

namespace Mediawiki\Api\Test\Options;

use Mediawiki\Api\Options\MoveOptions;

/**
 * @author Adam Shorland
 * @covers Mediawiki\Api\Options\MoveOptions
 */
class MoveOptionsTest extends \PHPUnit_Framework_TestCase {

	public function testReason() {
		$obj = new MoveOptions();
		$this->assertEquals( '', $obj->getReason() );
		$this->assertEquals( $obj, $obj->setReason( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getReason() );
	}

}