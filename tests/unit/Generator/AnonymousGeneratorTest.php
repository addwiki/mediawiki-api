<?php

namespace Addwiki\MediaWikiApi\Test\Generator;

use Addwiki\MediaWikiApi\Generator\AnonymousGenerator;

/**
 * @author Addshore
 *
 * @covers \Addwiki\MediaWikiApi\Generator\AnonymousGenerator
 */
class AnonymousGeneratorTest extends \PHPUnit_Framework_TestCase {

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
