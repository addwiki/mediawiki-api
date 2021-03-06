<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Exception\UsageException;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;

/**
 * @access private
 */
class UserCreator extends Service {

	public function create( string $username, string $password, ?string $email = null ): bool {
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
			$result = $this->api->request( ActionRequest::simplePost( 'createaccount', $params ) );
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
		$tokenRequest = ActionRequest::simplePost( 'createaccount', $newParams );
		$result = $this->api->request( $tokenRequest );
		if ( $result['createaccount']['result'] == 'NeedToken' ) {
			// Then send the token to create the account.
			$newParams['token'] = $result['createaccount']['token'];
			$request = ActionRequest::simplePost( 'createaccount', $newParams );
			$result = $this->api->request( $request );
		}
		return ( $result['createaccount']['result'] === 'Success' );
	}

}
