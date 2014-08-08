<?php

namespace Mediawiki\Api\Options;

/**
 * @since 0.4
 * @author Adam Shorland
 */
class ListCategoryMembersOptions {

	/**
	 * @var bool|int number of layers of recursion to do
	 */
	private $recursive = false;
	/**
	 * @var int|null
	 */
	private $limit = null;

	/**
	 * @since 0.4
	 *
	 * @param $recursive
	 *
	 * @return $this
	 */
	public function setRecursive( $recursive ) {
		$this->recursive = $recursive;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return bool|int
	 */
	public function getRecursive() {
		return $this->recursive;
	}

	/**
	 * @since 0.4
	 * @return int|null
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @since 0.4
	 * @param int $limit
	 *
	 * @return $this
	 */
	public function setLimit( $limit ) {
		$this->limit = $limit;
		return $this;
	}

} 