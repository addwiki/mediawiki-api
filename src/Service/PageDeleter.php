<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\DeleteOptions;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Revision;

/**
 * @author Adam Shorland
 */
class PageDeleter {

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
	 * @since 0.2
	 *
	 * @param Page $page
	 * @param DeleteOptions|null $options
	 *
	 * @return bool
	 */
	public function delete( Page $page, DeleteOptions $options = null ) {
		$this->api->postRequest( new SimpleRequest(
			'delete',
			$this->getDeleteParams( $page->getPageIdentifier(), $options )
		) );
		return true;
	}

	/**
	 * @since 0.2
	 *
	 * @param Revision $revision
	 * @param DeleteOptions|null $options
	 *
	 * @return bool
	 */
	public function deleteFromRevision( Revision $revision, DeleteOptions $options = null ) {
		$this->api->postRequest( new SimpleRequest(
			'delete',
			$this->getDeleteParams( $revision->getPageIdentifier(), $options )
		) );
		return true;
	}

	/**
	 * @since 0.2
	 *
	 * @param int $pageid
	 * @param DeleteOptions|null $options
	 *
	 * @return bool
	 */
	public function deleteFromPageId( $pageid, DeleteOptions $options = null ) {
		$this->api->postRequest( new SimpleRequest(
			'delete',
			$this->getDeleteParams( new PageIdentifier( null, $pageid ), $options )
		) );
		return true;
	}

	/**
	 * @param PageIdentifier $identifier
	 * @param DeleteOptions|null $options
	 *
	 * @return array
	 */
	private function getDeleteParams( PageIdentifier $identifier, $options ) {
		$params = array();

		if( !is_null( $options ) ) {
			$reason = $options->getReason();
			if( !empty( $reason ) ) {
				$params['reason'] = $reason;
			}
		}

		if( !is_null( $identifier->getId() ) ) {
			$params['pageid'] = $identifier->getId();
		} else {
			$params['title'] = $identifier->getTitle()->getTitle();
		}

		$params['token'] = $this->api->getToken( 'delete' );

		return $params;
	}

} 