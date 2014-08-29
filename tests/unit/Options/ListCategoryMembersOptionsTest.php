<?php

namespace Mediawiki\Api\Test\Options;

use Mediawiki\Api\Options\ListCategoryMembersOptions;

/**
 * @author Adam Shorland
 * @covers Mediawiki\Api\Options\ListCategoryMembersOptions
 */
class ListCategoryMembersOptionsTest extends \PHPUnit_Framework_TestCase {

	public function testRecursive() {
		$obj = new ListCategoryMembersOptions();
		$this->assertEquals( false, $obj->getRecursive() );
		$this->assertEquals( $obj, $obj->setRecursive( true ) );
		$this->assertEquals( true, $obj->getRecursive() );
	}

	public function testLimit () {
		$obj = new ListCategoryMembersOptions();
		$this->assertEquals( null, $obj->getLimit() );
		$this->assertEquals( $obj, $obj->setLimit( 100 ) );
		$this->assertEquals( 100, $obj->getLimit() );
	}

} 