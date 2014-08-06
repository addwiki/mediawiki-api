<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\DeleteOptions;
use Mediawiki\DataModel\Page;
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
		$this->api->postAction( 'delete', $this->getDeleteParams( $page->getId(), $options ) );
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
		$this->api->postAction( 'delete', $this->getDeleteParams( $revision->getPageId(), $options ) );
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
		$this->api->postAction( 'delete', $this->getDeleteParams( $pageid, $options ) );
		return true;
	}

	/**
	 * @param int $pageid
	 * @param DeleteOptions|null $options
	 *
	 * @return array
	 */
	private function getDeleteParams( $pageid, $options ) {
		$params = array();

		$reason = $options->getReason();
		if( !empty( $reason ) ) {
			$params['reason'] = $reason;
		}
		$params['pageid'] = $pageid;
		$params['token'] = $this->api->getToken( 'delete' );

		return $params;
	}

} 