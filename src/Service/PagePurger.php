<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\Generator\ApiGenerator;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Pages;
use Mediawiki\DataModel\Page;

/**
 * @access private
 *
 * @author Addshore
 * @author Thomas Arrow
 */
class PagePurger {

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
	 * @brief Purge a single page
	 *
	 * Purges a single page by submitting a
	 * 'purge' action to the mediawiki api
	 * with the parameter 'pageids' set to
	 * the singe page id
	 *
	 * @param Page $page the page that is going to be purged
	 *
	 * @return bool
	 */
	public function purge( Page $page ) {
		$this->api->postRequest(
			new SimpleRequest( 'purge', [ 'pageids' => $page->getId() ] )
		);

		return true;
	}

	/**
	 * @since 0.7
	 *
	 * @brief Purge multiple pages
	 *
	 * Purges all the pages of the Pages object
	 * by submitting a 'purge' action to the mediawiki
	 * api with the parameter 'pageids' set to be the
	 * pages ids in multiple-value seperation.
	 *
	 * @param Pages $pages the pages that are going to be purged
	 *
	 * @return bool
	 */
	public function purgePages( Pages $pages ) {
		$pagesArray = $pages->toArray();
		$pagesIds = [];

		foreach ( $pagesArray as $page ) {
			array_push( $pagesIds, $page->getId() );
		}

		// convert an array to multiple-value format
		// because the mediawiki api require multiple
		// values to be seperated like the example
		// ex: [111, 222, 333] => "111|222|333"
		$pageIdsMultiple = implode( "|", $pagesIds );

		$this->api->postRequest(
			new SimpleRequest( 'purge', [ 'pageids' => $pageIdsMultiple ] )
		);

		return true;
	}

	/**
	 * @since 0.6
	 *
	 * @param ApiGenerator $generator
	 *
	 * @return bool
	 */
	public function purgeGenerator( ApiGenerator $generator ) {
		$this->api->postRequest(
			new SimpleRequest( 'purge', $generator->getParams() )
		);

		return true;
	}

}
