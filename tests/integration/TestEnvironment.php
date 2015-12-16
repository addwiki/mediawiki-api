<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\MediawikiFactory;

/**
 * @author Addshore
 */
class TestEnvironment {

	public static function newDefault() {
		return new self();
	}

	private $factory;

	public function __construct() {
		$this->factory = new MediawikiFactory( new MediawikiApi( 'http://localhost/w/api.php' ) );
	}

	public function getFactory() {
		return $this->factory;
	}

}
