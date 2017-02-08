<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\NamespaceInfo;

/**
 * @access private
 *
 * @author gbirke
 */
class NamespaceGetter
{
	private $api;

	public function __construct( MediawikiApi $api ) {

		$this->api = $api;
	}

	/**
	 * @since 0.7
	 *
	 * @param string $canonicalName
	 * @return NamespaceInfo|null
	 */
	public function getNamespaceByCanonicalName( $canonicalName ) {
		foreach ( $this->getNamespaceResult()['query']['namespaces'] as $nsInfo ) {
			if ( !empty( $nsInfo['canonical'] ) && $nsInfo['canonical'] === $canonicalName ) {
				return $this->createNamespaceFromQuery( $nsInfo );
			}
		}
		return null;
	}

	/**
	 * @since 0.7
	 *
	 * @param string $name
	 * @return NamespaceInfo|null
	 */
	public function getNamespaceByName( $name ) {
		foreach ( $this->getNamespaceResult()['query']['namespaces'] as $nsInfo ) {
			if ( ( !empty( $nsInfo['canonical'] ) && $nsInfo['canonical'] === $name ) ||
				$nsInfo['*'] === $name ) {
				return $this->createNamespaceFromQuery( $nsInfo );
			}
		}
		return null;
	}

	/**
	 * @since 0.7
	 *
	 * @return NamespaceInfo[]
	 */
	public function getNamespaces() {
		$namespaces = [];
		foreach ( $this->getNamespaceResult()['query']['namespaces'] as $nsInfo ) {
			$namespaces[$nsInfo['id']] = $this->createNamespaceFromQuery( $nsInfo );
		}
		return $namespaces;
	}

	private function createNamespaceFromQuery( $nsInfo ) {
		return new NamespaceInfo(
			$nsInfo['id'],
			empty( $nsInfo['canonical'] ) ? '' : $nsInfo['canonical'],
			$nsInfo['*'],
			$nsInfo['case'],
			empty( $nsInfo['defaultcontentmodel'] ) ? null : $nsInfo['defaultcontentmodel']
		);
	}

	/**
	 * @return array
	 */
	private function getNamespaceResult() {
		return $this->api->getRequest( new SimpleRequest(
			'query', [
				'meta' => 'siteinfo',
				'siprop' => 'namespaces|namespacealiases'
			]
		) );
	}

}
