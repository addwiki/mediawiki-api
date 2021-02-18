<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\DataModel\Revision;

/**
 * @access private
 *
 * @author Addshore
 */
class RevisionPatroller extends Service {

	/**
	 * @since 0.3
	 *
	 * @param Revision $revision
	 *
	 * @return bool success
	 */
	public function patrol( Revision $revision ): bool {
		$this->api->postRequest( new SimpleRequest(
			'patrol', [
				'revid' => $revision->getId(),
				'token' => $this->getTokenForRevision( $revision ),
			] ) );
		return true;
	}

	/**
	 * @param Revision $revision
	 */
	private function getTokenForRevision( Revision $revision ): string {
		$result = $this->api->postRequest( new SimpleRequest( 'query', [
			'list' => 'recentchanges',
			'rcstart' => $revision->getTimestamp(),
			'rcend' => $revision->getTimestamp(),
			'rctoken' => 'patrol',
		] ) );
		$result = array_shift( $result['query']['recentchanges'] );
		return $result['patroltoken'];
	}

}
