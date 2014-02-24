<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Title;

class PageMover {

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
	 * @param Title $target
	 * @param string|null $reason
	 *
	 * @return bool
	 */
	public function move( Page $page, Title $target, $reason = null ) {
		$this->api->postAction( 'move', $this->getMoveParams( $page, $target, $reason ) );
		return true;
	}

	/**
	 * @param Page $page
	 * @param Title $target
	 * @param string|null $reason
	 *
	 * @return array
	 */
	private function getMoveParams( $page, $target, $reason ) {
		$params = array();
		$params['fromid'] = $page->getId();
		$params['to'] = $target->getTitle();
		if( !is_null( $reason ) ) {
			$params['reason'] = $reason;
		}
		$params['token'] = $this->api->getToken( 'move' );
		return $params;
	}

} 