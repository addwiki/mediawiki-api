<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Title;

class PageRestorer {

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
	 * @param Page $page
	 * @param string $reason
	 *
	 * @return bool
	 */
	public function restore( Page $page, $reason = null ) {
		$this->api->postAction( 'undelete', $this->getUndeleteParams( $page->getTitle(), $reason ) );
		return true;
	}

	/**
	 * @param Title $title
	 * @param string|null $reason
	 *
	 * @return array
	 */
	private function getUndeleteParams( Title $title, $reason ) {
		$params = array();

		if( !is_null( $reason ) ) {
			$params['reason'] = $reason;
		}
		$params['title'] = $title->getTitle();
		$params['token'] = $this->getUndeleteToken( $title );

		return $params;
	}

	/**
	 * @param Title $title
	 * @returns string
	 */
	private function getUndeleteToken( Title $title ) {
		$response = $this->api->postAction( 'query', array(
			'list' => 'deletedrevs',
			'titles' => $title->getTitle(),
			'drprop' => 'token'
		) );
		if( array_key_exists( 'token', $response['query']['deletedrevs'][0] ) ) {
			return $response['query']['deletedrevs'][0]['token'];
		} else {
			//TODO THROW EXCEPTION
			die();
		}
	}

} 