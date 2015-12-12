<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Log;
use Mediawiki\DataModel\LogList;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Revisions;
use Mediawiki\DataModel\Title;

/**
 * @access private
 *
 * @author Thomas Arrow
 */
class LogListGetter {
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
	 * @param array $extraParams
	 *
	 * @return LogList
	 */
	public function getLogList ( array $extraParams = array() ) {
		$logList = new LogList();

		while ( true ) {
			$params = array(
				'list' => 'logevents',
				'rawcontinue' => '',
				'leprop' => 'title|ids|type|user|timestamp|comment|details'
			);

			$result = $this->api->getRequest( new SimpleRequest( 'query', array_merge( $extraParams, $params ) ) );

			foreach ( $result[ 'query' ]['logevents'] as $logevent ) {
				$logList->addLog(
					new Log(
						$logevent['logid'],
						$logevent['type'],
						$logevent['action'],
						$logevent['timestamp'],
						$logevent['user'],
						new Page(
							new PageIdentifier(
								new Title( $logevent['title'], $logevent['ns'] ),
								$logevent['pageid']
							),
							new Revisions()
						),
						$logevent['comment'],
						$this->getLogDetailsFromEvent( $logevent )
					)
				);
			}

			return $logList;
		}
	}

	/**
	 * @param array $event
	 *
	 * @return array
	 */
	private function getLogDetailsFromEvent( $event ) {
		$ignoreKeys = array_flip( array(
			'logid',
			'ns',
			'title',
			'pageid',
			'logpage',
			'type',
			'action',
			'user',
			'type',
			'timestamp',
			'comment' ) );
		return array_diff_key( $event, $ignoreKeys );
	}

} 