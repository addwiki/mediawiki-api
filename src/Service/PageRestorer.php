<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\UndeleteOptions;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Title;
use OutOfBoundsException;

/**
 * @author Adam Shorland
 */
class PageRestorer {

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
	 * @param UndeleteOptions|null $options
	 *
	 * @return bool
	 */
	public function restore( Page $page, UndeleteOptions $options = null ) {
		$this->api->postAction( 'undelete', $this->getUndeleteParams( $page->getTitle(), $options ) );
		return true;
	}

	/**
	 * @param Title $title
	 * @param UndeleteOptions|null $options
	 *
	 * @return array
	 */
	private function getUndeleteParams( Title $title, $options ) {
		$params = array();

		$reason = $options->getReason();
		if( !empty( $reason ) ) {
			$params['reason'] = $reason;
		}
		$params['title'] = $title->getTitle();
		$params['token'] = $this->getUndeleteToken( $title );

		return $params;
	}

	/**
	 * @param Title $title
	 *
	 * @throws OutOfBoundsException
	 * @returns string
	 */
	private function getUndeleteToken( Title $title ) {
		$response = $this->api->postAction( 'query', array(
			'list' => 'deletedrevs',
			'titles' => $title->getTitle(),
			'drprop' => 'token'
		) );
		if( array_key_exists( 'token', $response['query']['deletedrevs'][0] ) ) {
			return $response['query']['deletedrevs'][0]['token'];
		} else {
			throw new OutOfBoundsException( 'Could not get page undelete token from list=deletedrevs query' );
		}
	}

} 