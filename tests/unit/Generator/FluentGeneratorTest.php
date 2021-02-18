<?php

namespace Addwiki\Mediawiki\Api\Tests\Unit\Generator;

use Addwiki\Mediawiki\Api\Generator\FluentGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Addwiki\Mediawiki\Api\Generator\FluentGenerator
 */
class FluentGeneratorTest extends TestCase {

	public function testConstructionWithNoGPrefix(): void {
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

	public function testConstructionWithGPrefix(): void {
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

	public function testFluidity(): void {
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
