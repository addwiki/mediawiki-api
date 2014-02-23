<?php

namespace Mediawiki\Api\DataModel;

use Mediawiki\DataModel\EditInfo;

class NewEditInfo extends EditInfo {

	/**
	 * @param string $summary
	 */
	public function setSummary( $summary ) {
		$this->summary = $summary;
	}

	/**
	 * @param bool $minor
	 */
	public function setMinor( $minor ) {
		$this->minor = $minor;
	}

	/**
	 * @param bool $bot
	 */
	public function setBot( $bot ) {
		$this->bot = $bot;
	}

} 