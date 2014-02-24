<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Page;

class PageDeleter {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param Page $page
	 * @param null|string $reason
	 *
	 * @return bool
	 */
	public function delete( Page $page, $reason = null ) {
		$this->api->postAction( 'delete', $this->getDeleteParams( $page, $reason ) );
		return true;
	}

	/**
	 * @param Page $page
	 * @param string|null $reason
	 *
	 * @return array
	 */
	private function getDeleteParams( Page $page, $reason ) {
		$params = array();

		if( !is_null( $reason ) ) {
			$params['reason'] = $reason;
		}
		$params['pageid'] = $page->getId();
		$params['token'] = $this->api->getToken( 'delete' );

		return $params;
	}

} 