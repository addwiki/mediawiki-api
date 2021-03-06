<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\NamespaceInfo;

/**
 * @access private
 */
class NamespaceGetter extends Service {

	/**
	 * Find a namespace by its canonical name
	 *
	 * @return mixed|null
	 */
	public function getNamespaceByCanonicalName( string $canonicalName ) {
		$result = $this->getNamespaceResult()['query'];
		foreach ( $result['namespaces'] as $nsInfo ) {
			if ( !empty( $nsInfo['canonical'] ) && $nsInfo['canonical'] === $canonicalName ) {
				return $this->createNamespaceFromQuery( $nsInfo, $result['namespacealiases'] );
			}
		}
		return null;
	}

	/**
	 * Find a namespace by its canonical name, local name or namespace alias
	 *
	 * @return mixed|null
	 */
	public function getNamespaceByName( string $name ) {
		$result = $this->getNamespaceResult()['query'];
		foreach ( $result['namespaces'] as $nsInfo ) {
			if ( ( !empty( $nsInfo['canonical'] ) && $nsInfo['canonical'] === $name ) ||
				$nsInfo['*'] === $name ) {
				return $this->createNamespaceFromQuery( $nsInfo, $result['namespacealiases'] );
			}
		}
		foreach ( $result['namespacealiases'] as $alias ) {
			if ( $alias['*'] === $name && !empty( $result['namespaces'][$alias['id']] ) ) {
				return $this->createNamespaceFromQuery(
					$result['namespaces'][$alias['id']],
					$result['namespacealiases']
				);
			}
		}
		return null;
	}

	/**
	 * @return NamespaceInfo[]
	 */
	public function getNamespaces(): array {
		$namespaces = [];
		$result = $this->getNamespaceResult()['query'];
		foreach ( $result['namespaces'] as $nsInfo ) {
			$namespaces[$nsInfo['id']] = $this->createNamespaceFromQuery(
				$nsInfo, $result['namespacealiases']
			);
		}
		return $namespaces;
	}

	private function createNamespaceFromQuery( $nsInfo, $namespaceAliases ): NamespaceInfo {
		return new NamespaceInfo(
			$nsInfo['id'],
			empty( $nsInfo['canonical'] ) ? '' : $nsInfo['canonical'],
			$nsInfo['*'],
			$nsInfo['case'],
			empty( $nsInfo['defaultcontentmodel'] ) ? null : $nsInfo['defaultcontentmodel'],
			$this->getAliases( $nsInfo['id'], $namespaceAliases )
		);
	}

	/**
	 * @param array $namespaceAliases Alias list, as returned by the API
	 * @return string[]
	 */
	private function getAliases( int $id, array $namespaceAliases ): array {
		$aliases = [];
		foreach ( $namespaceAliases as $alias ) {
			if ( $alias['id'] === $id ) {
				$aliases[] = $alias['*'];
			}
		}
		return $aliases;
	}

	/**
	 * @return mixed[]
	 */
	private function getNamespaceResult(): array {
		return $this->api->request( ActionRequest::simpleGet(
			'query', [
				'meta' => 'siteinfo',
				'siprop' => 'namespaces|namespacealiases'
			]
		) );
	}

}
