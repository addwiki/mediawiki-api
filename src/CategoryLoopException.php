<?php

namespace Addwiki\Mediawiki\Api;

use Addwiki\Mediawiki\DataModel\Pages;
use Exception;

/**
 * Class CategoryLoopException
 * @package Addwiki\Mediawiki\Api
 */
class CategoryLoopException extends Exception {

	protected ?Pages $categoryPath = null;

	public function setCategoryPath( Pages $path ): void {
		$this->categoryPath = $path;
	}

	/**
	 * Get the path of Pages that comprise the category loop. The first item in this list is also a
	 * child page of the last item.
	 * @return Pages The set of category Pages that comprise the category loop.
	 */
	public function getCategoryPath(): Pages {
		return $this->categoryPath;
	}

}
