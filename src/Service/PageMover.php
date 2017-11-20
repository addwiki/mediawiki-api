<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Title;

/**
 * @access private
 *
 * @author Addshore
 */
class PageMover extends Service {

	/**
	 * @since 0.2
	 *
	 * @param Page $page
	 * @param Title $target
	 * @param array $extraParams
	 *
	 * @return bool
	 */
	public function move( Page $page, Title $target, array $extraParams = [] ) {
		$this->api->postRequest(
			new SimpleRequest(
				'move', $this->getMoveParams( $page->getId(), $target, $extraParams )
			)
		);

		return true;
	}

	/**
	 * @since 0.2
	 *
	 * @param int $pageid
	 * @param Title $target
	 * @param array $extraParams
	 *
	 * @return bool
	 */
	public function moveFromPageId( $pageid, Title $target, array $extraParams = [] ) {
		$this->api->postRequest(
			new SimpleRequest( 'move', $this->getMoveParams( $pageid, $target, $extraParams ) )
		);

		return true;
	}

	/**
	 * @param int $pageid
	 * @param Title $target
	 * @param array $extraParams
	 *
	 * @return array
	 */
	private function getMoveParams( $pageid, $target, $extraParams ) {
		$params = [];
		$params['fromid'] = $pageid;
		$params['to'] = $target->getTitle();
		$params['token'] = $this->api->getToken( 'move' );

		return array_merge( $extraParams, $params );
	}

}
