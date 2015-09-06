<?php

namespace Mediawiki\Api\Generator;

/**
 * @since 0.6
 */
class FluidGenerator implements Generator {

	private $name;
	private $params;

	/**
	 * @param string $name
	 */
	public function __construct( $name ) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function getParams() {
		return $this->params;
	}

	/**
	 * @param string $key including 'g' prefix
	 * @param string $value
	 *
	 * @return $this
	 */
	public function set( $key, $value ) {
		$this->params[$key] = $value;
		return $this;
	}

}
