<?php

namespace Mediawiki\Api\Repos;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\EditFlags;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Revision;
use Mediawiki\DataModel\Revisions;
use Mediawiki\DataModel\Title;


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
	 * @param string $title
	 *
	 * @returns Page
	 */
	public function getFromTitle( $title ) {
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
			$revisions,
			$page->getContentmodel()
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
			$revisions,
			$result['contentmodel']
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
			'rvprop' => 'ids|flags|timestamp|user|userid|size|sha1|contentmodel|comment|parsedcomment|content|tags',
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
		foreach( $array['revisions'] as $revision ) {
			$revisions->addRevision(
				new Revision(
					$revision['revid'],
					$revision['*'],
					$revision['user'],
					new EditFlags(
						$revision['comment'],
						array_key_exists( 'minor', $revision ),
						array_key_exists( 'bot', $revision )
					),
					$revision['timestamp']
				)
			);
		}
		return $revisions;
	}

	/**
	 * @param array $array
	 *
	 * @return Revision
	 */
	private function newPageFromResult( $array ) {
		return new Page(
			new Title(
				$array['title'],
				$array['ns']
			),
			$array['pageid'],
			$this->getRevisionsFromResult( $array ),
			$array['contentmodel']
		);
	}

}