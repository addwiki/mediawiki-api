<?php

namespace Mediawiki\Api\Repos;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\User;

class ApiUserRepo {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param string $username
	 *
	 * @returns User
	 */
	public function getFromUsername( $username ) {
		$result = $this->api->getAction(
			'query', array(
				'list' => 'users',
				'ususers' => $username,
				'usprop' => 'gender|emailable|registration|editcount|rights|implicitgroups|groups|blockinfo',
				 )
		);
		return $this->newUserFromListUsersResult( array_shift( $result['query']['users'] ) );
	}

	/**
	 * @param array $array
	 *
	 * @return User
	 */
	private function newUserFromListUsersResult( $array ) {
		return new User(
			$array['name'],
			$array['userid'],
			$array['editcount'],
			$array['registration'],
			array_merge( $array['groups'], $array['implicitgroups'] ),
			$array['rights'],
			$array['gender']
		);
	}

}