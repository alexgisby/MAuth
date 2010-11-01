<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Main MAuth file. Designed to be simple to use and yeah, all that jazz.
 *
 * @package 	MAuth
 * @category  	Core
 * @author 		Alex Gisby
 */

class MAuth_Core
{
	/**
	 * @var 	MAuth 	The current instance.
	 */
	protected static $instance = false;
	
	/**
	 * @var 	Config 	The config file associated with MAuth
	 */
	protected static $config = false;
	
	/**
	 * Creates an instance of the MAuth object
	 *
	 * @return 	MAuth
	 */
	public static function instance()
	{
		if(!self::$instance)
		{
			self::$instance = new MAuth();
		}
		return self::$instance;
	}
	
	/**
	 * Builds up a new MAuth
	 *
	 * @return 	MAuth
	 */
	protected function __construct()
	{
		self::$config = kohana::config('mauth');
	}
	
	/**
	 * Logs a user into the system
	 *
	 * @param 	string 	username
	 * @param 	string 	password
	 * @return 	bool
	 */
	public static function login($username, $password)
	{
		
	}
}