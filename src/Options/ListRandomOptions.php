<?php

namespace Mediawiki\Api\Options;

/**
 * @since 0.4
 * @author Adam Shorland
 */
class ListRandomOptions {

	/**
	 * @var array
	 */
	private $namespaces = array();

	/**
	 * @var bool
	 */
	private $redirectsOnly = false;

	/**
	 * @var int
	 */
	private $limit = 1;

	/**
	 * @return mixed
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @param mixed $limit
	 */
	public function setLimit( $limit ) {
		$this->limit = $limit;
	}

	/**
	 * @return array
	 */
	public function getNamespaces() {
		return $this->namespaces;
	}

	/**
	 * @param array $namespaces
	 */
	public function setNamespaces( array $namespaces ) {
		$this->namespaces = $namespaces;
	}

	/**
	 * @return boolean
	 */
	public function getRedirectsOnly() {
		return $this->redirectsOnly;
	}

	/**
	 * @param boolean $redirectsOnly
	 */
	public function setRedirectsOnly( $redirectsOnly ) {
		$this->redirectsOnly = $redirectsOnly;
	}



} 