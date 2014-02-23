<?php

namespace Mediawiki\Api\DataModel;

use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\Revision;

class NewRevision extends Revision {

	/**
	 * @param Revision $revision
	 *
	 * @return NewRevision
	 */
	public static function fromRevision( Revision $revision ) {
		return new self(
			$revision->getContent(),
			$revision->getPageId(),
			$revision->getId(),
			new NewEditInfo(),
			null,
			$revision->getTimestamp()
		);
	}

	/**
	 * @param mixed $content
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}

} 