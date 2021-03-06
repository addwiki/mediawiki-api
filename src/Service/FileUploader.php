<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Exception;

/**
 * @access private
 */
class FileUploader extends Service {

	protected ?int $chunkSize = null;

	/**
	 * Set the chunk size used for chunked uploading.
	 *
	 * Chunked uploading is available in MediaWiki 1.20 and above, although prior to version 1.25,
	 * SVGs could not be uploaded via chunked uploading.
	 *
	 * @link https://www.mediawiki.org/wiki/API:Upload#Chunked_uploading
	 *
	 * @param int $chunkSize In bytes.
	 */
	public function setChunkSize( int $chunkSize ): void {
		$this->chunkSize = $chunkSize;
	}

	/**
	 * Upload a file.
	 *
	 * @param string $targetName The name to give the file on the wiki (no 'File:' prefix required).
	 * @param string $location Can be local path or remote URL.
	 * @param string $text Initial page text for new files.
	 * @param string|null $comment Upload comment. Also used as the initial page text for new files if
	 * text parameter not provided.
	 * @param string|null $watchlist Unconditionally add or remove the page from your watchlist, use
	 * preferences or do not change watch. Possible values: 'watch', 'preferences', 'nochange'.
	 * @param bool $ignoreWarnings Ignore any warnings. This must be set to upload a new version of
	 * an existing image.
	 */
	public function upload(
		string $targetName,
		string $location,
		string $text = '',
		?string $comment = '',
		?string $watchlist = 'preferences',
		bool $ignoreWarnings = false
	): bool {
		$params = [
			'filename' => $targetName,
			'token' => $this->api->getToken(),
		];
		// Watchlist behaviour.
		if ( in_array( $watchlist, [ 'watch', 'nochange' ] ) ) {
			$params['watchlist'] = $watchlist;
		}
		// Ignore warnings?
		if ( $ignoreWarnings ) {
			$params['ignorewarnings'] = '1';
		}
		// Page text.
		if ( !empty( $text ) ) {
			$params['text'] = $text;
		}
		// Revision comment.
		if ( $comment !== null && !empty( $comment ) ) {
			$params['comment'] = $comment;
		}

		if ( is_file( $location ) ) {
			// Normal single-request upload.
			$params['filesize'] = filesize( $location );
			$params['file'] = fopen( $location, 'r' );
			if ( $this->chunkSize !== null && $this->chunkSize > 0 ) {
				// Chunked upload.
				$params = $this->uploadByChunks( $params );
			}
		} else {
			// Upload from URL.
			$params['url'] = $location;
		}

		// TODO force this POST to be a multipart...!
		$response = $this->api->request( ActionRequest::simplePost( 'upload', $params )->setMultipart( true ) );
		return ( $response['upload']['result'] === 'Success' );
	}

	/**
	 * Upload a file by chunks and get the parameters for the final upload call.
	 * @param mixed[] $params The request parameters.
	 * @return mixed[]
	 * @throws Exception
	 */
	protected function uploadByChunks( array $params ) {
		// Get the file handle for looping, but don't keep it in the request parameters.
		$fileHandle = $params['file'];
		unset( $params['file'] );
		// Track the chunks and offset.
		$chunksDone = 0;
		$params['offset'] = 0;
		while ( true ) {
			// 1. Make the request.
			$params['chunk'] = fread( $fileHandle, $this->chunkSize );
			$contentDisposition = 'form-data; name="chunk"; filename="' . $params['filename'] . '"';
			$request = ActionRequest::factory()
				->setMethod( 'POST' )
				->setParams( $params )
				->addParams( [ 'action' => 'upload' ] )
				->setMultipartParams( [
					'chunk' => [ 'headers' => [ 'Content-Disposition' => $contentDisposition ] ],
				] );
			$response = $this->api->request( $request );

			// 2. Deal with the response.
			++$chunksDone;
			$params['offset'] = ( $chunksDone * $this->chunkSize );
			if ( !isset( $response['upload']['filekey'] ) ) {
				// This should never happen. Even the last response still has the filekey.
				throw new Exception( 'Unable to get filekey for chunked upload' );
			}
			$params['filekey'] = $response['upload']['filekey'];
			if ( $response['upload']['result'] === 'Continue' ) {
				// Amend parameters for next upload POST request.
				$params['offset'] = $response['upload']['offset'];
			} else {
				// The final upload POST will be done in self::upload()
				// to commit the upload out of the stash area.
				unset( $params['chunk'], $params['offset'] );
				return $params;
			}
		}
	}
}
