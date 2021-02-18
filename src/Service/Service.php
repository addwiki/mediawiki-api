<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;

/**
 * The base service functions that all services inherit.
 * @since 0.7.2
 */
abstract class Service {

	protected MediawikiApi $api;

	/**
	 * @param MediawikiApi $api The API to in for this service.
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

}
