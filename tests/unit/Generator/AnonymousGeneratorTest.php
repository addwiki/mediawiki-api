<?php

namespace Mediawiki\Api\Test\Generator;

use Mediawiki\Api\Generator\AnonymousGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @author Addshore
 *
 * @covers \Mediawiki\Api\Generator\AnonymousGenerator
 */
class AnonymousGeneratorTest extends TestCase {

	public function testConstruction() {
		$generator = new AnonymousGenerator( 'name', [ 'gfoo' => 'bar' ] );

		$this->assertEquals(
			[
				'generator' => 'name',
				'gfoo' => 'bar',
			],
			$generator->getParams()
		);
	}

}
