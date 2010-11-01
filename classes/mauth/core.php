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
	 * @var 	MAuth 	The current instances.
	 */
	protected static $instances = array();
	
	/**
	 * @var 	Config 	The config file associated with MAuth
	 */
	protected static $config = false;
	
	/**
	 * @var 	Model_User 	The current user
	 */
	protected $user = false;
	
	/**
	 * @var 	string 		The current instance name
	 */
	protected $name = 'default';
	
	
	/**
	 * Creates an instance of the MAuth object
	 *
	 * @param 	string 	The name of the instance.
	 * @return 	MAuth
	 */
	public static function instance($name = 'default')
	{
		if(!array_key_exists($name, self::$instances))
		{
			self::$instances[$name] = new MAuth($name);
		}
		
		return self::$instances[$name];
	}
	
	/**
	 * Builds up a new MAuth
	 *
	 * @return 	MAuth
	 */
	protected function __construct()
	{
		self::$config = kohana::config('mauth');
		
		$cookie_key = self::$config->cookie_prefix .  '_user';
		if((bool)cookie::get($cookie_key, false))
		{
			$this->user = Model_User::find_by_id(cookie::get($cookie_key));
		}
	}
	
	/**
	 * Logs a user into the system
	 *
	 * @param 	string 	username
	 * @param 	string 	password
	 * @return 	bool
	 */
	public function login($username, $password)
	{
		$user = Model_User::find_by_username($username, self::$config);
		if($user)
		{
			$password_hash = $this->hash_password($password, $user->email);
			
			if($password_hash === $user->password)
			{
				// Set this user_id into the cookie:
				cookie::set($this->make_cookie_key(), $user->id);
				$this->user = $user;
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Log a user out of the system
	 *
	 * @return 	bool
	 */
	public function logout()
	{
		cookie::set($this->make_cookie_key(), '');
		$this->user = false;
		return !(bool)$this->user;
	}
	
	/**
	 * Whether or not someone is logged in or not.
	 *
	 * @return 	bool
	 */
	public function logged_in()
	{
		return (bool)$this->user;
	}
	
	/**
	 * Returns the currently active user
	 *
	 * @return 	Model_User
	 */
	public function get_user()
	{
		return $this->user;
	}
	
	/**
	 * Hashes up a password
	 *
	 * @param 	string 	Password to hash
	 * @param 	string 	Salt to add to the password
	 * @return 	string
	 */
	public function hash_password($password, $salt)
	{
		$pw 	= sha1($password);
		$salt 	= sha1($salt);
		return $pw;
	}
	
	
	/**
	 * Makes the cookie key for this instance.
	 *
	 * @return string
	 */
	protected function make_cookie_key()
	{
		return $cookie_key = self::$config->cookie_prefix .  '_' . $this->name;
	}
	
}