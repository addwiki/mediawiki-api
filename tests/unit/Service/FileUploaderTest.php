<?php

namespace Addwiki\Mediawiki\Api\Tests\Unit\Service;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Tokens;
use Addwiki\Mediawiki\Api\Client\Auth\NoAuth;
use Addwiki\Mediawiki\Api\Service\FileUploader;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class FileUploaderTest extends TestCase {

	/**
	 * @return MockObject&ResponseInterface
	 */
	private function getMockResponse( $responseValue ) {
		$mock = $this->createMock( ResponseInterface::class );
		$mock
			->method( 'getBody' )
			->willReturn( json_encode( $responseValue ) );
		return $mock;
	}

	public function testUpload(): void {
		$testPagename = uniqid( 'file-uploader-test-' ) . '.png';
		$testFilename = dirname( __DIR__, 2 ) . '/fixtures/blue ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ.png';

		$expectedMultipartParts = [
			[ 'name' => 'action', 'contents' => 'upload' ],
			[ 'name' => 'filename', 'contents' => 'CUSTOM-ASSERT-BELOW' ],
			[ 'name' => 'token', 'contents' => 'someToken' ],
			[ 'name' => 'ignorewarnings', 'contents' => '1' ],
			[ 'name' => 'text', 'contents' => 'Testing' ],
			[ 'name' => 'filesize', 'contents' => 2807 ],
			[ 'name' => 'file', 'contents' => 'CUSTOM-ASSERT-BELOW' ],
			[ 'name' => 'format', 'contents' => 'json' ],
		];

		$client = $this->createMock( ClientInterface::class );
		$client->method( 'request' )
		->willReturnCallback( function ( $method, $url, $options ) use ( $expectedMultipartParts ) {
			$this->assertSame( 'POST', $method );
			$this->assertSame( 'someUrl', $url );
			$this->assertArrayHasKey( 'headers', $options );
			$this->assertArrayHasKey( 'User-Agent', $options['headers'] );
			$this->assertArrayHasKey( 'multipart', $options );
			$foundParts = 0;
			foreach ( $expectedMultipartParts as $expectedPart ) {
				foreach ( $options['multipart'] as $actualPart ) {
					if ( !in_array( $expectedPart['name'], [ 'filename', 'file' ] ) && $expectedPart === $actualPart ) {
						++$foundParts;
					}

					if ( $expectedPart['name'] === 'filename' && $expectedPart['name'] == $actualPart['name'] ) {
						$this->assertIsString( $actualPart['contents'] );
						++$foundParts;
					}

					if ( $expectedPart['name'] === 'file' && $expectedPart['name'] == $actualPart['name'] ) {
						$this->assertTrue( is_resource( $actualPart['contents'] ) );
						++$foundParts;
					}
				}
			}

			$this->assertSame( count( $expectedMultipartParts ), $foundParts );

			return $this->getMockResponse( [ 'upload' => [ 'result' => 'Success' ] ] );
		} );

		$session = $this->createMock( Tokens::class );
		$session->method( 'get' )->willReturn( 'someToken' );

		$api = new ActionApi( 'someUrl', new NoAuth(), $client, $session );
		$service = new FileUploader( $api );

		// Upload a file.
		$uploaded = $service->upload( $testPagename, $testFilename, 'Testing', '', null, true );
		$this->assertTrue( $uploaded );
	}

	public function testUploadByChunks(): void {
		$testPagename = uniqid( 'file-uploader-test-' ) . '.png';
		$testFilename = dirname( __DIR__, 2 ) . '/fixtures/blue ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ.png';

		$expectedMultipartParts = [
			1 => [
				[ 'name' => 'filename', 'contents' => 'CUSTOM-ASSERT-BELOW' ],
				[ 'name' => 'token', 'contents' => 'someToken' ],
				[ 'name' => 'ignorewarnings', 'contents' => '1' ],
				[ 'name' => 'text', 'contents' => 'Testing' ],
				[ 'name' => 'filesize', 'contents' => 2807 ],
				[ 'name' => 'offset', 'contents' => 0 ],
				[ 'name' => 'chunk', 'contents' => 'CUSTOM-ASSERT-BELOW', 'headers' => [], ],
				[ 'name' => 'action', 'contents' => 'upload' ],
				[ 'name' => 'format', 'contents' => 'json' ],
				[ 'name' => 'assert', 'contents' => 'anon' ],
			],
			2 => [
				[ 'name' => 'action', 'contents' => 'upload' ],
				[ 'name' => 'filename', 'contents' => 'CUSTOM-ASSERT-BELOW' ],
				[ 'name' => 'token', 'contents' => 'someToken' ],
				[ 'name' => 'ignorewarnings', 'contents' => '1' ],
				[ 'name' => 'text', 'contents' => 'Testing' ],
				[ 'name' => 'filesize', 'contents' => 2807 ],
				[ 'name' => 'filekey', 'contents' => 'someKey' ],
				[ 'name' => 'format', 'contents' => 'json' ],
				[ 'name' => 'assert', 'contents' => 'anon' ],
			]
		];
		$callbackCounter = 0;

		$client = $this->createMock( ClientInterface::class );
		$client->method( 'request' )
		->willReturnCallback( function ( $method, $url, $options ) use ( $expectedMultipartParts, &$callbackCounter ) {
			++$callbackCounter;
			$this->assertSame( 'POST', $method );
			$this->assertSame( 'someUrl', $url );
			$this->assertArrayHasKey( 'headers', $options );
			$this->assertArrayHasKey( 'User-Agent', $options['headers'] );
			$this->assertArrayHasKey( 'multipart', $options );

			$expectedByName = $this->getMultiPartByName( $expectedMultipartParts[$callbackCounter] );
			$actualByName = $this->getMultiPartByName( $options['multipart'] );

			$this->assertSame( array_keys( $expectedByName ), array_keys( $actualByName ) );

			$foundParts = 0;
			foreach ( $expectedMultipartParts[$callbackCounter] as $expectedPart ) {
				foreach ( $options['multipart'] as $actualPart ) {
					if ( !in_array( $expectedPart['name'], [ 'filename', 'chunk' ] ) && $expectedPart === $actualPart ) {
						++$foundParts;
					}

					if ( $expectedPart['name'] === 'filename' && $expectedPart['name'] == $actualPart['name'] ) {
						$this->assertIsString( $actualPart['contents'] );
						++$foundParts;
					}

					if ( $expectedPart['name'] === 'chunk' && $expectedPart['name'] == $actualPart['name'] ) {
						$this->assertStringContainsString(
							'form-data; name="chunk"; filename="file-uploader-test-',
							$actualPart['headers']['Content-Disposition']
						);
						$this->assertIsString( $actualPart['contents'] );
						++$foundParts;
					}
				}
			}

			$this->assertSame( count( $expectedMultipartParts[$callbackCounter] ), $foundParts );

			return $this->getMockResponse( [ 'upload' => [ 'result' => 'Success', 'filekey' => 'someKey' ] ] );
		} );

		$session = $this->createMock( Tokens::class );
		$session->method( 'get' )->willReturn( 'someToken' );

		$api = new ActionApi( 'someUrl', new NoAuth(), $client, $session );
		$service = new FileUploader( $api );

		// Upload a file.
		$service->setChunkSize( 1024 * 10 );

		$uploaded = $service->upload( $testPagename, $testFilename, 'Testing', null, null, true );
		$this->assertTrue( $uploaded );
	}

	private function getMultiPartByName( array $multiPartOptions ): array {
		$multipartByName = [];
		foreach ( $multiPartOptions as $actualPart ) {
			$multipartByName[ $actualPart['name'] ] = $actualPart;
		}

		return $multipartByName;
	}

}
