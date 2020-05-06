<?php

namespace Addwiki\MediaWikiApi\Test\Generator;

use Addwiki\MediaWikiApi\Generator\FluentGenerator;

/**
 * @author Addshore
 *
 * @covers \Addwiki\MediaWikiApi\Generator\FluentGenerator
 */
class FluentGeneratorTest extends \PHPUnit_Framework_TestCase {

	public function testConstructionWithNoGPrefix() {
		$generator = new FluentGenerator( 'name' );
		$generator->set( 'foo', 'bar' );

		$this->assertEquals(
			[
				'generator' => 'name',
				'gfoo' => 'bar',
			],
			$generator->getParams()
		);
	}

	public function testConstructionWithGPrefix() {
		$generator = new FluentGenerator( 'name' );
		$generator->set( 'gfoo', 'bar' );

		$this->assertEquals(
			[
				'generator' => 'name',
				'gfoo' => 'bar',
			],
			$generator->getParams()
		);
	}

	public function testFluidity() {
		$generator = FluentGenerator::factory( 'name' )
			->set( 'foo', 'bar' )
			->set( 'gcat', 'meow' );

		$this->assertEquals(
			[
				'generator' => 'name',
				'gfoo' => 'bar',
				'gcat' => 'meow',
			],
			$generator->getParams()
		);
	}

}
