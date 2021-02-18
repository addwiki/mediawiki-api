<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\DataModel\Revision;

/**
 * @access private
 *
 * @author Addshore
 */
class RevisionUndoer extends Service {

	/**
	 * @param Revision $revision
	 *
	 * @return bool
	 */
	public function undo( Revision $revision ) {
		$this->api->postRequest( new SimpleRequest(
			'edit',
			$this->getParamsFromRevision( $revision )
		) );
		return true;
	}

	/**
	 * @param Revision $revision
	 *
	 * @return array
	 */
	private function getParamsFromRevision( Revision $revision ) {
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
