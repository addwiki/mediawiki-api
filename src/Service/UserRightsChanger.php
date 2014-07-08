<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\User;

/**
 * @author Adam Shorland
 */
class UserRightsChanger {

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
	 * @param User $user
	 * @param string[] $add
	 * @param string[] $remove
	 * @param string $reason
	 *
	 * @return bool
	 */
	public function change( User $user, $add = array(), $remove = array(), $reason = '' ) {
		$result = $this->api->postAction( 'query', array(
			'list' => 'users',
			'ustoken' => 'userrights',
			'ususers' => $user->getName(),
		) );

		$params = array(
			'user' => $user->getName(),
			'token' => $result['query']['users'][0]['userrightstoken'],
		);
		if( !empty( $reason ) ) {
			$params['reason'] = $reason;
		}
		if( !empty( $add ) ) {
			$params['add'] = implode( '|', $add );
		}
		if( !empty( $remove ) ) {
			$params['remove'] = implode( '|', $remove );
		}

		$this->api->postAction( 'userrights', $params );
		return true;
	}

} 