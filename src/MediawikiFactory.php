<?php

namespace Mediawiki\Api;

use Mediawiki\Api\Service\LogListGetter;
use Mediawiki\Api\Service\PageDeleter;
use Mediawiki\Api\Service\PageListGetter;
use Mediawiki\Api\Service\PageMover;
use Mediawiki\Api\Service\PageProtector;
use Mediawiki\Api\Service\PagePurger;
use Mediawiki\Api\Service\PageGetter;
use Mediawiki\Api\Service\PageRestorer;
use Mediawiki\Api\Service\RevisionDeleter;
use Mediawiki\Api\Service\RevisionPatroller;
use Mediawiki\Api\Service\RevisionRestorer;
use Mediawiki\Api\Service\RevisionRollbacker;
use Mediawiki\Api\Service\RevisionSaver;
use Mediawiki\Api\Service\RevisionUndoer;
use Mediawiki\Api\Service\UserBlocker;
use Mediawiki\Api\Service\UserGetter;
use Mediawiki\Api\Service\UserRightsChanger;

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
	 * @since 0.5
	 * @return RevisionUndoer
	 */
	public function newRevisionUndoer() {
		return new RevisionUndoer( $this->api );
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

	/**
	 * @since 0.3
	 * @return RevisionPatroller
	 */
	public function newRevisionPatroller() {
		return new RevisionPatroller( $this->api );
	}

	/**
	 * @since 0.3
	 * @return PageProtector
	 */
	public function newPageProtector() {
		return new PageProtector( $this->api );
	}

	/**
	 * @since 0.3
	 * @return RevisionDeleter
	 */
	public function newRevisionDeleter() {
		return new RevisionDeleter( $this->api );
	}

	/**
	 * @since 0.3
	 * @return RevisionRestorer
	 */
	public function newRevisionRestorer() {
		return new RevisionRestorer( $this->api );
	}

	/**
	 * @since 0.3
	 * @return UserBlocker
	 */
	public function newUserBlocker() {
		return new UserBlocker( $this->api );
	}

	/**
	 * @since 0.3
	 * @return UserRightsChanger
	 */
	public function newUserRightsChanger() {
		return new UserRightsChanger( $this->api );
	}

	/**
	 * @since 0.4
	 * @return LogListGetter
	 */
	public function newLogListGetter() {
		return new LogListGetter( $this->api );
	}
} 