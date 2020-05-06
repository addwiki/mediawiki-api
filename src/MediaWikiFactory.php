<?php

namespace Addwiki\MediaWikiApi;

use Addwiki\MediaWikiApi\Service\CategoryTraverser;
use Addwiki\MediaWikiApi\Service\FileUploader;
use Addwiki\MediaWikiApi\Service\ImageRotator;
use Addwiki\MediaWikiApi\Service\LogListGetter;
use Addwiki\MediaWikiApi\Service\NamespaceGetter;
use Addwiki\MediaWikiApi\Service\PageDeleter;
use Addwiki\MediaWikiApi\Service\PageListGetter;
use Addwiki\MediaWikiApi\Service\PageMover;
use Addwiki\MediaWikiApi\Service\PageProtector;
use Addwiki\MediaWikiApi\Service\PagePurger;
use Addwiki\MediaWikiApi\Service\PageGetter;
use Addwiki\MediaWikiApi\Service\PageRestorer;
use Addwiki\MediaWikiApi\Service\PageWatcher;
use Addwiki\MediaWikiApi\Service\Parser;
use Addwiki\MediaWikiApi\Service\RevisionDeleter;
use Addwiki\MediaWikiApi\Service\RevisionPatroller;
use Addwiki\MediaWikiApi\Service\RevisionRestorer;
use Addwiki\MediaWikiApi\Service\RevisionRollbacker;
use Addwiki\MediaWikiApi\Service\RevisionSaver;
use Addwiki\MediaWikiApi\Service\RevisionUndoer;
use Addwiki\MediaWikiApi\Service\UserBlocker;
use Addwiki\MediaWikiApi\Service\UserCreator;
use Addwiki\MediaWikiApi\Service\UserGetter;
use Addwiki\MediaWikiApi\Service\UserRightsChanger;
use Mediawiki\Api\MediawikiApi;

/**
 * @access public
 *
 * @author Addshore
 */
class MediaWikiFactory {

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
	 * Get a new CategoryTraverser object for this API.
	 * @return CategoryTraverser
	 */
	public function newCategoryTraverser() {
		return new CategoryTraverser( $this->api );
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
	 * @since 0.5
	 * @return PageWatcher
	 */
	public function newPageWatcher() {
		return new PageWatcher( $this->api );
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
	 * @since 0.5
	 * @return UserCreator
	 */
	public function newUserCreator() {
		return new UserCreator( $this->api );
	}

	/**
	 * @since 0.4
	 * @return LogListGetter
	 */
	public function newLogListGetter() {
		return new LogListGetter( $this->api );
	}

	/**
	 * @since 0.5
	 * @return FileUploader
	 */
	public function newFileUploader() {
		return new FileUploader( $this->api );
	}

	/**
	 * @since 0.5
	 * @return ImageRotator
	 */
	public function newImageRotator() {
		return new ImageRotator( $this->api );
	}

	/**
	 * @since 0.6
	 * @return Parser
	 */
	public function newParser() {
		return new Parser( $this->api );
	}

	/**
	 * @since 0.7
	 * @return NamespaceGetter
	 */
	public function newNamespaceGetter() {
		return new NamespaceGetter( $this->api );
	}
}
