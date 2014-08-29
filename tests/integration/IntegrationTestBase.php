<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\MediawikiFactory;

class IntegrationTestBase extends \PHPUnit_Framework_TestCase {

	/**
	 * @var MediawikiFactory
	 */
	protected $factory;

	protected function setUp() {
		parent::setUp();
		$this->factory = new MediawikiFactory(
			new MediawikiApi( 'http://localhost/w/api.php' )
		);
	}

	// Needed to stop phpunit complaining
	public function testFactory() {
		$this->assertInstanceOf( 'Mediawiki\Api\MediawikiFactory', $this->factory );
	}

	protected function tearDown() {
		parent::tearDown();
		unset( $this->factory );
	}

}