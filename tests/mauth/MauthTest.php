<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Testing the basic MAuth file
 *
 * @group mauth
 */
class MauthTest extends Unittest_TestCase
{
	public function testTesting()
	{
		$this->assertEquals('Alex', 'Alex');
	}
}