<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Page;
use InvalidArgumentException;

/**
 * @access private
 */
class PageProtector extends Service {

	/**
	 * @param string[] $protections where the 'key' is the action and the 'value' is the group
	 *
	 * @throws InvalidArgumentException
	 */
	public function protect( Page $page, array $protections, array $extraParams = [] ): bool {
		if ( !is_array( $protections ) || empty( $protections ) ) {
			throw new InvalidArgumentException(
				'$protections must be an array with keys and values'
			);
		}

		$params = [
			'pageid' => $page->getId(),
			'token' => $this->api->getToken( 'protect' ),
		];
		$protectionsString = '';
		foreach ( $protections as $action => $value ) {
			if ( !is_string( $action ) || !is_string( $value ) ) {
				throw new InvalidArgumentException(
					'All keys and elements of $protections must be strings'
				);
			}
			$protectionsString = $action . '=' . $value . '|';
		}
		$params['protections'] = rtrim( $protectionsString, '|' );

		$this->api->request(
			ActionRequest::simplePost( 'protect', array_merge( $extraParams, $params ) )
		);

		return true;
	}

}
