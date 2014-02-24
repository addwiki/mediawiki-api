<?php

namespace Mediawiki\Api;

use Mediawiki\Api\Service\PageRepo;
use Mediawiki\Api\Service\RevisionSaver;
use Mediawiki\Api\Service\UserRepo;

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

} 