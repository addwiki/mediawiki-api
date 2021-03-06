<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\User;

/**
 * @access private
 */
class UserRightsChanger extends Service {

	/**
	 * @param string[] $add
	 * @param string[] $remove
	 */
	public function change(
		User $user,
		array $add = [],
		array $remove = [],
		array $extraParams = []
	): bool {
		$result = $this->api->request(
			ActionRequest::simplePost(
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

		$this->api->request(
			ActionRequest::simplePost( 'userrights', array_merge( $extraParams, $params ) )
		);

		return true;
	}

}
