<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\ListCategoryMembersOptions;
use Mediawiki\Api\Options\ListEmbededInOptions;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\PageIdentifier;
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
	 * @param ListCategoryMembersOptions $options
	 * @returns Pages
	 */
	public function getPageListFromCategoryName( $name, ListCategoryMembersOptions $options = null ) {
		if( is_null( $options ) ) {
			$options = new ListCategoryMembersOptions();
		}

		$recursive = $options->getRecursive();
		if( $recursive ) {
			//TODO implement recursive behaviour
			throw new \BadMethodCallException( 'Not yet implemented' );
		}

		$continue = '';
		$limit = $options->getLimit();
		$pages = new Pages();

		while ( true ) {
			$params = array(
				'list' => 'categorymembers',
				'cmtitle' => $name,
			);
			if( !empty( $continue ) ) {
				$params['cmcontinue'] = $continue;
			}
			if( $limit === null ) {
				$params['cmlimit'] = 5000;
			} else {
				$params['cmlimit'] = $limit;
			}

			$result = $this->api->getAction( 'query', $params );
			$limit = $limit - count( $result[ 'query' ]['categorymembers'] );

			foreach ( $result['query']['categorymembers'] as $member ) {
				$pages->addPage( new Page(
						new PageIdentifier(
							new Title( $member['title'], $member['ns'] ),
							$member['pageid']
						),
						new Revisions()
					)
				);
			}

			if( $limit !== null && $limit <= 0 ) {
				return $pages;
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
	 * @param ListEmbededInOptions $options
	 *
	 * @return Pages
	 */
	public function getPageListFromPageTransclusions( $pageName, ListEmbededInOptions $options = null ) {
		if( is_null( $options ) ) {
			$options = new ListEmbededInOptions();
		}

		$continue = '';
		$limit = $options->getLimit();
		$pages = new Pages();

		while ( true ) {
			$params = array(
				'list' => 'embeddedin',
				'eititle' => $pageName,
				'einamespace' => implode( '|', $options->getNamespaces() )
			);
			if( !empty( $continue ) ) {
				$params['eicontinue'] = $continue;
			}
			if( $limit === null ) {
				$params['eilimit'] = 5000;
			} else {
				$params['eilimit'] = $limit;
			}
			$result = $this->api->getAction( 'query', $params );
			$limit = $limit - count( $result[ 'query' ]['embeddedin'] );

			foreach ( $result['query']['embeddedin'] as $member ) {
				$pages->addPage( new Page(
						new PageIdentifier(
							new Title( $member['title'], $member['ns'] ),
							$member['pageid']
						),
						new Revisions()
					)
				);
			}

			if( $limit !== null && $limit <= 0 ) {
				return $pages;
			}
			if ( empty( $result['query-continue']['embeddedin']['eicontinue'] ) ) {
				return $pages;
			} else {
				$continue = $result['query-continue']['embeddedin']['eicontinue'];
			}
		}
	}

}
