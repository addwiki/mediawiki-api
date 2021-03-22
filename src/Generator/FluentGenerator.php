<?php

namespace Addwiki\Mediawiki\Api\Generator;

/**
 * @access public
 */
class FluentGenerator implements ApiGenerator {

	private string $name;
	private array $params = [];

	public function __construct( string $name ) {
		$this->name = $name;
	}

	/**
	 * Convenience method for using this fluidly
	 *
	 *
	 */
	public static function factory( string $name ): FluentGenerator {
		return new self( $name );
	}

	/**
	 * @return string[]
	 */
	public function getParams(): array {
		$params = $this->params;
		$params['generator'] = $this->name;
		return $params;
	}

	/**
	 * @param string $key optionally with the 'g' prefix
	 */
	public function set( string $key, string $value ): self {
		$key = $this->addKeyPrefixIfNeeded( $key );
		$this->params[$key] = $value;
		return $this;
	}

	private function addKeyPrefixIfNeeded( string $key ): string {
		if ( strtolower( substr( $key, 0, 1 ) ) === 'g' ) {
			return $key;
		}
		return 'g' . $key;
	}

}
