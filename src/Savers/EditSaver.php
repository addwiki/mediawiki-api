<?php

namespace Mediawiki\Api\Savers;

use Mediawiki\Api\Actions\Edit;
use Mediawiki\Api\MediawikiApi;
use RuntimeException;

class EditSaver {

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
	 * @param \Mediawiki\Api\Actions\Edit $edit
	 *
	 * @returns bool success
	 */
	public function save( Edit $edit ) {
		$result = $this->api->postAction( 'edit', $this->getEditParams( $edit ) );
		if( $result['edit']['result'] == 'Success' ) {
			return true;
		}
		return false;
	}

	/**
	 * @param \Mediawiki\Api\Actions\Edit $edit
	 *
	 * @throws RuntimeException
	 * @returns array
	 */
	private function getEditParams( Edit $edit ) {
		$params = array();
		$assertions = array();
		$params['text'] = $edit->getText();
		$params['md5'] = md5( $edit->getText() );
		$params['basetimestamp'] = $edit->getBasetimestamp();
		$params['pageid'] = $edit->getPageid();
		$params['token'] = $this->api->getToken();
		$params['summary'] = $edit->getEditFlags()->getSummary();
		if( $edit->getEditFlags()->getMinor() ) {
			$params['minor'] = true;
		}
		if( $edit->getEditFlags()->getBot() ) {
			$params['bot'] = true;
			$assertions[] = 'bot';
		}
		if( $this->api->isLoggedin() ) {
			$assertions[] = 'user';
		}
		$params['assert'] = implode( '|', $assertions );
		return $params;
	}

} 