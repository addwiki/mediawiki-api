<?php

namespace Mediawiki\Api\Test\Generator;

use Mediawiki\Api\Generator\FluidGenerator;

/**
 * @covers \Mediawiki\Api\Generator\FluidGenerator
 */
class FluidGeneratorTest extends \PHPUnit_Framework_TestCase {

	public function testConstructionWithNoGPrefix() {
		$generator = new FluidGenerator( 'name' );
		$generator->set( 'foo', 'bar' );

		$this->assertEquals(
			array(
				'generator' => 'name',
				'gfoo' => 'bar',
			),
			$generator->getParams()
		);
	}

	public function testConstructionWithGPrefix() {
		$generator = new FluidGenerator( 'name' );
		$generator->set( 'gfoo', 'bar' );

		$this->assertEquals(
			array(
				'generator' => 'name',
				'gfoo' => 'bar',
			),
			$generator->getParams()
		);
	}

	public function testFluidity() {
		$generator = FluidGenerator::factory( 'name' )
			->set( 'foo', 'bar' )
			->set( 'gcat', 'meow' );

		$this->assertEquals(
			array(
				'generator' => 'name',
				'gfoo' => 'bar',
				'gcat' => 'meow',
			),
			$generator->getParams()
		);
	}

}
