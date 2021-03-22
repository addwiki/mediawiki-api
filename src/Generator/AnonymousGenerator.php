<?php

namespace Addwiki\Mediawiki\Api\Generator;

/**
 * @access public
 */
class AnonymousGenerator implements ApiGenerator {

	private string $name;

	private array $params = [];

	/**
	 * @param array $params including 'g' prefix keys
	 */
	public function __construct( string $name, array $params = [] ) {
		$this->name = $name;
		$this->params = $params;
	}

	public function getParams(): array {
		$this->params['generator'] = $this->name;
		return $this->params;
	}
}
