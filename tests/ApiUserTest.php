<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\ApiUser;

/**
 * @covers Mediawiki\Api\ApiUser
 */
class ApiUserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testValidConstruction( $user, $pass ) {
		$apiUser = new ApiUser( $user, $pass );
		$this->assertEquals( $user, $apiUser->getUsername() );
		$this->assertEquals( $pass, $apiUser->getPassword() );
	}

	public function provideValidConstruction() {
		return array(
			array( 'user', 'pass' )
		);
	}

	/**
	 * @dataProvider provideInvalidConstruction
	 */
	public function testInvalidConstruction( $user, $pass ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		 new ApiUser( $user, $pass );
	}

	public function provideInvalidConstruction() {
		return array(
			array( 'user', '' ),
			array( '', 'pass' ),
			array( '', '' ),
			array( 'user', array() ),
			array( 'user', 455667 ),
			array( 34567, 'pass' ),
			array( array(), 'pass' ),
		);
	}

} 