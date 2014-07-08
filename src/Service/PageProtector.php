<?php

namespace Mediawiki\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Page;

/**
 * @author Adam Shorland
 */
class PageProtector {

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
	 * @since 0.3
	 *
	 * @param Page $page
	 * @param string[] $protections where the 'key' is the action and the 'value' is the group
	 * @param string $expiry
	 * @param string $reason
	 * @param bool $cascade
	 * @param string $watchlist
	 *
	 * @throws InvalidArgumentException
	 */
	public function protect( Page $page, $protections, $expiry = 'infinite', $reason = '', $cascade = false, $watchlist = 'preferences' ) {
		if( !is_array( $protections) || empty( $protections ) ) {
			throw new InvalidArgumentException( '$protections must be an array with keys and values' );
		}
		if( !is_string( $expiry ) ) {
			throw new InvalidArgumentException( '$expiry must be a string' );
		}
		if( !is_string( $reason ) ) {
			throw new InvalidArgumentException( '$reason must be a string' );
		}
		if( !is_bool( $cascade ) ) {
			throw new InvalidArgumentException( '$cascade must be either true or false' );
		}
		if( !is_string( $watchlist ) ) {
			throw new InvalidArgumentException( '$watchlist must be a string' );
		}

		$params = array(
			'pageid' => $page->getId(),
			'token' => $this->api->getToken( 'protect' ),
		);
		$protectionsString = '';
		foreach( $protections as $action => $value ) {
			if( !is_string( $action ) || !is_string( $value ) ) {
				throw new InvalidArgumentException( 'All keys and elements of $protections must be strings' );
			}
			$protectionsString = $action . '=' . $value . '|';
		}
		$params['protections'] = rtrim( $protectionsString, '|' );
		if( $expiry !== 'infinite' ) {
			$params['expiry'] = $expiry;
		}
		if( $reason !== '' ) {
			$params['reason'] = $reason;
		}
		if( $cascade ) {
			$params['cascade'] = '';
		}
		if( $watchlist !== 'preferences' ) {
			$params['watchlist'] = $watchlist;
		}

		$value = $this->api->postAction( 'protect', $params );
		var_dump( $value );die();
	}

} 