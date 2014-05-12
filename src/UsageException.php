<?php

namespace Mediawiki\Api;

use Exception;

/**
 * Class representing a Mediawiki Api UsageException
 */
class UsageException extends Exception {

	/**
	 * @var string
	 */
	private $apiCode;

	/**
	 * @param string $apiCode
	 * @param string $message
	 */
	public function __construct( $apiCode = '', $message = '' ) {
		$this->apiCode = $apiCode;
		parent::__construct( $message, 0, null );
	}

	/**
	 * @return string
	 */
	public function getApiCode() {
		return $this->apiCode;
	}

} 