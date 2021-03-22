<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Mediawiki\DataModel\Title;

/**
 * @access private
 */
class RevisionRollbacker extends Service {

	/**
	 * @param Title|null $title if using MW 1.24 of lower (https://gerrit.wikimedia.org/r/#/c/133063/)
	 */
	public function rollback( Revision $revision, Title $title = null ): bool {
		$this->api->request(
			ActionRequest::simplePost( 'rollback', $this->getRollbackParams( $revision, $title ) )
		);

		return true;
	}

	/**
	 *
	 * @return array <string mixed>|array<string, string|null>
	 */
	private function getRollbackParams( Revision $revision, ?Title $title ): array {
		$params = [];
		if ( $title !== null ) {
			// This is needed prior to https://gerrit.wikimedia.org/r/#/c/133063/
			$params['title'] = $title->getText();
		} else {
			// This will work after https://gerrit.wikimedia.org/r/#/c/133063/
			$params['pageid'] = $revision->getPageIdentifier()->getId();
		}
		$params['user'] = $revision->getUser();
		$params['token'] = $this->getTokenForRevision( $revision );

		return $params;
	}

	private function getTokenForRevision( Revision $revision ): string {
		$result = $this->api->request(
			ActionRequest::simplePost(
				'query', [
				'prop' => 'revisions',
				'revids' => $revision->getId(),
				'rvtoken' => 'rollback',
			]
			)
		);
		$result = array_shift( $result['query']['pages'] );

		return $result['revisions'][0]['rollbacktoken'];
	}

}
