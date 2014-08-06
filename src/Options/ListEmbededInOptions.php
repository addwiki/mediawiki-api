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

} 