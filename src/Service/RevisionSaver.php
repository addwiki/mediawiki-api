<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\Revision;
use RuntimeException;

/**
 * @author Adam Shorland
 */
class RevisionSaver {

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
	 * @param Revision $revision
	 * @param EditInfo $editInfo
	 *
	 * @returns bool success
	 */
	public function save( Revision $revision, EditInfo $editInfo = null ) {
		$result = $this->api->postRequest( new SimpleRequest( 'edit', $this->getEditParams( $revision, $editInfo ) ) );
		if( $result['edit']['result'] == 'Success' ) {
			return true;
		}
		return false;
	}

	/**
	 * @param Revision $revision
	 * @param EditInfo $editInfo
	 *
	 * @throws RuntimeException
	 * @returns array
	 */
	private function getEditParams( Revision $revision, EditInfo $editInfo = null ) {
		if( !$revision->getPageIdentifier()->identifiesPage() ) {
			throw new RuntimeException( '$revision PageIdentifier does not identify a page' );
		}

		$params = array();
		$assertions = array();

		$content = $revision->getContent();
		$data = $content->getData();
		if( !is_string( $data ) ) {
			throw new RuntimeException( 'Dont know how to save content of this model.' );
		}
		$params['text'] = $content->getData();
		$params['md5'] = md5( $content->getData() );

		$timestamp = $revision->getTimestamp();
		if( !is_null( $timestamp ) ) {
			$params['basetimestamp'] = $timestamp;
		}

		if( !is_null( $revision->getPageIdentifier()->getId() ) ) {
			$params['pageid'] = $revision->getPageIdentifier()->getId();
		} else {
			$params['title'] = $revision->getPageIdentifier()->getTitle()->getTitle();
		}

		$params['token'] = $this->api->getToken();

		$params = array_merge( $params, $this->getEditInfoParams( $editInfo ) );

		if( $this->api->isLoggedin() ) {
			$assertions[] = 'user';
		}
		if( !empty( $assertions ) ) {
			$params['assert'] = implode( '|', $assertions );
		}
		return $params;
	}

	/**
	 * @param null|EditInfo $editInfo
	 *
	 * @return array
	 */
	private function getEditInfoParams( $editInfo ) {
		$params = array();
		$assertions = array();
		if( !is_null( $editInfo ) ) {
			$params['summary'] = $editInfo->getSummary();
			if( $editInfo->getMinor() ) {
				$params['minor'] = true;
			}
			if( $editInfo->getBot() ) {
				$params['bot'] = true;
				$assertions[] = 'bot';
			}
		}
		return $params;
	}

}
