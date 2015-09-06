<?php

namespace Mediawiki\Api\Generator;

/**
 * @since 0.6
 */
class CategoryMembersGenerator implements Generator {

	private $params = array();

	public function getName() {
		return 'categorymembers';
	}

	/**
	 * @return string[]
	 */
	public function getParams() {
		return array_merge(
			$this->params,
			array( 'generator' => $this->getName() )
		);
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle( $title ) {
		$this->params['gcmtitle'] = $title;
		unset( $this->params['gcmpageid'] );
		return $this;
	}

	/**
	 * @param int $title
	 *
	 * @return $this
	 */
	public function setPageId( $title ) {
		$this->params['gcmpageid'] = $title;
		unset( $this->params['gcmtitle'] );
		return $this;
	}

}
