<?php

namespace Mediawiki\Api\Test\Generator;

use Mediawiki\Api\Generator\AnonymousGenerator;

/**
 * @covers \Mediawiki\Api\Generator\AnonymousGenerator
 */
class AnonymousGeneratorTest extends \PHPUnit_Framework_TestCase {

	public function testConstruction() {
		$generator = new AnonymousGenerator( 'name', array( 'gfoo' => 'bar' ) );

		$this->assertEquals( 'name', $generator->getName() );
		$this->assertEquals(
			array(
				'generator' => 'name',
				'gfoo' => 'bar',
			),
			$generator->getParams()
		);
	}

}
