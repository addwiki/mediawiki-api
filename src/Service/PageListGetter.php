<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Pages;
use Mediawiki\DataModel\Revisions;
use Mediawiki\DataModel\Title;

/**
 * @author Adam Shorland
 */
class PageListGetter {

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
	 * @param string $name
	 * @param bool|int $recursive layers of recursion to do
	 * @returns Pages
	 */
	public function getPageListFromCategoryName( $name, $recursive = false ) {
		if( $recursive ) {
			//TODO implement recursive behaviour
			throw new \BadMethodCallException( 'Not yet implemented' );
		}

		$continue = '';
		$pages = new Pages();

		while ( true ) {
			$params = array(
				'list' => 'categorymembers',
				'cmtitle' => $name,
				'cmlimit' => 500,
			);
			if( !empty( $continue ) ) {
				$params['cmcontinue'] = $continue;
			}
			$result = $this->api->getAction( 'query', $params );

			foreach ( $result['query']['categorymembers'] as $member ) {
				$pages->addPage( new Page(
						new Title( $member['title'], $member['ns'] ),
						$member['pageid'],
						new Revisions()
					)
				);
			}
			if ( empty( $result['query-continue']['categorymembers']['cmcontinue'] ) ) {
				if ( $recursive ) {
					//TODO implement recursive behaviour
				}
				return $pages;
			} else {
				$continue = $result['query-continue']['categorymembers']['cmcontinue'];
			}
		}
	}

	/**
	 * @param string $pageName
	 * @param array $namespaces
	 *
	 * @return Pages
	 */
	public function getPageListFromPageTransclusions( $pageName, $namespaces= array() ) {
		$continue = '';
		$pages = new Pages();

		while ( true ) {
			$params = array(
				'list' => 'embeddedin',
				'eititle' => $pageName,
				'eilimit' => 500,
				'einamespace' => implode( '|', $namespaces )
			);
			if( !empty( $continue ) ) {
				$params['eicontinue'] = $continue;
			}
			$result = $this->api->getAction( 'query', $params );

			foreach ( $result['query']['embeddedin'] as $member ) {
				$pages->addPage( new Page(
						new Title( $member['title'], $member['ns'] ),
						$member['pageid'],
						new Revisions()
					)
				);
			}
			if ( empty( $result['query-continue']['embeddedin']['eicontinue'] ) ) {
				return $pages;
			} else {
				$continue = $result['query-continue']['embeddedin']['eicontinue'];
			}
		}
	}

}
