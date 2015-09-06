<?php

namespace Mediawiki\Api\Generator;

/**
 * Interface relating to Mediawiki generators
 * @see https://www.mediawiki.org/wiki/API:Query#Generators
 *
 * @since 0.5.1
 */
interface Generator {

	/**
	 * @since 0.5.1
	 *
	 * Associative array of parameters including the 'generator' parameter.
	 * All generator param keys must have their 'g' prefixes
	 *
	 * @return string[]
	 */
	public function getParams();

}
