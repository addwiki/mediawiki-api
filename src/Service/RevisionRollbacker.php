<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Revision;

/**
 * @author Adam Shorland
 */
class RevisionRollbacker {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.3
	 *
	 * @param Revision $revision
	 *
	 * @return bool
	 */
	public function rollback( Revision $revision ) {
		$this->api->postAction( 'rollback', $this->getRollbackParams( $revision ) );
		return true;
	}

	/**
	 * @param Revision $revision
	 *
	 * @return array
	 */
	private function getRollbackParams( Revision $revision ) {
		$params = array();
		//TODO we need the title in this request
		throw new \BadMethodCallException( 'Not yet implemented' );
		$params['user'] = $revision->getUser();
		$params['token'] = $this->getTokenForRevision( $revision );
		return $params;
	}

	/**
	 * @param Revision $revision
	 *
	 * @returns string
	 */
	private function getTokenForRevision( Revision $revision ) {
		$result = $this->api->postAction( 'query', array(
			'prop' => 'revisions',
			'revids' => $revision->getId(),
			'rvtoken' => 'rollback',
		) );
		$result = array_shift( $result['query']['pages'] );
		return $result['revisions'][0]['rollbacktoken'];
	}

} 