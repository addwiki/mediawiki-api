<?php

namespace Addwiki\Mediawiki\Api;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Service\CategoryTraverser;
use Addwiki\Mediawiki\Api\Service\FileUploader;
use Addwiki\Mediawiki\Api\Service\ImageRotator;
use Addwiki\Mediawiki\Api\Service\LogListGetter;
use Addwiki\Mediawiki\Api\Service\NamespaceGetter;
use Addwiki\Mediawiki\Api\Service\PageDeleter;
use Addwiki\Mediawiki\Api\Service\PageGetter;
use Addwiki\Mediawiki\Api\Service\PageListGetter;
use Addwiki\Mediawiki\Api\Service\PageMover;
use Addwiki\Mediawiki\Api\Service\PageProtector;
use Addwiki\Mediawiki\Api\Service\PagePurger;
use Addwiki\Mediawiki\Api\Service\PageRestorer;
use Addwiki\Mediawiki\Api\Service\PageWatcher;
use Addwiki\Mediawiki\Api\Service\Parser;
use Addwiki\Mediawiki\Api\Service\RevisionDeleter;
use Addwiki\Mediawiki\Api\Service\RevisionPatroller;
use Addwiki\Mediawiki\Api\Service\RevisionRestorer;
use Addwiki\Mediawiki\Api\Service\RevisionRollbacker;
use Addwiki\Mediawiki\Api\Service\RevisionSaver;
use Addwiki\Mediawiki\Api\Service\RevisionUndoer;
use Addwiki\Mediawiki\Api\Service\UserBlocker;
use Addwiki\Mediawiki\Api\Service\UserCreator;
use Addwiki\Mediawiki\Api\Service\UserGetter;
use Addwiki\Mediawiki\Api\Service\UserRightsChanger;

/**
 * @access public
 */
class MediawikiFactory {

	private ActionApi $api;

	public function __construct( ActionApi $api ) {
		$this->api = $api;
	}

	/**
	 * Get a new CategoryTraverser object for this API.
	 */
	public function newCategoryTraverser(): CategoryTraverser {
		return new CategoryTraverser( $this->api );
	}

	public function newRevisionSaver(): RevisionSaver {
		return new RevisionSaver( $this->api );
	}

	public function newRevisionUndoer(): RevisionUndoer {
		return new RevisionUndoer( $this->api );
	}

	public function newPageGetter(): PageGetter {
		return new PageGetter( $this->api );
	}

	public function newUserGetter(): UserGetter {
		return new UserGetter( $this->api );
	}

	public function newPageDeleter(): PageDeleter {
		return new PageDeleter( $this->api );
	}

	public function newPageMover(): PageMover {
		return new PageMover( $this->api );
	}

	public function newPageListGetter(): PageListGetter {
		return new PageListGetter( $this->api );
	}

	public function newPageRestorer(): PageRestorer {
		return new PageRestorer( $this->api );
	}

	public function newPagePurger(): PagePurger {
		return new PagePurger( $this->api );
	}

	public function newRevisionRollbacker(): RevisionRollbacker {
		return new RevisionRollbacker( $this->api );
	}

	public function newRevisionPatroller(): RevisionPatroller {
		return new RevisionPatroller( $this->api );
	}

	public function newPageProtector(): PageProtector {
		return new PageProtector( $this->api );
	}

	public function newPageWatcher(): PageWatcher {
		return new PageWatcher( $this->api );
	}

	public function newRevisionDeleter(): RevisionDeleter {
		return new RevisionDeleter( $this->api );
	}

	public function newRevisionRestorer(): RevisionRestorer {
		return new RevisionRestorer( $this->api );
	}

	public function newUserBlocker(): UserBlocker {
		return new UserBlocker( $this->api );
	}

	public function newUserRightsChanger(): UserRightsChanger {
		return new UserRightsChanger( $this->api );
	}

	public function newUserCreator(): UserCreator {
		return new UserCreator( $this->api );
	}

	public function newLogListGetter(): LogListGetter {
		return new LogListGetter( $this->api );
	}

	public function newFileUploader(): FileUploader {
		return new FileUploader( $this->api );
	}

	public function newImageRotator(): ImageRotator {
		return new ImageRotator( $this->api );
	}

	public function newParser(): Parser {
		return new Parser( $this->api );
	}

	public function newNamespaceGetter(): NamespaceGetter {
		return new NamespaceGetter( $this->api );
	}
}
