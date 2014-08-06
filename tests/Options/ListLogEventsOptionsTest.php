<?php

namespace Mediawiki\Api\Test\Options;


use Mediawiki\Api\Options\ListLogEventsOptions;
use PHPUnit_Framework_TestCase;

/**
 * @author Thomas Arrow
 * @covers Mediawiki\Api\Options\ListLogEventsOptions
 */
class ListLogEventsOptionsTest extends PHPUnit_Framework_TestCase {

	public function testAction () {
		$obj = new ListLogEventsOptions();
		$this->assertEquals( '', $obj->getAction() );
		$this->assertEquals( $obj, $obj->setAction( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getAction() );
	}

	public function testStart () {
		$obj = new ListLogEventsOptions();
		$this->assertEquals( '', $obj->getStart() );
		$this->assertEquals( $obj, $obj->setStart( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getStart() );
	}

	public function testEnd () {
		$obj = new ListLogEventsOptions();
		$this->assertEquals( '', $obj->getEnd() );
		$this->assertEquals( $obj, $obj->setEnd( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getEnd() );
	}
	public function testNamespace () {
		$obj = new ListLogEventsOptions();
		$this->assertEquals( null, $obj->getNamespace() );
		$this->assertEquals( $obj, $obj->setNamespace( 10 ) );
		$this->assertEquals( 10, $obj->getNamespace() );
	}

	public function testTitle () {
		$obj = new ListLogEventsOptions();
		$this->assertEquals( '', $obj->getTitle() );
		$this->assertEquals( $obj, $obj->setTitle( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getTitle() );
}

	public function testUser () {
		$obj = new ListLogEventsOptions();
		$this->assertEquals( '', $obj->getUser() );
		$this->assertEquals( $obj, $obj->setUser( 'foo' ) );
		$this->assertEquals( 'foo', $obj->getUser() );
	}

}