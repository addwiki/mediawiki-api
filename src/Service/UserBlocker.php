<?php

namespace Mediawiki\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\BlockOptions;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\User;

/**
 * @author Adam Shorland
 */
class UserBlocker {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.3
	 *
	 * @param User|string $user
	 * @param BlockOptions $options
	 *
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	public function block( $user, BlockOptions $options = null ) {
		if( !$user instanceof User && !is_string( $user ) ) {
			throw new InvalidArgumentException( '$user must be either a string or User object' );
		}

		if( $user instanceof User ) {
			$user = $user->getName();
		}

		$params = array(
			'user' => $user,
			'token' => $this->api->getToken( 'block' ),
		);
		if( $options->getExpiry() !== 'never' ) {
			$params['expiry'] = $options->getExpiry();
		}
		$reason = $options->getReason();
		if( !empty( $reason ) ) {
			$params['reason'] = $reason;
		}
		if( $options->getAllowusertalk() ){
			$params['allowusertalk'] = '';
		}
		if( $options->getAnononly() ){
			$params['allowusertalk'] = '';
		}
		if( $options->getAutoblock() ){
			$params['allowusertalk'] = '';
		}
		if( $options->getHidename() ){
			$params['allowusertalk'] = '';
		}
		if( $options->getNocreate() ){
			$params['allowusertalk'] = '';
		}
		if( $options->getNoemail() ){
			$params['allowusertalk'] = '';
		}
		if( $options->getReblock() ){
			$params['allowusertalk'] = '';
		}
		if( $options->getWatchuser() ){
			$params['allowusertalk'] = '';
		}

		$this->api->postRequest( new SimpleRequest( 'block', $params ) );
		return true;
	}

}
