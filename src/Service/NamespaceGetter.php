<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\NamespaceInfo;

/**
 * @author gbirke
 */
class NamespaceGetter
{
	private $api;

	public function __construct( MediawikiApi $api ) {

		$this->api = $api;
	}

	/**
	 * Find a namespace by its canonical name
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
	 * Find a namespace by its canonical name, local name or namespace alias
	 *
	 * @param string $name
	 * @return NamespaceInfo|null
	 */
	public function getNamespaceByName( $name ) {
		$result = $this->getNamespaceResult()['query'];
		foreach ( $result['namespaces'] as $nsInfo ) {
			if ( ( !empty( $nsInfo['canonical'] ) && $nsInfo['canonical'] === $name ) ||
				$nsInfo['*'] === $name ) {
				return $this->createNamespaceFromQuery( $nsInfo );
			}
		}
		foreach ( $result['namespacealiases'] as $alias ) {
			if ( $alias['*'] === $name && !empty( $result['namespaces'][$alias['id']] ) ) {
				return $this->createNamespaceFromQuery( $result['namespaces'][$alias['id']] );
			}
		}
		return null;
	}

	/**
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
