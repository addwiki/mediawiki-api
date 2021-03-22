<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\Api\Generator\ApiGenerator;
use Addwiki\Mediawiki\DataModel\Page;
use Addwiki\Mediawiki\DataModel\Pages;

/**
 * @access private
 */
class PagePurger extends Service {

	/**
	 * Purge a single page
	 *
	 * Purges a single page by submitting a
	 * 'purge' action to the mediawiki api
	 * with the parameter 'pageids' set to
	 * the singe page id
	 *
	 * @param Page $page the page that is going to be purged
	 *
	 * @return bool return true if the purge was successful
	 */
	public function purge( Page $page ): bool {
		if ( $page->getPageIdentifier()->getId() ) {
			$params = [ 'pageids' => $page->getPageIdentifier()->getId() ];
		} else {
			$params = [ 'titles' => $page->getPageIdentifier()->getTitle()->getText() ];
		}

		$responseArray = $this->api->request(
			ActionRequest::simplePost( 'purge', $params )
		);

		// the purge response for the page
		$purgeResponse = $responseArray['purge'][0];

		return array_key_exists( 'purged', $purgeResponse );
	}

	/**
	 * Purge multiple pages
	 *
	 * Purges all the pages of the Pages object
	 * by submitting a 'purge' action to the mediawiki
	 * api with the parameter 'pageids' set to be the
	 * pages ids in multiple-value seperation.
	 *
	 * @param Pages $pages the pages that are going to be purged
	 *
	 * @return Pages the pages that have been purged successfully
	 */
	public function purgePages( Pages $pages ): Pages {
		$pagesArray = $pages->toArray();
		$pagesIds = [];

		foreach ( $pagesArray as $page ) {
			$pagesIds[] = $page->getId();
		}

		// convert an array to multiple-value format
		// because the mediawiki api require multiple
		// values to be seperated like the example
		// ex: [111, 222, 333] => "111|222|333"
		$pageIdsMultiple = implode( '|', $pagesIds );

		$responseArray = $this->api->request(
			ActionRequest::simplePost( 'purge', [ 'pageids' => $pageIdsMultiple ] )
		);

		// array that will hold the successfully purged pages
		$purgedPages = new Pages();

		// for every purge result
		foreach ( $responseArray['purge'] as $purgeResponse ) {
			// if the purge for the page was successful
			if ( array_key_exists( 'purged', $purgeResponse ) ) {
				// we iterate all the input pages
				foreach ( $pagesArray as $page ) {
					// and if the page from the input was successfully purged
					if ( $purgeResponse['title'] === $page->getTitle()->getText() ) {
						// add it in the purgedPages object
						$purgedPages->addPage( $page );

						break;
					}

				}

			}

		}

		return $purgedPages;
	}

	public function purgeGenerator( ApiGenerator $generator ): bool {
		$this->api->request(
			ActionRequest::simplePost( 'purge', $generator->getParams() )
		);

		return true;
	}

}
