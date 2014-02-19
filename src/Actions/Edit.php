<?php

namespace Mediawiki\Api\Actions;

use Mediawiki\DataModel\EditFlags;
use Mediawiki\DataModel\Page;

class Edit {

	/**
	 * @var string
	 */
	protected $basetimestamp;

	/**
	 * @var string|null
	 */
	protected $text = null;

	/**
	 * @var int
	 */
	protected $pageid;

	/**
	 * @var EditFlags
	 */
	protected $editFlags;

	/**
	 * @param Page $page
	 * @param string $text
	 * @param EditFlags|null $editFlags
	 */
	public function __construct( Page $page, $text, $editFlags = null ) {
		$this->basetimestamp = $page->getRevisions()->getLatest()->getTimestamp();
		$this->pageid = $page->getId();
		$this->text = $text;

		if( is_null( $editFlags ) ) {
			$this->editFlags = new EditFlags();
		} else {
			$this->editFlags = $editFlags;
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBasetimestamp() {
		return $this->basetimestamp;
	}

	/**
	 * @return int
	 */
	public function getPageid() {
		return $this->pageid;
	}

	/**
	 * @return string|null
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @return EditFlags
	 */
	public function getEditFlags() {
		return $this->editFlags;
	}

} 