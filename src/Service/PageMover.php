<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\MoveOptions;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Title;

/**
 * @author Adam Shorland
 */
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
	 * @param MoveOptions|null $options
	 *
	 * @return bool
	 */
	public function move( Page $page, Title $target, MoveOptions $options = null ) {
		$this->api->postAction( 'move', $this->getMoveParams( $page->getId(), $target, $options ) );
		return true;
	}

	/**
	 * @since 0.2
	 *
	 * @param int $pageid
	 * @param Title $target
	 * @param MoveOptions|null $options
	 *
	 * @return bool
	 */
	public function moveFromPageId( $pageid, Title $target, MoveOptions $options = null ) {
		$this->api->postAction( 'move', $this->getMoveParams( $pageid, $target, $options ) );
		return true;
	}

	/**
	 * @param int $pageid
	 * @param Title $target
	 * @param MoveOptions|null $options
	 *
	 * @return array
	 */
	private function getMoveParams( $pageid, $target, $options ) {
		$params = array();
		$params['fromid'] = $pageid;
		$params['to'] = $target->getTitle();

		$reason = $options->getReason();
		if( !empty( $reason ) ) {
			$params['reason'] = $reason;
		}
		$params['token'] = $this->api->getToken( 'move' );
		return $params;
	}

} 