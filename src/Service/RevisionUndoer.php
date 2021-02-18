<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\DataModel\Revision;

/**
 * @access private
 */
class RevisionUndoer extends Service {

	/**
	 * @param Revision $revision
	 */
	public function undo( Revision $revision ): bool {
		$this->api->postRequest( new SimpleRequest(
			'edit',
			$this->getParamsFromRevision( $revision )
		) );
		return true;
	}

	/**
	 * @param Revision $revision
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
