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
		if ($options == null ) {
			$options = new ListLogEventsOptions();
		}
		$params = array( 'list' => 'logevents' );
		$params['leprop'] = 'namespace|title|ids|type|user|timestamp|comment|details';
		if( $options->getType() != '' ) {
			$params['letype'] = $options->getType();
		}
		if( $options->getAction() != '' ) {
		$params['leaction'] = $options->getAction();
		}
		if( $options->getStart() != '' ) {
		$params['lestart'] = $options->getStart();
		}
		if( $options->getEnd() != '' ) {
		$params['leend'] = $options->getEnd();
		}
		if( $options->getTitle() != '' ) {
		$params['letitle'] = $options->getTitle();
		}
		if( $options->getUser() != '' ) {
		$params['leuser'] = $options->getUser();
		}
		if( $options->getNamespace() != '' ) {
		$params['lenamespace'] = $options->getNamespace();
		}
		$result = $this->api->getAction( 'query', $params );
		$loglist = new LogList();
		foreach ( $result[ 'query' ]['logevents'] as $logevent ) {
			$loglist->addLog(
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
		return $loglist;
	}
} 