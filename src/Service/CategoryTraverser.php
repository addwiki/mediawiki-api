<?php

namespace Mediawiki\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;

/**
 * Category traverser.
 *
 * Note on spelling 'descendant':
 * The adjective, "descending from a biological ancestor", may be spelt either
 * with an a or with an 'e' in the final syllable. However the noun descendant,
 * "one who is the progeny of someone", may be spelt only with an 'a'.
 */
class CategoryTraverser {

	const CALLBACK_CATEGORY = 10;
	const CALLBACK_PAGE = 20;

	/** @var \Mediawiki\Api\MediawikiApi */
	protected $api;

	/** @var string[] */
	protected $namespaces;

	/** @var callable[] */
	protected $callbacks;

	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
		$this->callbacks = [];

		// Find the site's namespace IDs.
		$params = [ 'meta' => 'siteinfo', 'siprop' => 'namespaces' ];
		$namespaces = $this->api->getRequest( new SimpleRequest( 'query', $params ) );
		if ( isset( $namespaces['query']['namespaces'] ) ) {
			$this->namespaces = $namespaces['query']['namespaces'];
		}
	}

	public function addCallback( $type, $callback ) {
		echo "adding callback $type\n";
		if ( !isset( $this->callbacks[$type] ) ) {
			$this->callbacks[$type] = [];
		}
		$this->callbacks[$type][] = $callback;
	}

	/**
	 * Visit every descendant page of $rootCategoryName (which will be a Category
	 * page, because there are no desecendants of any other pages).
	 *
	 * @return string[] Each element is an array of ['pageid','ns','title'].
	 */
	public function descendants( $rootCategoryName ) {
		$descendants = [];
		do {
			$continue = ( isset( $result['continue'] ) ) ? $result['continue']['cmcontinue'] : '';
			$params = [
				'list' => 'categorymembers',
				'cmtitle' => $rootCategoryName,
				'cmcontinue' => $continue,
			];
			$result = $this->api->getRequest( new SimpleRequest( 'query', $params ) );
			echo " -- $rootCategoryName -- ";
			print_r( $result );
			if ( !array_key_exists( 'query', $result ) ) {
				return true;
			}

			foreach ( $result['query']['categorymembers'] as $member ) {
				$isCat = isset( $this->namespaces[$member['ns']] ) && isset( $this->namespaces[$member['ns']]['canonical'] ) && $this->namespaces[$member['ns']]['canonical'] === 'Category';
				if ( $isCat ) {
					echo "\nattempting to callback for cat $rootCategoryName \n";
					$this->call( self::CALLBACK_CATEGORY, [ $member, $rootCategoryName ] );
					$newDescendants = $this->descendants( $member['title'] );
					$descendants = array_merge( $descendants, $newDescendants );
				} else {
					$descendants[$member['pageid']] = $member;
					$this->call( self::CALLBACK_PAGE, [ $member, $rootCategoryName ] );
				}
			}
		} while ( isset( $result['continue'] ) );
		return $descendants;
	}

	protected function call( $type, $params ) {
		if ( !isset( $this->callbacks[$type] ) ) {
			return;
		}
		foreach ( $this->callbacks[$type] as $callback ) {
			if ( is_callable( $callback ) ) {
				echo "calling callback $type\n";
				call_user_func_array( $callback, $params );
			}
		}
	}

}
