<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\UsageException;

/**
 * @covers Mediawiki\Api\UsageException
 */
class UsageExceptionTest extends \PHPUnit_Framework_TestCase {

	public function testUsageExceptionWithNoParams() {
		$e = new UsageException();
		$this->assertEquals( '', $e->getMessage() );
		$this->assertEquals( '', $e->getApiCode() );
	}

	public function testUsageExceptionWithParams() {
		$e = new UsageException( 'imacode', 'imamsg' );
		$this->assertEquals( 'imacode', $e->getApiCode() );
		$this->assertEquals( 'imamsg', $e->getMessage() );
	}

} 