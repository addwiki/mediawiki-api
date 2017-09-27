<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\ApiUser;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\MediawikiFactory;
use Mediawiki\Api\Service\FileUploader;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Title;
use PHPUnit_Framework_TestCase;

/**
 * Test the \Mediawiki\Api\Service\FileUploader class.
 */
class FileUploaderTest extends PHPUnit_Framework_TestCase {

	/** @var MediawikiFactory */
	protected $factory;

	/** @var FileUploader */
	protected $fileUploader;

	/**
	 * Create a FileUploader to use in all these tests.
	 */
	public function setup() {
		parent::setup();
		$testEnvironment = TestEnvironment::newDefault();
		$this->factory = $testEnvironment->getFactory();
		$this->fileUploader = $this->factory->newFileUploader();

		// Log in as the sysop user. These credentials are referenced in docs/contributing.rst.
		$localApiUser = new ApiUser( 'admin', 'admin123' );
		$api = $testEnvironment->getApi();
		$api->login( $localApiUser );
	}

	public function testUpload() {
		$testPagename = uniqid( 'file-uploader-test-' ) . '.png';
		$testTitle = new Title( 'File:'.$testPagename );

		// Check that the file doesn't exist yet.
		$testFile = $this->factory->newPageGetter()->getFromTitle( $testTitle );
		$this->assertEquals( 0, $testFile->getPageIdentifier()->getId() );

		// Upload a file.
		$testFilename = dirname( __DIR__ ) . '/fixtures/blue ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ.png';
		$uploaded = $this->fileUploader->upload( $testPagename, $testFilename, 'Testing',
			null, null, true );
		$this->assertTrue( $uploaded );

		// Get the file again, and check that it exists this time.
		$testFile2 = $this->factory->newPageGetter()->getFromTitle( $testTitle );
		$this->assertGreaterThan( 0, $testFile2->getPageIdentifier()->getId() );
	}

	public function testUploadByChunks() {
		$testPagename = uniqid( 'file-uploader-test-' ) . '.png';
		$testTitle = new Title( 'File:'.$testPagename );

		// Upload a 83725 byte file in 10k chunks.
		$testFilename = dirname( __DIR__ ) . '/fixtures/blue ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ.png';
		$this->fileUploader->setChunkSize( 1024 * 10 );
		$uploaded = $this->fileUploader->upload( $testPagename, $testFilename, 'Testing',
			null, null, true );
		$this->assertTrue( $uploaded );

		// Get the file again, and check that it exists this time.
		$testFile2 = $this->factory->newPageGetter()->getFromTitle( $testTitle );
		$this->assertGreaterThan( 0, $testFile2->getPageIdentifier()->getId() );
	}
}
