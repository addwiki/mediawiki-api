<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\Options\QueryOptions;
use Mediawiki\DataModel\Content;
use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Revision;
use Mediawiki\DataModel\Revisions;
use Mediawiki\DataModel\Title;
use Mediawiki\DataModel\WikitextContent;
use RuntimeException;

/**
 * @author Adam Shorland
 */
class PageGetter {

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
	 * @since 0.2
	 *
	 * @param int $id
	 * @param QueryOptions|null $options
	 *
	 * @returns Page
	 */
	public function getFromRevisionId( $id, QueryOptions $options = null ) {
		if( is_null( $options ) ) {
			$options = new QueryOptions();
		}
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'revids' => $id ), $options ) );
		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	/**
	 * @since 0.2
	 *
	 * @param string|Title $title
	 * @param QueryOptions|null $options
	 *
	 * @returns Page
	 */
	public function getFromTitle( $title, QueryOptions $options = null ) {
		if( is_null( $options ) ) {
			$options = new QueryOptions();
		}
		if( $title instanceof Title ) {
			$title = $title->getTitle();
		}
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'titles' => $title ), $options ) );
		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	/**
	 * @since 0.2
	 *
	 * @param int $id
	 * @param QueryOptions|null $options
	 *
	 * @returns Page
	 */
	public function getFromPageId( $id, QueryOptions $options = null ) {
		if( is_null( $options ) ) {
			$options = new QueryOptions();
		}
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'pageids' => $id ), $options ) );
		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	/**
	 * @since 0.2
	 *
	 * @param Page $page
	 * @param QueryOptions|null $options
	 *
	 * @return Page
	 */
	public function getFromPage( Page $page, QueryOptions $options = null ) {
		if( is_null( $options ) ) {
			$options = new QueryOptions();
		}
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'pageids' => $page->getId() ), $options ) );
		$revisions = $this->getRevisionsFromResult( array_shift( $result['query']['pages'] ) );
		$revisions->addRevisions( $page->getRevisions() );
		return new Page(
			$page->getTitle(),
			$page->getId(),
			$revisions
		);
	}

	/**
	 * @since 0.2
	 *
	 * @param Revision $revision
	 * @param QueryOptions|null $options
	 *
	 * @return Page
	 */
	public function getFromRevision( Revision $revision, QueryOptions $options = null ) {
		if( is_null( $options ) ) {
			$options = new QueryOptions();
		}
		$result = $this->api->getAction( 'query', $this->getQuery( array( 'revids' => $revision->getId() ), $options ) );
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
	 * @param QueryOptions $options
	 *
	 * @return array
	 */
	private function getQuery( $additionalParams, QueryOptions $options ) {
		$base = array(
			'prop' => 'revisions|info|pageprops',
			'rvprop' => 'ids|flags|timestamp|user|size|sha1|comment|content|tags',
			'inprop' => 'protection',
		);
		if( $options->getFollowRedirects() ) {
			$base['redirects'] = '';
		}
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
	 * @since 0.2
	 *
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