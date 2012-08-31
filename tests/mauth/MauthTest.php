<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Testing the basic MAuth file
 *
 * @group mauth
 */
class MauthTest extends Unittest_TestCase
{
	/**
	 * Tests fetching the default instance of MAuth.
	 */
	public function testFetchingDefaultInstance()
	{
		$instance = MAuth::instance();
		$this->assertTrue($instance instanceof MAuth);
	}
	
	/**
	 * Tests fetching a named instance.
	 */
	public function testFetchingNamedInstance()
	{
		$instance = MAuth::instance('namedInstance');
		$this->assertTrue($instance instanceof MAuth);
		$this->assertEquals('namedInstance', $instance->name());
	}
}