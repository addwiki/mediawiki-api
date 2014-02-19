<?php

namespace Mediawiki\Api;

/**
 * Represents a user that can log in to the api
 */
class ApiUser {

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( $username, $password ) {
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param mixed $other
	 *
	 * @return bool
	 */
	public function equals( $other ) {
		if( $other instanceof ApiUser && $this->username == $other->getUsername() && $this->password == $other->getPassword() ) {
			return true;
		}
		return false;
	}

} 