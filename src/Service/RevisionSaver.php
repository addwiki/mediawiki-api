<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\Revision;
use RuntimeException;

/**
 * @access private
 *
 * @author Addshore
 * @author DFelten (EditInfo fix)
 */
class RevisionSaver extends Service {

	/**
	 * @since 0.2
	 *
	 * @param Revision $revision
	 * @param EditInfo $editInfo
	 *
	 * @return bool success
	 */
	public function save( Revision $revision, EditInfo $editInfo = null ) {
		$editInfo = $editInfo ? $editInfo : $revision->getEditInfo();

		$result = $this->api->postRequest(
			new SimpleRequest( 'edit', $this->getEditParams( $revision, $editInfo ) )
		);
		return ( $result['edit']['result'] == 'Success' );
	}

	/**
	 * @param Revision $revision
	 * @param EditInfo $editInfo
	 *
	 * @throws RuntimeException
	 * @returns array
	 */
	private function getEditParams( Revision $revision, EditInfo $editInfo = null ) {
		if ( !$revision->getPageIdentifier()->identifiesPage() ) {
			throw new RuntimeException( '$revision PageIdentifier does not identify a page' );
		}

		$params = [];

		$content = $revision->getContent();
		$data = $content->getData();
		if ( !is_string( $data ) ) {
			throw new RuntimeException( 'Dont know how to save content of this model.' );
		}
		$params['text'] = $content->getData();
		$params['md5'] = md5( $content->getData() );

		$timestamp = $revision->getTimestamp();
		if ( !is_null( $timestamp ) ) {
			$params['basetimestamp'] = $timestamp;
		}

		if ( !is_null( $revision->getPageIdentifier()->getId() ) ) {
			$params['pageid'] = $revision->getPageIdentifier()->getId();
		} else {
			$params['title'] = $revision->getPageIdentifier()->getTitle()->getTitle();
		}

		$params['token'] = $this->api->getToken();

		if ( $this->api->isLoggedin() ) {
			$params['assert'] = 'user';
		}

		$this->addEditInfoParams( $editInfo, $params );

		return $params;
	}

	/**
	 * @param null|EditInfo $editInfo
	 * @param array &$params
	 */
	private function addEditInfoParams( $editInfo, &$params ) {
		if ( !is_null( $editInfo ) ) {
			$params['summary'] = $editInfo->getSummary();
			if ( $editInfo->getMinor() ) {
				$params['minor'] = true;
			}
			if ( $editInfo->getBot() ) {
				$params['bot'] = true;
				$params['assert'] = 'bot';
			}
		}
	}

}
