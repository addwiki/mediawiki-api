<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Revision;

/**
 * @author Adam Shorland
 */
class RevisionRestorer {

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
	 */
	public function restore( Revision $revision ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 