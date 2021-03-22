<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Page;
use Addwiki\Mediawiki\DataModel\Title;

/**
 * @access private
 */
class PageMover extends Service {

	public function move( Page $page, Title $target, array $extraParams = [] ): bool {
		$this->api->request(
			ActionRequest::simplePost(
				'move', $this->getMoveParams( $page->getId(), $target, $extraParams )
			)
		);

		return true;
	}

	public function moveFromPageId( int $pageid, Title $target, array $extraParams = [] ): bool {
		$this->api->request(
			ActionRequest::simplePost( 'move', $this->getMoveParams( $pageid, $target, $extraParams ) )
		);

		return true;
	}

	/**
	 * @return mixed[]
	 */
	private function getMoveParams( int $pageid, Title $target, array $extraParams ): array {
		$params = [];
		$params['fromid'] = $pageid;
		$params['to'] = $target->getTitle();
		$params['token'] = $this->api->getToken( 'move' );

		return array_merge( $extraParams, $params );
	}

}
