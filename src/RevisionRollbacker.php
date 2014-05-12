<?php

namespace Mediawiki\Api\Service;

use Mediawiki\DataModel\Revision;

/**
 * @author Adam Shorland
 */
class RevisionRollbacker {

	/**
	 * @since 0.3
	 *
	 * @param Revision $revision
	 */
	public function rollback( Revision $revision ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 