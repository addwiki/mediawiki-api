<?php

namespace Addwiki\Mediawiki\Api\Tests\Integration\Service;

use Addwiki\Mediawiki\Api\Client\ApiUser;
use Addwiki\Mediawiki\Api\Tests\Integration\TestEnvironment;
use PHPUnit\Framework\TestCase;

/**
 * @author Addshore
 */
class UserIntegrationTest extends TestCase {

	private static ?ApiUser $localApiUser = null;

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		$strTime = strval( time() );
		self::$localApiUser = new ApiUser( 'TestUser - ' . strval( time() ), $strTime . '-pass' );
	}

	public function testCreateUser(): void {
		$factory = TestEnvironment::newInstance()->getFactory();
		$createResult = $factory->newUserCreator()->create(
			self::$localApiUser->getUsername(),

			self::$localApiUser->getPassword()
		);
		$this->assertTrue( $createResult );
	}

}
