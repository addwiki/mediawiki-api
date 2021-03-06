<?php

namespace Addwiki\Mediawiki\Api\Tests\Integration;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\Api\Client\Auth\UserAndPassword;
use Addwiki\Mediawiki\Api\Guzzle\ClientFactory;
use Exception;

class TestEnvironment {

	public string $apiUrl;
	public string $pageUrl;

	protected ActionApi $api;
	protected ActionApi $apiAuthed;

	/**
	 * Get a new TestEnvironment.
	 * This is identical to calling self::__construct() but is useful for fluent construction.
	 */
	public static function newInstance(): TestEnvironment {
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
		$this->api = new ActionApi( $this->apiUrl );
		$this->apiAuthed = new ActionApi( $this->apiUrl, new UserAndPassword( 'CIUser', 'LongCIPass123' ) );
	}

	/**
	 * Get the MediawikiApi to test against
	 */
	public function getApi(): ActionApi {
		return $this->api;
	}

	/**
	 * Get the MediawikiApi to test against (with authentication with the CI user)
	 */
	public function getApiAuthed(): ActionApi {
		return $this->apiAuthed;
	}

	/**
	 * Run all jobs in the queue. This only works if the MediaWiki installation has $wgJobRunRate
	 * set to greater than zero (for test-running, you should set it to something higher than 50).
	 * @todo This and TestEnvironment::getJobQueueLength() should probably not live here.
	 */
	public function runJobs(): void {
		$reqestProps = [ 'meta' => 'siteinfo', 'siprop' => 'general' ];
		$siteInfoRequest = ActionRequest::simpleGet( 'query', $reqestProps );
		$out = $this->getApi()->request( $siteInfoRequest );
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
	 * @param \Addwiki\Mediawiki\Api\Client\Action\ActionApi $api
	 * @todo This and TestEnvironment::runJobs() should probably not live here.
	 */
	public function getJobQueueLength( ActionApi $api ): int {
		$req = ActionRequest::simpleGet( 'query', [
				'meta' => 'siteinfo',
				'siprop' => 'statistics',
			]
		);
		$out = $api->request( $req );
		return (int)$out['query']['statistics']['jobs'];
	}
}
