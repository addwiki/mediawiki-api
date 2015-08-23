<?php

namespace Mediawiki\Api\Options;

/**
 * @since 0.4
 *
 * @deprecated this should be removed
 */
class QueryOptions {

	/**
	 * @var bool
	 */
	private $followRedirects = false;

	/**
	 * @since 0.4
	 *
	 * @param bool $follow
	 *
	 * @return $this
	 */
	public function setFollowRedirects( $follow ) {
		$this->followRedirects = $follow;
		return $this;
	}

	/**
	 * @since 0.4
	 * @return bool
	 */
	public function getFollowRedirects() {
		return $this->followRedirects;
	}

} 