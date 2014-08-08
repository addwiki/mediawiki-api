<?php

namespace Mediawiki\Api\Options;

/**
 * @since 0.4
 * @author Adam Shorland
 */
class ListEmbededInOptions {

	/**
	 * @var array
	 */
	private $namespaces = array();
	/**
	 * @var int|null
	 */
	private $limit = null;

	/**
	 * @since 0.4
	 *
	 * @param $namespaces
	 *
	 * @return $this
	 */
	public function setNamespaces( $namespaces ) {
		$this->namespaces = $namespaces;
		return $this;
	}

	/**
	 * @since 0.4
	 *
	 * @return array
	 */
	public function getNamespaces() {
		return $this->namespaces;
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