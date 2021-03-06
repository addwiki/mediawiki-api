<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Revision;

/**
 * @access private
 */
class RevisionRestorer extends Service {

	public function restore( Revision $revision ): bool {
		$params = [
			'type' => 'revision',
			'show' => 'content',
			// Note: pre 1.24 this is a delete token, post it is csrf
			'token' => $this->api->getToken( 'delete' ),
			'ids' => $revision->getId(),
		];

		$this->api->request( ActionRequest::simplePost(
			'revisiondelete',
			$params
		) );

		return true;
	}

}
