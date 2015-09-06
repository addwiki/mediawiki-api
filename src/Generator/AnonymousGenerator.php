<?php

namespace Mediawiki\Api\Generator;

/**
 * @since 0.6
 */
class AnonymousGenerator implements Generator {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * @param string $name
	 * @param array $params including 'g' prefix keys
	 */
	public function __construct( $name, array $params ) {
		$this->name = $name;
		$this->params = $params;
	}

	public function getName() {
		return $this->name;
	}

	public function getParams() {
		return $this->params;
	}
}
