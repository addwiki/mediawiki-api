<?php

namespace Mediawiki\Api;

use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Service\Mediawiki\MediawikiApiClient;
use InvalidArgumentException;

class MediawikiApi {

	/**
	 * @var MediawikiApiClient
	 */
	private $client;

	/**
	 * @var bool|string
	 */
	private $isLoggedIn;

	/**
	 * @var array
	 */
	private $tokens = array();

	/**
	 * @param string|MediawikiApiClient $client either the url or the api or
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $client ) {
		if( is_string( $client ) ) {
			$client = MediawikiApiClient::factory( array( 'base_url' => $client ) );
		}
		if( !$client instanceof MediawikiApiClient ) {
			throw new InvalidArgumentException();
		}

		$this->client = $client;
		$this->client->addSubscriber( new CookiePlugin( new ArrayCookieJar() ) );
	}

	/**
	 * @param string $action
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getAction( $action, $params = array() ) {
		$resultArray = $this->client->getAction( array_merge( array( 'action' => $action ), $params ) );
		$this->throwUsageExceptions( $resultArray );
		return $resultArray;
	}

	/**
	 * @param string $action
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function postAction( $action, $params = array() ) {
		$resultArray = $this->client->postAction( array_merge( array( 'action' => $action ), $params ) );
		$this->throwUsageExceptions( $resultArray );
		return $resultArray;
	}

	/**
	 * @param array $result
	 *
	 * @throws UsageException
	 */
	private function throwUsageExceptions( $result ) {
		if( array_key_exists( 'error', $result ) ) {
			throw new UsageException( $result['error']['code'], $result['error']['info'] );
		}
	}

	/**
	 * @return bool|string false or the name of the current user
	 */
	public function isLoggedin() {
		return $this->isLoggedIn;
	}

	/**
	 * @param ApiUser $apiUser
	 *
	 * @return bool success
	 */
	public function login( ApiUser $apiUser ) {
		$credentials = array(
			'lgname' => $apiUser->getUsername(),
			'lgpassword' => $apiUser->getPassword()
		);
		$result = $this->postAction( 'login', $credentials, $apiUser );
		if ( $result['login']['result'] == "NeedToken" ) {
			$result = $this->postAction( 'login', array_merge( array( 'lgtoken' => $result['login']['token'] ), $credentials), $apiUser );
		}
		if ( $result['login']['result'] == "Success" ) {
			$this->isLoggedIn = $apiUser->getUsername();
			return true;
		}
		$this->isLoggedIn = false;
		return false;
	}

	/**
	 * @param ApiUser $user
	 *
	 * @return bool success
	 */
	public function logout( ApiUser $user ) {
		$result = $this->postAction( 'logout', array(), $user );
		if( $result === array() ) {
			$this->isLoggedIn = false;
			$this->clearTokens();
			return true;
		}
		return false;
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	public function getToken( $type = 'edit' ) {
		if( !array_key_exists( $type , $this->tokens ) ) {
			$result = $this->getAction( 'tokens', array( 'type' => $type ) );
			$this->tokens[$type] = array_pop( $result['tokens'] );
		}
		return $this->tokens[$type];
	}

	/**
	 * Clears all tokens stored by the api
	 */
	public function clearTokens() {
		$this->tokens = array();
	}

}
