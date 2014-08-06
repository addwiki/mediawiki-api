<?php

namespace Mediawiki\Api\Test\Options;

use Mediawiki\Api\Options\ListEmbededInOptions;

/**
 * @author Adam Shorland
 * @covers Mediawiki\Api\Options\ListEmbededInOptions
 */
class ListEmbededInOptionsTest extends \PHPUnit_Framework_TestCase {

	public function testNamespaces() {
		$obj = new ListEmbededInOptions();
		$this->assertEquals( array(), $obj->getNamespaces() );
		$this->assertEquals( $obj, $obj->setNamespaces( array( 1 ) ) );
		$this->assertEquals( array( 1 ), $obj->getNamespaces() );
	}

} 