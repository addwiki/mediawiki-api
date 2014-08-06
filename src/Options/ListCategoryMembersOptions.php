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

} 