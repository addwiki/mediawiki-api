<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;

class PageListRepo {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.3
	 *
	 * @param string $name
	 * @param bool|int $recursive layers of recursion to do
	 * @returns array
	 */
	public function getPageListFromCategoryName( $name, $recursive = false ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

}
