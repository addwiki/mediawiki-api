<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Title;

class PageMover {

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
	 * @since 0.2
	 *
	 * @param Page $page
	 * @param Title $target
	 * @param string|null $reason
	 *
	 * @return bool
	 */
	public function move( Page $page, Title $target, $reason = null ) {
		$this->api->postAction( 'move', $this->getMoveParams( $page->getId(), $target, $reason ) );
		return true;
	}

	/**
	 * @since 0.2
	 *
	 * @param int $pageid
	 * @param Title $target
	 * @param null $reason
	 *
	 * @return bool
	 */
	public function moveFromPageId( $pageid, Title $target, $reason = null ) {
		$this->api->postAction( 'move', $this->getMoveParams( $pageid, $target, $reason ) );
		return true;
	}

	/**
	 * @param int $pageid
	 * @param Title $target
	 * @param string|null $reason
	 *
	 * @return array
	 */
	private function getMoveParams( $pageid, $target, $reason ) {
		$params = array();
		$params['fromid'] = $pageid;
		$params['to'] = $target->getTitle();
		if( !is_null( $reason ) ) {
			$params['reason'] = $reason;
		}
		$params['token'] = $this->api->getToken( 'move' );
		return $params;
	}

} 