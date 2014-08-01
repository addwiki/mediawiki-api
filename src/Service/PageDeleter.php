<?php

namespace Mediawiki\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
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
	 * @var array
	 */
	private $params;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
		return $this;
	}

	/**
	 * @param Page|Revision|int $target int is for pageid
	 *
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function target( $target ) {
		if( $target instanceof Page ) {
			$id = $target->getId();
		} else if ( $target instanceof Revision ) {
			$id = $target->getPageId();
		} else if ( is_int( $target ) ) {
			$id = $target;
		} else {
			throw new InvalidArgumentException( '$target must be a Page Revison or int (pageid)' );
		}
		$this->params['pageid'] = $id;
		return $this;
	}

	/**
	 * @param string $reason
	 *
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function reason( $reason ) {
		if( !is_string( $reason ) ) {
			throw new InvalidArgumentException( '$reason must be a string' );
		}
		$this->params['reason'] = $reason;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function delete() {
		$this->params['token'] = $this->api->getToken( 'delete' );
		$this->api->postRequest( 'delete', $this->params );
		return true;
	}

} 