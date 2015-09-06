<?php

namespace Mediawiki\Api\Generator;

/**
 * @since 0.6
 */
interface Generator {

	/**
	 * @since 0.6
	 *
	 * The name of the generator according to the Mediawiki API.
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * @since 0.6
	 *
	 * Associative array of parameters including the 'generator' parameter.
	 *
	 * @return string[]
	 */
	public function getParams();

}
