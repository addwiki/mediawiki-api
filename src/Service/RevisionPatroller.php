<?php

namespace Addwiki\MediaWikiApi\Service;

use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Revision;

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
	public function patrol( Revision $revision ) {
		$this->api->postRequest( new SimpleRequest(
			'patrol', [
				'revid' => $revision->getId(),
				'token' => $this->getTokenForRevision( $revision ),
			] ) );
		return true;
	}

	/**
	 * @param Revision $revision
	 *
	 * @returns string
	 */
	private function getTokenForRevision( Revision $revision ) {
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
