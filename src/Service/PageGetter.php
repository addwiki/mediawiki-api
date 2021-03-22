<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Content;
use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Mediawiki\DataModel\Page;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Mediawiki\DataModel\Revisions;
use Addwiki\Mediawiki\DataModel\Title;
use RuntimeException;

/**
 * @access private
 */
class PageGetter extends Service {

	public function getFromRevisionId( int $id, array $extraParams = [] ): Page {
		$result =
			$this->api->request(
				ActionRequest::simpleGet(
					'query',
					$this->getQuery( [ 'revids' => $id ], $extraParams )
				)
			);

		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	/**
	 * @param string|Title $title
	 */
	public function getFromTitle( $title, array $extraParams = [] ): Page {
		if ( $title instanceof Title ) {
			$title = $title->getTitle();
		}
		$result =
			$this->api->request(
				ActionRequest::simpleGet(
					'query',
					$this->getQuery( [ 'titles' => $title ], $extraParams )
				)
			);

		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	public function getFromPageId( int $id, array $extraParams = [] ): Page {
		$result =
			$this->api->request(
				ActionRequest::simpleGet(
					'query',
					$this->getQuery( [ 'pageids' => $id ], $extraParams )
				)
			);

		return $this->newPageFromResult( array_shift( $result['query']['pages'] ) );
	}

	/**
	 *
	 * @throws RuntimeException
	 * @return Page|void
	 */
	public function getFromPageIdentifier(
		PageIdentifier $pageIdentifier,
		array $extraParams = []
	) {
		if ( !$pageIdentifier->identifiesPage() ) {
			throw new RuntimeException( '$pageIdentifier does not identify a page' );
		}
		if ( $pageIdentifier->getId() !== null ) {
			return $this->getFromPageId( $pageIdentifier->getId(), $extraParams );
		} else {
			return $this->getFromTitle( $pageIdentifier->getTitle(), $extraParams );
		}
	}

	public function getFromPage( Page $page, array $extraParams = [] ): Page {
		$result =
			$this->api->request(
				ActionRequest::simpleGet(
					'query',
					$this->getQuery( [ 'pageids' => $page->getId() ], $extraParams )
				)
			);
		$revisions = $this->getRevisionsFromResult( array_shift( $result['query']['pages'] ) );
		$revisions->addRevisions( $page->getRevisions() );

		return new Page(
			$page->getPageIdentifier(),
			$revisions
		);
	}

	public function getFromRevision( Revision $revision, array $extraParams = [] ): Page {
		$result =
			$this->api->request(
				ActionRequest::simpleGet(
					'query',
					$this->getQuery( [ 'revids' => $revision->getId() ], $extraParams )
				)
			);
		$revisions = $this->getRevisionsFromResult( array_shift( $result['query']['pages'] ) );
		$revisions->addRevision( $revision );

		return new Page(
			new PageIdentifier(
				new Title(
					$result['title'],
					$result['ns']
				),
				$result['pageid']
			),
			$revisions
		);
	}

	/**
	 * @return mixed[]
	 */
	private function getQuery( array $additionalParams, array $extraParams = [] ): array {
		$base = [
			'prop' => 'revisions|info|pageprops',
			'rvprop' => 'ids|flags|timestamp|user|size|sha1|comment|content|tags',
			'inprop' => 'protection',
		];

		return array_merge( $extraParams, $base, $additionalParams );
	}

	private function getRevisionsFromResult( array $array ): Revisions {
		$revisions = new Revisions();
		$pageid = $array['pageid'];
		foreach ( $array['revisions'] as $revision ) {
			$revision['comment'] ??= '';
			$revisions->addRevision(
				new Revision(
					$this->getContent( $array['contentmodel'], $revision['*'] ),
					new PageIdentifier( new Title( $array['title'], $array['ns'] ), $pageid ),
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
	 * @param string $content returned from the API
	 * @throws RuntimeException
	 */
	private function getContent( string $model, string $content ): Content {
		return new Content( $content, $model );
	}

	private function newPageFromResult( array $array ): Page {
		if ( array_key_exists( 'pageid', $array ) ) {
			$pageid = $array['pageid'];
			$revisions = $this->getRevisionsFromResult( $array );
		} else {
			$pageid = 0;
			$revisions = new Revisions();
		}

		return new Page(
			new PageIdentifier(
				new Title(
					$array['title'],
					$array['ns']
				),
				$pageid
			),
			$revisions
		);
	}

}
