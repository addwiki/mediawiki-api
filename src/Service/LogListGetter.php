<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\ListLogEventsOptions;
use Mediawiki\DataModel\Log;
use Mediawiki\DataModel\LogList;
use Mediawiki\DataModel\Page;
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
		$continue = '';
		$logList = new LogList();

		if ($options == null ) {
			$options = new ListLogEventsOptions();
		}

		while ( true ) {
			$params = $this->getParamsFromOptions( $options );
			if( !empty( $continue ) ) {
				$params['lecontinue'] = $continue;
			}

			$result = $this->api->getAction( 'query', $params );

			foreach ( $result[ 'query' ]['logevents'] as $logevent ) {
				$logList->addLog(
					new Log(
						$logevent['logid'],
						$logevent['type'],
						$logevent['action'],
						$logevent['timestamp'],
						$logevent['user'],
						new Page( new Title( $logevent['title'], $logevent['ns']), $logevent['pageid'], new Revisions() ),
						$logevent['comment'])
				);
			}

			if ( empty( $result['query-continue']['logevents']['lecontinue'] ) ) {
				return $logList;
			} else {
				$continue = $result['query-continue']['logevents']['lecontinue'];
			}
		}
	}

	/**
	 * @param ListLogEventsOptions $options
	 * @return array
	 */
	private function getParamsFromOptions( $options ) {
		$params = array( 'list' => 'logevents' );
		$params['leprop'] = 'title|ids|type|user|timestamp|comment|details';
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
		//TODO bots should be able to request more than 500 at once!
		$params['lelimit'] = '500';
		return $params;
	}

} 