<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\SimpleRequest;
use Mediawiki\Api\UsageException;
use Mediawiki\DataModel\File;

/**
 * @access private
 *
 * @author Addshore
 */
class ImageRotator extends Service {

	/**
	 * NOTE: This service has not been fully tested
	 *
	 * @param File $file
	 * @param int $rotation Degrees to rotate image clockwise, One value: 90, 180, 270
	 *
	 * @throws UsageException
	 * @return bool
	 */
	public function rotate( File $file, $rotation ) {
		$params = [
			'rotation' => $rotation,
			'token' => $this->api->getToken(),
		];

		if ( !is_null( $file->getPageIdentifier()->getTitle() ) ) {
			$params['titles'] = $file->getPageIdentifier()->getTitle()->getText();
		} else {
			$params['pageids'] = $file->getPageIdentifier()->getId();
		}

		$result = $this->api->postRequest( new SimpleRequest( 'imagerotate', $params ) );

		// This module sometimes gives odd errors so deal with them..
		if ( array_key_exists( 'imagerotate', $result ) ) {
			$imageRotate = array_pop( $result['imagerotate'] );
			if ( array_key_exists( 'result', $imageRotate ) &&
				$imageRotate['result'] == 'Failure'
			) {
				throw new UsageException(
					'imagerotate-Failure',
					$imageRotate['errormessage'],
					$result
				);
			}
		}

		return true;
	}

}
