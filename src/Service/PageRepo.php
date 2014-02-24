<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Content;
use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Revision;
use Mediawiki\DataModel\Revisions;
use Mediawiki\DataModel\Title;
use Mediawiki\DataModel\WikitextContent;
use RuntimeException;

class PageRepo {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param int $id
	 *
	 * @returns Page
	 */
	public function getFromRevisionId( $id ) {
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'revids' => $id ) ) );
		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	/**
	 * @param string|Title $title
	 *
	 * @returns Page
	 */
	public function getFromTitle( $title ) {
		if( $title instanceof Title ) {
			$title = $title->getTitle();
		}
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'titles' => $title ) ) );
		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	/**
	 * @param int $id
	 *
	 * @returns Page
	 */
	public function getFromPageId( $id ) {
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'pageids' => $id ) ) );
		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	/**
	 * @param Page $page
	 *
	 * @return Page
	 */
	public function getFromPage( Page $page ) {
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'pageids' => $page->getId() ) ) );
		$revisions = $this->getRevisionsFromResult( array_shift( $result['query']['pages'] ) );
		$revisions->addRevisions( $page->getRevisions() );
		return new Page(
			$page->getTitle(),
			$page->getId(),
			$revisions
		);
	}

	/**
	 * @param Revision $revision
	 *
	 * @return Page
	 */
	public function getFromRevision( Revision $revision ) {
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'revids' => $revision->getId() ) ) );
		$revisions = $this->getRevisionsFromResult( array_shift( $result['query']['pages'] ) );
		$revisions->addRevision( $revision );
		return new Page(
			new Title(
				$result['title'],
				$result['ns']
			),
			$result['pageid'],
			$revisions
		);
	}

	/**
	 * @param array $additionalParams
	 *
	 * @return array
	 */
	private function getQuery( $additionalParams ) {
		$base = array(
			'prop' => 'revisions|info|pageprops',
			'rvprop' => 'ids|flags|timestamp|user|size|sha1|comment|content|tags',
			'inprop' => 'protection',
		);
		return array_merge( $base, $additionalParams );
	}

	/**
	 * @param array $array
	 *
	 * @return Revisions
	 */
	private function getRevisionsFromResult( $array ) {
		$revisions = new Revisions();
		$pageid = $array['pageid'];
		foreach( $array['revisions'] as $revision ) {
			$revisions->addRevision(
				new Revision(
					$this->getContent( $array['contentmodel'], $revision['*'] ),
					$pageid,
					$revision['revid'],
					new EditInfo(
						$revision['comment'],
						array_key_exists( 'minor', $revision ),
						array_key_exists( 'bot', $revision )
					),
					$revision['user'],
					$revision['timestamp']
				)
			);
		}
		return $revisions;
	}

	/**
	 * @param string $model
	 * @param string $content returned from the API
	 *
	 * @throws RuntimeException
	 * @return Content
	 */
	public function getContent( $model, $content ) {
		switch ( $model ) {
			case WikitextContent::contentModel;
				return new WikitextContent( $content );
			default:
				throw new RuntimeException( 'Unknown Content Model' );
		}
	}

	/**
	 * @param array $array
	 *
	 * @return Page
	 */
	private function newPageFromResult( $array ) {
		if( array_key_exists( 'pageid', $array ) ) {
			$pageid = $array['pageid'];
			$revisions = $this->getRevisionsFromResult( $array );
		} else {
			$pageid = null;
			$revisions = new Revisions();
		}

		return new Page(
			new Title(
				$array['title'],
				$array['ns']
			),
			$pageid,
			$revisions
		);
	}

}