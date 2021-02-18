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
	 *
	 * @return bool
	 */
	public function delete( Page $page, array $extraParams = [] ) {
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
	 *
	 * @return bool
	 */
	public function deleteFromRevision( Revision $revision, array $extraParams = [] ) {
		$this->api->postRequest( new SimpleRequest(
			'delete',
			$this->getDeleteParams( $revision->getPageIdentifier(), $extraParams )
		) );
		return true;
	}

	/**
	 * @since 0.2
	 *
	 * @param int $pageid
	 * @param array $extraParams
	 *
	 * @return bool
	 */
	public function deleteFromPageId( $pageid, array $extraParams = [] ) {
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
	 *
	 * @return bool
	 */
	public function deleteFromPageTitle( $title, array $extraParams = [] ) {
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
	 * @param PageIdentifier $identifier
	 * @param array $extraParams
	 *
	 * @return array
	 */
	private function getDeleteParams( PageIdentifier $identifier, $extraParams ) {
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
