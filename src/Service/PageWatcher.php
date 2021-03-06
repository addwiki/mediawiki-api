<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Page;

/**
 * @access private
 */
class PageWatcher extends Service {

	public function watch( Page $page ): bool {
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

		$this->api->request( ActionRequest::simplePost( 'watch', $params ) );

		return true;
	}

}
