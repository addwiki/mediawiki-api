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

	/** @var Mediawiki\Api\MediawikiFactory */
	private $factory;

	/**
	 * Set up the test environment by creating a new API object pointing to a
	 * MediaWiki installation on localhost (or elsewhere as specified by the
	 * MEDIAWIKI_API_URL environment variable).
	 *
	 * @throws \Exception If the MEDIAWIKI_API_URL environment variable is set but does not end in 'api.php'
	 */
	public function __construct() {
		$apiUrl = getenv('MEDIAWIKI_API_URL');
		if (empty($apiUrl)) {
			$apiUrl = 'http://localhost/w/api.php';
		} elseif (substr($apiUrl, -7) !== 'api.php') {
			$msg = "URL incorrect: $apiUrl (the MEDIAWIKI_API_URL environment variable should end in 'api.php')";
			throw new \Exception($msg);
		}
		$this->factory = new MediawikiFactory( new MediawikiApi( $apiUrl ) );
	}

	/**
	 * Get the MediaWiki factory.
	 *
	 * @return \Mediawiki\Api\MediawikiFactory The factory instance.
	 */
	public function getFactory() {
		return $this->factory;
	}

}
