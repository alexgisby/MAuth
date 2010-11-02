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
	 * @var 	Config 	The config file associated with this instance
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
	protected function __construct($name)
	{
		$this->name 	= $name;
		self::$config 	= kohana::config('mauth');
		
		$cookie_key = $this->make_cookie_key();
		if((bool)cookie::get($cookie_key, false))
		{
			$this->user = Model_User::mauth_find_by_id(cookie::get($cookie_key));
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
		$user = Model_User::mauth_find_by_username($username, $this->read_config('login_username'));
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
		return $cookie_key = $this->read_config('cookie_prefix') .  '_' . $this->name;
	}
	
	
	/**
	 * Reads a value from the cascading config
	 *
	 * @param 	string 	Config key
	 * @param 	mixed 	Default value if not found
	 */
	protected function read_config($key, $default = false)
	{
		// Ok, first things first, see if this is a top-level config:
		if(isset(self::$config->$key))
		{
			return self::$config->$key;
		}
		
		// Now, if there's a name specified for this instance, load it;
		if($this->name != 'default')
		{
			if(array_key_exists($this->name, self::$config) && array_key_exists($key, self::$config[$this->name]))
			{
				return self::$config[$this->name][$key];
			}
		}
		
		// Check the default for a value:
		if(array_key_exists('default', self::$config) && array_key_exists($key, self::$config['default']))
		{
			return self::$config['default'][$key];
		}
		
		// Nope, nothing, return the default:
		return $default;
	}
	
	
}