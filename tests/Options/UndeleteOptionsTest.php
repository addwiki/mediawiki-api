<?php

namespace Mediawiki\Api\Test\Options;

use Mediawiki\Api\Options\UndeleteOptions;

/**
 * @author Adam Shorland
 * @covers Mediawiki\Api\Options\UndeleteOptions
 */
class UndeleteOptionsTest extends \PHPUnit_Framework_TestCase {

	public function testReason() {
		$obj = new UndeleteOptions();
		$this->assertEquals( '', $obj->getReason() );
		$this->assertEquals( $obj, $obj->setReason( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getReason() );
	}

}