<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\PageIdentifier;

/**
 * @access private
 *
 * @author Adam Shorland
 */
class Parser {

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
	 * @param PageIdentifier $pageIdentifier
	 *
	 * @return array the parse result (raw from the api)
	 */
	public function parsePage( PageIdentifier $pageIdentifier ) {
		$params = array();
		if( $pageIdentifier->getId() !== null ) {
			$params['pageid'] = $pageIdentifier->getId();
		} elseif( $pageIdentifier->getTitle() !== null ) {
			$params['page'] = $pageIdentifier->getTitle()->getText();
		} else {
			throw new \RuntimeException( 'No way to identify page' );
		}

		$result = $this->api->getRequest( new SimpleRequest( 'parse', $params ) );

		return $result['parse'];
	}

}
