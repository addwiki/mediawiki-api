<?php

namespace Addwiki\Mediawiki\Api\Tests\Integration;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\Api\Guzzle\ClientFactory;
use Addwiki\Mediawiki\Api\MediawikiFactory;
use Exception;

/**
 * @author Addshore
 */
class TestEnvironment {

	/**
	 * @var string
	 */
	public $apiUrl;
	/**
	 * @var string
	 */
	public $pageUrl;
	/** @var MediawikiFactory */
	private $factory;

	/** @var \Addwiki\Mediawiki\Api\Client\MediawikiApi */
	protected $api;

	/**
	 * Get a new TestEnvironment.
	 * This is identical to calling self::__construct() but is useful for fluent construction.
	 *
	 * @return TestEnvironment
	 */
	public static function newInstance() {
		return new self();
	}

	/**
	 * Set up the test environment by creating a new API object pointing to a
	 * MediaWiki installation on localhost (or elsewhere as specified by the
	 * ADDWIKI_MW_API environment variable).
	 *
	 * @throws Exception If the ADDWIKI_MW_API environment variable does not end in 'api.php'
	 */
	public function __construct() {
		$apiUrl = getenv( 'ADDWIKI_MW_API' );

		if ( !$apiUrl ) {
			$apiUrl = "http://localhost:8877/api.php";
		}

		if ( substr( $apiUrl, -7 ) !== 'api.php' ) {
			$msg = sprintf( 'URL incorrect: %s', $apiUrl )
				. " (Set the ADDWIKI_MW_API environment variable correctly)";
			throw new Exception( $msg );
		}

		$this->apiUrl = $apiUrl;
		$this->pageUrl = str_replace( 'api.php', 'index.php?title=Special:SpecialPages', $apiUrl );
		$this->api = MediawikiApi::newFromApiEndpoint( $this->apiUrl );

		$this->factory = new MediawikiFactory( $this->api );
	}

	/**
	 * Get the MediawikiApi to test against
	 * @return MediawikiApi
	 */
	public function getApi() {
		return $this->api;
	}

	/**
	 * Get the MediaWiki factory.
	 *
	 * @return MediawikiFactory The factory instance.
	 */
	public function getFactory() {
		return $this->factory;
	}

	/**
	 * Run all jobs in the queue. This only works if the MediaWiki installation has $wgJobRunRate
	 * set to greater than zero (for test-running, you should set it to something higher than 50).
	 * @todo This and TestEnvironment::getJobQueueLength() should probably not live here.
	 * @return void
	 */
	public function runJobs() {
		$reqestProps = [ 'meta' => 'siteinfo', 'siprop' => 'general' ];
		$siteInfoRequest = new SimpleRequest( 'query', $reqestProps );
		$out = $this->getApi()->getRequest( $siteInfoRequest );
		$mainPageUrl = $out['query']['general']['base'];
		$i = 0;
		while ( $this->getJobQueueLength( $this->getApi() ) > 0 ) {
			++$i;
			$cf = new ClientFactory();
			$cf->getClient()->get( $mainPageUrl );
			if ( $i == 10 ) {
				// Give up if we've been looping too much. This is very arbitrary.
				break;
			}
		}
	}

	/**
	 * Get the number of jobs currently in the queue.
	 * @param \Addwiki\Mediawiki\Api\Client\MediawikiApi $api
	 * @return int
	 * @todo This and TestEnvironment::runJobs() should probably not live here.
	 */
	public function getJobQueueLength( MediawikiApi $api ) {
		$req = new SimpleRequest( 'query', [
				'meta' => 'siteinfo',
				'siprop' => 'statistics',
			]
		);
		$out = $api->getRequest( $req );
		return (int)$out['query']['statistics']['jobs'];
	}
}
