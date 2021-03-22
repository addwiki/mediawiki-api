<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Log;
use Addwiki\Mediawiki\DataModel\LogList;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Title;

/**
 * @access private
 */
class LogListGetter extends Service {

	/**
	 *
	 * @return LogList|void
	 */
	public function getLogList( array $extraParams = [] ) {
		$logList = new LogList();

		while ( true ) {
			$params = [
				'list' => 'logevents',
				'leprop' => 'title|ids|type|user|timestamp|comment|details'
			];

			$newParams = array_merge( $extraParams, $params );
			$result = $this->api->request( ActionRequest::simpleGet( 'query', $newParams ) );

			foreach ( $result[ 'query' ]['logevents'] as $logevent ) {
				$logList->addLog(
					new Log(
						$logevent['logid'],
						$logevent['type'],
						$logevent['action'],
						$logevent['timestamp'],
						$logevent['user'],
						new PageIdentifier(
							new Title( $logevent['title'], $logevent['ns'] ),
							$logevent['pageid']
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
	 *
	 * @return mixed[]
	 */
	private function getLogDetailsFromEvent( array $event ): array {
		$ignoreKeys = array_flip( [
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
			'comment' ] );
		return array_diff_key( $event, $ignoreKeys );
	}

}
