<?php

namespace Mediawiki\Api;

use Mediawiki\Api\Service\PageDeleter;
use Mediawiki\Api\Service\PageListGetter;
use Mediawiki\Api\Service\PageMover;
use Mediawiki\Api\Service\PagePurger;
use Mediawiki\Api\Service\PageGetter;
use Mediawiki\Api\Service\PageRestorer;
use Mediawiki\Api\Service\RevisionRollbacker;
use Mediawiki\Api\Service\RevisionSaver;
use Mediawiki\Api\Service\UserGetter;

/**
 * @author Adam Shorland
 */
class MediawikiFactory {

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
	 * @return RevisionSaver
	 */
	public function newRevisionSaver() {
		return new RevisionSaver( $this->api );
	}

	/**
	 * @since 0.3
	 * @return PageGetter
	 */
	public function newPageGetter() {
		return new PageGetter( $this->api );
	}

	/**
	 * @since 0.3
	 * @return UserGetter
	 */
	public function newUserGetter() {
		return new UserGetter( $this->api );
	}

	/**
	 * @since 0.3
	 * @return PageDeleter
	 */
	public function newPageDeleter() {
		return new PageDeleter( $this->api );
	}

	/**
	 * @since 0.3
	 * @return PageMover
	 */
	public function newPageMover() {
		return new PageMover( $this->api );
	}

	/**
	 * @since 0.3
	 * @return PageListGetter
	 */
	public function newPageListGetter() {
		return new PageListGetter( $this->api );
	}

	/**
	 * @since 0.3
	 * @return PageRestorer
	 */
	public function newPageRestorer() {
		return new PageRestorer( $this->api );
	}

	/**
	 * @since 0.3
	 * @return PagePurger
	 */
	public function newPagePurger() {
		return new PagePurger( $this->api );
	}

	/**
	 * @since 0.3
	 * @return RevisionRollbacker
	 */
	public function newRevisionRollbacker() {
		return new RevisionRollbacker( $this->api );
	}

} 