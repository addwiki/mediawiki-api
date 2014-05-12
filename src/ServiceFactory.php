<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;

class ServiceFactory {

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
	 * @return PageRepo
	 */
	public function newPageRepo() {
		return new PageRepo( $this->api );
	}

	/**
	 * @since 0.3
	 * @return UserRepo
	 */
	public function newUserRepo() {
		return new UserRepo( $this->api );
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
	 * @return PageListRepo
	 */
	public function newPageListRepo() {
		return new PageListRepo( $this->api );
	}

	/**
	 * @since 0.3
	 * @return PageRestorer
	 */
	public function newPageRestorer() {
		return new PageRestorer( $this->api );
	}

} 