<?php

namespace Addwiki\Mediawiki\Api\Tests\Integration\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\Request\SimpleRequest;
use Addwiki\Mediawiki\Api\Service\NamespaceGetter;
use Addwiki\Mediawiki\DataModel\NamespaceInfo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NamespaceGetterTest extends TestCase {
	public function testGetNamespaceByCanonicalNameReturnsNullIfNamespaceWasNotFound(): void {
		$nsGetter = new NamespaceGetter( $this->getApi() );
		$this->assertNull( $nsGetter->getNamespaceByCanonicalName( 'Dummy' ) );
	}

	public function testGetNamespaceByCanonicalNameReturnsNamespaceIfNamespaceWasFound(): void {
		$nsGetter = new NamespaceGetter( $this->getApi() );
		$expectedNamespace = new NamespaceInfo( 1, 'Talk', 'Diskussion', 'first-letter' );
		$this->assertEquals( $expectedNamespace, $nsGetter->getNamespaceByCanonicalName( 'Talk' ) );
	}

	public function testGetNamespaceByNameTriesAllNames(): void {
		$nsGetter = new NamespaceGetter( $this->getApi() );
		$expectedNamespace = new NamespaceInfo( 1, 'Talk', 'Diskussion', 'first-letter' );
		$this->assertEquals( $expectedNamespace, $nsGetter->getNamespaceByName( 'Talk' ) );
		$this->assertEquals( $expectedNamespace, $nsGetter->getNamespaceByName( 'Diskussion' ) );
	}

	public function testGetNamespaceByNameTriesAliases(): void {
		$nsGetter = new NamespaceGetter( $this->getApi() );
		$expectedNamespace = new NamespaceInfo(
			3,
			'User talk',
			'Benutzer Diskussion',
			'first-letter',
			null,
			[ 'BD', 'Benutzerin Diskussion' ]
		);
		$this->assertEquals( $expectedNamespace, $nsGetter->getNamespaceByName(
			'Benutzerin Diskussion'
		) );
		$this->assertEquals( $expectedNamespace, $nsGetter->getNamespaceByName( 'BD' ) );
	}

	public function testGetNamespacesReturnsAllNamespaces(): void {
		$nsGetter = new NamespaceGetter( $this->getApi() );
		$talkNamespace = new NamespaceInfo( 1, 'Talk', 'Diskussion', 'first-letter' );
		$gadgetNamespace = new NamespaceInfo(
			2302,
			'Gadget definition',
			'Gadget-Definition',
			'case-sensitive',
			'GadgetDefinition'
		);
		$namespaces = $nsGetter->getNamespaces();
		$this->assertCount( 27, $namespaces );
		$this->assertArrayHasKey( 1, $namespaces );
		$this->assertEquals( $talkNamespace, $namespaces[1] );
		$this->assertArrayHasKey( 2302, $namespaces );
		$this->assertEquals( $gadgetNamespace, $namespaces[2302] );
	}

	/**
	 * @return MockObject|MediawikiApi
	 */
	private function getApi() {
		$api = $this->getMockBuilder( MediawikiApi::class )->disableOriginalConstructor()->getMock();
		$api
			->method( 'getRequest' )
			->with( $this->getRequest() )
			->willReturn( $this->getNamespaceFixture() );
		return $api;
	}

	private function getRequest(): SimpleRequest {
		return new SimpleRequest(
			'query', [
			'meta' => 'siteinfo',
			'siprop' => 'namespaces|namespacealiases'
		] );
	}

	private function getNamespaceFixture() {
		return json_decode( file_get_contents( __DIR__ . '/../../fixtures/namespaces.json' ), true );
	}
}
