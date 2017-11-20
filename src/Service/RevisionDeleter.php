<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Revision;

/**
 * @access private
 *
 * @author Addshore
 */
class RevisionDeleter extends Service {

	/**
	 * @since 0.5
	 *
	 * @param Revision $revision
	 *
	 * @return bool
	 */
	public function delete( Revision $revision ) {
		$params = [
			'type' => 'revision',
			'hide' => 'content',
			// Note: pre 1.24 this is a delete token, post it is csrf
			'token' => $this->api->getToken( 'delete' ),
			'ids' => $revision->getId(),
		];

		$this->api->postRequest( new SimpleRequest(
			'revisiondelete',
			$params
		) );

		return true;
	}

}
