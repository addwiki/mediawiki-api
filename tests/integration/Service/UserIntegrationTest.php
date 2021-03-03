<?php

namespace Addwiki\Mediawiki\Api\Tests\Integration\Service;

use Addwiki\Mediawiki\Api\MediawikiFactory;
use Addwiki\Mediawiki\Api\Tests\Integration\TestEnvironment;
use PHPUnit\Framework\TestCase;

class UserIntegrationTest extends TestCase {

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
	}

	public function testCreateUser(): void {
		$strTime = strval( time() );

		$testEnvironment = TestEnvironment::newInstance();
		$factory = new MediawikiFactory( $testEnvironment->getApi() );
		$createResult = $factory->newUserCreator()->create(
			'TestUser - ' . $strTime,
			$strTime . '-pass'
		);
		$this->assertTrue( $createResult );
	}

}
