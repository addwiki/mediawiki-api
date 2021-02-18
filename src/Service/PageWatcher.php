<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\DataModel\Page;

/**
 * @access private
 *
 * @author Addshore
 */
class PageWatcher extends Service {

	/**
	 * @param Page $page
	 *
	 * @return bool
	 */
	public function watch( Page $page ) {
		$params = [
			'token' => $this->api->getToken( 'watch' ),
		];
		if ( $page->getPageIdentifier()->getId() !== null ) {
			$params['pageids'] = $page->getPageIdentifier()->getId();
		} elseif ( $page->getPageIdentifier()->getTitle() !== null ) {
			$params['titles'] = $page->getPageIdentifier()->getTitle()->getTitle();
		} elseif ( $page->getRevisions()->getLatest() !== null ) {
			$params['revids'] = $page->getRevisions()->getLatest()->getId();
		}

		$this->api->postRequest( new SimpleRequest( 'watch', $params ) );

		return true;
	}

}
