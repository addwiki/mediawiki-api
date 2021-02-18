<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\Api\Client\UsageException;
use InvalidArgumentException;

/**
 * @access private
 */
class UserCreator extends Service {

	public function create( string $username, string $password, ?string $email = null ): bool {
		if ( !is_string( $username ) ) {
			throw new InvalidArgumentException( '$username should be a string' );
		}
		if ( !is_string( $password ) ) {
			throw new InvalidArgumentException( '$password should be a string' );
		}
		if ( !is_string( $email ) && $email !== null ) {
			throw new InvalidArgumentException( '$email should be a string or null' );
		}

		$params = [
			'createreturnurl' => $this->api->getApiUrl(),
			'createtoken' => $this->api->getToken( 'createaccount' ),
			'username' => $username,
			'password' => $password,
			'retype' => $password,
		];

		if ( $email !== null ) {
			$params['email'] = $email;
		}

		try {
			$result = $this->api->postRequest( new SimpleRequest( 'createaccount', $params ) );
			return $result['createaccount']['status'] === 'PASS';
		} catch ( UsageException $usageException ) {
			// If the above request failed, try again in the old way.
			if ( $usageException->getApiCode() === 'noname' ) {
				return $this->createPreOneTwentySeven( $params );
			}
			throw $usageException;
		}
	}

	/**
	 * Create a user in the pre 1.27 manner.
	 * @link https://www.mediawiki.org/wiki/API:Account_creation/pre-1.27
	 */
	protected function createPreOneTwentySeven( array $params ): bool {
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
