<?php

namespace Mediawiki\Api\Options;

/**
 * @since 0.4
 * @author Adam Shorland
 */
class PageRestoreOptions {

	/**
	 * @var string
	 */
	private $reason = '';

	/**
	 * @since 0.4
	 *
	 * @param string $reason
	 *
	 * @return $this
	 */
	public function setReason( $reason ) {
		$this->reason = $reason;
		return $this;
	}

	/**
	 * @since 0.4
	 * @return string
	 */
	public function getReason() {
		return $this->reason;
	}

} 