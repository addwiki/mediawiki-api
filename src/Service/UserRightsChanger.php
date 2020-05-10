<?php

namespace Addwiki\MediaWikiApi\Service;

use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\User;

/**
 * @access private
 *
 * @author Addshore
 */
class UserRightsChanger extends Service {

	/**
	 * @since 0.3
	 *
	 * @param User $user
	 * @param string[] $add
	 * @param string[] $remove
	 * @param array $extraParams
	 *
	 * @return bool
	 */
	public function change(
		User $user,
		$add = [],
		$remove = [],
		array $extraParams = []
	) {
		$result = $this->api->postRequest(
			new SimpleRequest(
				'query', [
				'list' => 'users',
				'ustoken' => 'userrights',
				'ususers' => $user->getName(),
			]
			)
		);

		$params = [
			'user' => $user->getName(),
			'token' => $result['query']['users'][0]['userrightstoken'],
		];
		if ( !empty( $add ) ) {
			$params['add'] = implode( '|', $add );
		}
		if ( !empty( $remove ) ) {
			$params['remove'] = implode( '|', $remove );
		}

		$this->api->postRequest(
			new SimpleRequest( 'userrights', array_merge( $extraParams, $params ) )
		);

		return true;
	}

}
