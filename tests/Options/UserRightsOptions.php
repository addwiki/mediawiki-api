<?php

namespace Mediawiki\Api\Test\Options;

use Mediawiki\Api\Options\UserRightsOptions;

/**
 * @author Adam Shorland
 * @covers Mediawiki\Api\Options\UserRightsOptions
 */
class UserRightsOptionsTest extends \PHPUnit_Framework_TestCase {

	public function testReason() {
		$obj = new UserRightsOptions();
		$this->assertEquals( '', $obj->getReason() );
		$this->assertEquals( $obj, $obj->setReason( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getReason() );
	}

}