<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\ListLogEventsOptions;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Log;
use Mediawiki\DataModel\LogList;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Revisions;
use Mediawiki\DataModel\Title;

/**
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
	 * @param ListLogEventsOptions $options
	 *
	 * @return LogList
	 */
	public function getLogList ( ListLogEventsOptions $options = null ) {
		if ($options == null ) {
			$options = new ListLogEventsOptions();
		}

		$logList = new LogList();
		$continue = '';
		$limit = $options->getLimit();

		while ( true ) {
			$params = $this->getParamsFromOptions( $options );
			if( !empty( $continue ) ) {
				$params['lecontinue'] = $continue;
			}
			if( $limit === null ) {
				$params['lelimit'] = 5000;
			} else {
				$params['lelimit'] = $limit;
			}

			$result = $this->api->getRequest( new SimpleRequest( 'query', $params ) );
			$limit = $limit - count( $result[ 'query' ]['logevents'] );

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

			if( $limit !== null && $limit <= 0 ) {
				return $logList;
			}
			if ( empty( $result['query-continue']['logevents']['lecontinue'] ) ) {
				return $logList;
			} else {
				$continue = $result['query-continue']['logevents']['lecontinue'];
			}
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

	/**
	 * @param ListLogEventsOptions $options
	 * @return array
	 */
	private function getParamsFromOptions( $options ) {
		$params = array(
			'list' => 'logevents',
			'rawcontinue' => '',
			'leprop' => 'title|ids|type|user|timestamp|comment|details'
		);
		if( $options->getType() !== '' ) {
			$params['letype'] = $options->getType();
		}
		if( $options->getAction() !== '' ) {
			$params['leaction'] = $options->getAction();
		}
		if( $options->getStart() !== '' ) {
			$params['lestart'] = $options->getStart();
		}
		if( $options->getEnd() !== '' ) {
			$params['leend'] = $options->getEnd();
		}
		if( $options->getTitle() !== '' ) {
			$params['letitle'] = $options->getTitle();
		}
		if( $options->getUser() !== '' ) {
			$params['leuser'] = $options->getUser();
		}
		if( $options->getNamespace() !== null ) {
			$params['lenamespace'] = $options->getNamespace();
		}
		return $params;
	}

} 