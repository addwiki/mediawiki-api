<?php

namespace Mediawiki\Api\Generator;

/**
 * Interface relating to Mediawiki generators
 * @see https://www.mediawiki.org/wiki/API:Query#Generators
 *
 * @since 0.6
 */
interface Generator {

	/**
	 * @since 0.6
	 *
	 * The name of the generator according to the Mediawiki API.
	 * Examples: watchlist / links / categorymembers
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * @since 0.6
	 *
	 * Associative array of parameters including the 'generator' parameter.
	 * All generator param keys must have their 'g' prefixes
	 *
	 * @return string[]
	 */
	public function getParams();

}