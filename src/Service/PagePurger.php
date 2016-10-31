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
	 * @param Page $page
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
	 * @since 0.x
	 *
	 * @param Pages $pages
	 *
	 * @return bool
	 *
	 */
	public function purgePages( Pages $pages ) {
		$pagesArray = $pages->toArray();

		foreach ( $pagesArray as $page ) {
			$this->purge( $page );
		}

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
