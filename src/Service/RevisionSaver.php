<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Mediawiki\DataModel\Revision;
use RuntimeException;

/**
 * @access private
 */
class RevisionSaver extends Service {

	/**
	 * @param EditInfo|null $editInfo
	 * @return bool success
	 */
	public function save( Revision $revision, EditInfo $editInfo = null ): bool {
		$editInfo ??= $revision->getEditInfo();

		$result = $this->api->request(
			ActionRequest::simplePost( 'edit', $this->getEditParams( $revision, $editInfo ) )
		);
		return ( $result['edit']['result'] == 'Success' );
	}

	/**
	 * @param EditInfo|null $editInfo
	 *
	 * @throws RuntimeException
	 * @return mixed[]
	 */
	private function getEditParams( Revision $revision, EditInfo $editInfo = null ): array {
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
		if ( $timestamp !== null ) {
			$params['basetimestamp'] = $timestamp;
		}

		if ( $revision->getPageIdentifier()->getId() !== null ) {
			$params['pageid'] = $revision->getPageIdentifier()->getId();
		} else {
			$params['title'] = $revision->getPageIdentifier()->getTitle()->getTitle();
		}

		$params['token'] = $this->api->getToken();

		$this->addEditInfoParams( $editInfo, $params );

		return $params;
	}

	private function addEditInfoParams( ?EditInfo $editInfo, array &$params ): void {
		if ( $editInfo !== null ) {
			$params['summary'] = $editInfo->getSummary();
			if ( $editInfo->getMinor() ) {
				$params['minor'] = true;
			}
			if ( $editInfo->getBot() ) {
				$params['bot'] = true;
				$params['assert'] = 'bot';
			}
			if ( $editInfo->getMaxlag() ) {
				$params['maxlag'] = $editInfo->getMaxlag();
			}
		}
	}

}
