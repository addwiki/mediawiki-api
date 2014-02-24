<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\Revision;
use Mediawiki\DataModel\WikitextContent;
use RuntimeException;

class RevisionSaver {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param Revision $revision
	 * @param EditInfo $editInfo
	 *
	 * @returns bool success
	 */
	public function save( Revision $revision, EditInfo $editInfo = null ) {
		$result = $this->api->postAction( 'edit', $this->getEditParams( $revision, $editInfo ) );
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
		$params = array();
		$assertions = array();

		$content = $revision->getContent();
		switch ( $content->getModel() ) {
			case WikitextContent::contentModel;
				/** @var $content WikitextContent */
				$params['text'] = $content->getText();
				$params['md5'] = md5( $content->getText() );
				break;
			default:
				throw new RuntimeException( 'Dont know how to save content of this model' );
		}

		$timestamp = $revision->getTimestamp();
		if( !is_null( $timestamp ) ) {
			$params['basetimestamp'] = $timestamp;
		}

		$params['pageid'] = $revision->getPageId();
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