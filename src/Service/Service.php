<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;

/**
 * The base service functions that all services inherit.
 * @since 0.7.2
 */
abstract class Service {

	/** @var MediawikiApi */
	protected $api;

	/**
	 * @param MediawikiApi $api The API to in for this service.
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

}
