<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\Guzzle\ClientFactory;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\MediawikiFactory;
use Mediawiki\Api\SimpleRequest;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * @author Addshore
 */
class TestEnvironment {

	/** @var \Mediawiki\Api\MediawikiFactory */
	private $factory;

	/** @var MediawikiApi */
	protected $api;

	/**
	 * Get a new default test environment.
	 * @return TestEnvironment
	 */
	public static function newDefault() {
		return new self();
	}

	/**
	 * Set up the test environment by creating a new API object pointing to a
	 * MediaWiki installation on localhost (or elsewhere as specified by the
	 * MEDIAWIKI_API_URL environment variable).
	 */
	public function __construct() {
		$this->factory = new MediawikiFactory( $this->getApi() );
	}

	/**
	 * Get the MediawikiApi to test against, based on the MEDIAWIKI_API_URL environment variable.
	 * @return MediawikiApi
	 * @throws \Exception If the MEDIAWIKI_API_URL environment variable does not end in 'api.php'
	 */
	public function getApi() {
		if ( $this->api instanceof MediawikiApi ) {
			return $this->api;
		}
		$apiUrl = getenv( 'MEDIAWIKI_API_URL' );
		if ( empty( $apiUrl ) ) {
			$apiUrl = 'http://localhost/w/api.php';
		} elseif ( substr( $apiUrl, -7 ) !== 'api.php' ) {
			$msg = "URL incorrect: $apiUrl"
				. " (the MEDIAWIKI_API_URL environment variable should end in 'api.php')";
			throw new \Exception( $msg );
		}

		// Log to a local file.
		$logger = new Logger( 'mediawiki-api' );
		$logFile = __DIR__ . '/../../log/mediawiki-api.log';
		$logger->pushHandler( new StreamHandler( $logFile, Logger::DEBUG ) );

		// Create and return the API object.
		$this->api = new MediawikiApi( $apiUrl );
		$this->api->setLogger( $logger );
		return $this->api;
	}

	/**
	 * Get the MediaWiki factory.
	 *
	 * @return \Mediawiki\Api\MediawikiFactory The factory instance.
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
			$i++;
			$cf = new ClientFactory();
			$cf->getClient()->get( $mainPageUrl );
			if ($i == 10) {
				// Give up if we've been looping too much. This is very arbitrary.
				break;
			}
		}
	}

	/**
	 * Get the number of jobs currently in the queue.
	 * @todo This and TestEnvironment::runJobs() should probably not live here.
	 * @param MediawikiApi $api
	 * @return int
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
