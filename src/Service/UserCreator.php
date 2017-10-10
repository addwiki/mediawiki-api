<?php

namespace Mediawiki\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\Api\UsageException;

/**
 * @access private
 *
 * @author Addshore
 */
class UserCreator extends Service {

	/**
	 * @param string $username
	 * @param string $password
	 * @param string|null $email
	 *
	 * @return bool
	 */
	public function create( $username, $password, $email = null ) {
		if ( !is_string( $username ) ) {
			throw new InvalidArgumentException( '$username should be a string' );
		}
		if ( !is_string( $password ) ) {
			throw new InvalidArgumentException( '$password should be a string' );
		}
		if ( !is_string( $email ) && !is_null( $email ) ) {
			throw new InvalidArgumentException( '$email should be a string or null' );
		}

		$params = [
			'createreturnurl' => $this->api->getApiUrl(),
			'createtoken' => $this->api->getToken( 'createaccount' ),
			'username' => $username,
			'password' => $password,
			'retype' => $password,
		];

		if ( !is_null( $email ) ) {
			$params['email'] = $email;
		}

		try {
			$result = $this->api->postRequest( new SimpleRequest( 'createaccount', $params ) );
			return $result['createaccount']['status'] === 'PASS';
		} catch ( UsageException $exception ) {
			// If the above request failed, try again in the old way.
			if ( $exception->getApiCode() === 'noname' ) {
				return $this->createPreOneTwentySeven( $params );
			}
			throw $exception;
		}
	}

	/**
	 * Create a user in the pre 1.27 manner.
	 * @link https://www.mediawiki.org/wiki/API:Account_creation/pre-1.27
	 * @return bool
	 */
	protected function createPreOneTwentySeven( $params ) {
		$newParams = [
			'name' => $params['username'],
			'password' => $params['password'],
		];
		if ( array_key_exists( 'email', $params ) ) {
			$newParams['email'] = $params['email'];
		}
		// First get the token.
		$tokenRequest = new SimpleRequest( 'createaccount', $newParams );
		$result = $this->api->postRequest( $tokenRequest );
		if ( $result['createaccount']['result'] == 'NeedToken' ) {
			// Then send the token to create the account.
			$newParams['token'] = $result['createaccount']['token'];
			$request = new SimpleRequest( 'createaccount', $newParams );
			$result = $this->api->postRequest( $request );
		}
		return ( $result['createaccount']['result'] === 'Success' );
	}

}
