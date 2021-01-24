<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\ApiUser;

/**
 * @author Addshore
 */
class UserIntegrationTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @var ApiUser
	 */
	private static $localApiUser;

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		$strTime = strval( time() );
		self::$localApiUser = new ApiUser( 'TestUser - ' . strval( time() ), $strTime . '-pass' );
	}

	public function testCreateUser() {
		$factory = TestEnvironment::newInstance()->getFactory();
		$createResult = $factory->newUserCreator()->create(
			self::$localApiUser->getUsername(),

			self::$localApiUser->getPassword()
		);
		$this->assertTrue( $createResult );
	}

}
