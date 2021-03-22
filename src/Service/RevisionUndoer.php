<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Revision;

/**
 * @access private
 */
class RevisionUndoer extends Service {

	public function undo( Revision $revision ): bool {
		$this->api->request( ActionRequest::simplePost(
			'edit',
			$this->getParamsFromRevision( $revision )
		) );
		return true;
	}

	/**
	 *
	 * @return array <string int|string|null>
	 */
	private function getParamsFromRevision( Revision $revision ): array {
		$params = [
			'undo' => $revision->getId(),
			'token' => $this->api->getToken(),
		];

		if ( $revision->getPageIdentifier()->getId() !== null ) {
			$params['pageid'] = $revision->getPageIdentifier()->getId();
		} else {
			$params['title'] = $revision->getPageIdentifier()->getTitle()->getTitle();
		}

		return $params;
	}

}
