<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\User;

/**
 * @access private
 */
class UserGetter extends Service {

	public function getFromUsername( string $username ): User {
		$result = $this->api->request(
			ActionRequest::simpleGet(
				'query', [
				'list' => 'users',
				'ususers' => $username,
				'usprop' => 'gender|emailable|registration|editcount|rights|implicitgroups|groups|blockinfo',
			]
			)
		);

		return $this->newUserFromListUsersResult( array_shift( $result['query']['users'] ) );
	}

	/**
	 *
	 * @return User|void
	 */
	private function newUserFromListUsersResult( array $array ) {
		if ( array_key_exists( 'userid', $array ) ) {
			return new User(
				$array['name'],
				$array['userid'],
				$array['editcount'],
				$array['registration'],
				[ 'groups' => $array['groups'], 'implicitgroups' => $array['implicitgroups'] ],
				$array['rights'],
				$array['gender']
			);
		} else {
			return new User(
				$array['name'],
				0,
				0,
				'',
				[ 'groups' => [], 'implicitgroups' => [] ],
				[],
				''
			);
		}
	}

}
