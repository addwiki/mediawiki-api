<?php

namespace Mediawiki\Api\Options;

/**
 * @since 0.4
 * @author Adam Shorland
 */
class ProtectOptions {

	/**
	 * @var string
	 */
	private $reason = '';

	/**
	 * @var string
	 */
	private $expiry = 'infinite';

	/**
	 * @var bool
	 */
	private $cascade = false;

	/**
	 * @var string
	 */
	private $watchlist = 'preferences';

	/**
	 * @since 0.4
	 *
	 * @param boolean $cascade
	 *
	 * @return $this
	 */
	public function setCascade( $cascade ) {
		$this->cascade = $cascade;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getCascade() {
		return $this->cascade;
	}

	/**
	 * @since 0.4
	 *
	 * @param string $expiry
	 *
	 * @return $this
	 */
	public function setExpiry( $expiry ) {
		$this->expiry = $expiry;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getExpiry() {
		return $this->expiry;
	}

	/**
	 * @since 0.4
	 *
	 * @param string $watchlist
	 *
	 * @return $this
	 */
	public function setWatchlist( $watchlist ) {
		$this->watchlist = $watchlist;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getWatchlist() {
		return $this->watchlist;
	}

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