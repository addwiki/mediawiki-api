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
	 * @return \Mediawiki\Api\Service\RevisionSaver
	 */
	public function newRevisionSaver() {
		return new RevisionSaver( $this->api );
	}

	/**
	 * @return PageRepo
	 */
	public function newPageRepo() {
		return new PageRepo( $this->api );
	}

	/**
	 * @return UserRepo
	 */
	public function newUserRepo() {
		return new UserRepo( $this->api );
	}

	/**
	 * @return PageDeleter
	 */
	public function newPageDeleter() {
		return new PageDeleter( $this->api );
	}

	/**
	 * @return PageMover
	 */
	public function newPageMover() {
		return new PageMover( $this->api );
	}

	/**
	 * @return PageListRepo
	 */
	public function newPageListRepo() {
		return new PageListRepo( $this->api );
	}

} 