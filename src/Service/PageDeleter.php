<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\DataModel\Page;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Mediawiki\DataModel\Title;

/**
 * @access private
 *
 * @author Addshore
 */
class PageDeleter extends Service {

	/**
	 * @since 0.2
	 *
	 * @param Page $page
	 * @param array $extraParams
	 */
	public function delete( Page $page, array $extraParams = [] ): bool {
		$this->api->postRequest( new SimpleRequest(
			'delete',
			$this->getDeleteParams( $page->getPageIdentifier(), $extraParams )
		) );
		return true;
	}

	/**
	 * @since 0.2
	 *
	 * @param Revision $revision
	 * @param array $extraParams
	 */
	public function deleteFromRevision( Revision $revision, array $extraParams = [] ): bool {
		$this->api->postRequest( new SimpleRequest(
			'delete',
			$this->getDeleteParams( $revision->getPageIdentifier(), $extraParams )
		) );
		return true;
	}

	/**
	 * @since 0.2
	 *
	 *
	 */
	public function deleteFromPageId( int $pageid, array $extraParams = [] ): bool {
		$this->api->postRequest( new SimpleRequest(
			'delete',
			$this->getDeleteParams( new PageIdentifier( null, $pageid ), $extraParams )
		) );
		return true;
	}

	/**
	 * @since 0.5
	 *
	 * @param Title|string $title
	 * @param array $extraParams
	 */
	public function deleteFromPageTitle( $title, array $extraParams = [] ): bool {
		if ( is_string( $title ) ) {
			$title = new Title( $title );
		}
		$this->api->postRequest( new SimpleRequest(
			'delete',
			$this->getDeleteParams( new PageIdentifier( $title ), $extraParams )
		) );
		return true;
	}

	/**
	 *
	 * @return mixed[]
	 */
	private function getDeleteParams( PageIdentifier $identifier, array $extraParams ): array {
		$params = [];

		if ( $identifier->getId() !== null ) {
			$params['pageid'] = $identifier->getId();
		} else {
			$params['title'] = $identifier->getTitle()->getTitle();
		}

		$params['token'] = $this->api->getToken( 'delete' );

		return array_merge( $extraParams, $params );
	}

}
