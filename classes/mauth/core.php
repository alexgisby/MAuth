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
	 * @var 	bool 		If we've tried to get the user or not yet
	 */
	protected $load_user_attempted = false;
	
	/**
	 * @var 	array 	Package and user map
	 */
	protected static $packages_map = array();
	
	/**
	 * @var 	array 	Permissions drilled down
	 */
	protected static $permissions = array();
	
	
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
		$this->name 		= $name;
		self::$config 		= kohana::config('mauth');
		$this->user_model 	= $this->read_config('user_model');
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
		$user = call_user_func($this->user_model() . '::mauth_find_by_username', $username, $this->read_config('login_username'));
		if($user)
		{
			if($this->check_password($password, $user))
			{
				// Set this user_id into the cookie:
				cookie::set($this->make_cookie_key(), $user->id);
				$this->user = $user;
				
				// Update the users stats:
				$user->mauth_event_logged_in();
				
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
		cookie::delete($this->make_cookie_key());
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
		$this->attempt_load_user();
		return (bool)$this->user;
	}
	
	/**
	 * Returns the currently active user
	 *
	 * @return 	Model_User
	 */
	public function get_user()
	{
		$this->attempt_load_user();
		return $this->user;
	}
	
	/**
	 * Try and load the current user. Do it here rather than in __construct to keep things lighter.
	 */
	protected function attempt_load_user()
	{
		if(!$this->load_user_attempted)
		{
			$cookie_key = $this->make_cookie_key();
			if((bool)cookie::get($cookie_key, false))
			{
				$this->user = call_user_func($this->user_model() . '::mauth_find_by_id', cookie::get($cookie_key));
			}
		
			$this->load_user_attempted = true;
		}
	}
	
	/**
	 * Hashes up a password
	 *
	 * @param 	string 	Password to hash
	 * @param 	string 	Salt to add to the password
	 * @return 	string
	 */
	public function hash_password($password, $salt = false)
	{
		$pw 		= sha1($password);
		if(!$salt)	$salt = sha1(uniqid(null, true));
		$pattern	= $this->read_config('salt_pattern');
		sort($pattern);
		
		foreach($pattern as $i => $offset)
		{
			$front 	= substr($pw, 0, $offset);
			$tail 	= substr($pw, $offset);
			$pw = $front . $salt[$i] . $tail;
		}
		
		return $pw;
	}
	
	/**
	 * Checks if a given password is the same as one attached to a user:
	 *
	 * @param 	string 			Password
	 * @param 	Model_User 		User-type model to check against
	 * @return 	bool
	 */
	public function check_password($password, $user)
	{
		$unsalted = $this->unsalt_password($user->password);
		return (sha1($password) === $unsalted);
	}
	
	
	/**
	 * Unsalts a password
	 *
	 * @param 	string 	Hashed password to unsalt
	 * @return 	string 	Unsalted hashed password
	 */
	protected function unsalt_password($password)
	{
		$pattern = $this->read_config('salt_pattern');
		sort($pattern);
		$pattern = array_reverse($pattern);
		foreach($pattern as $i => $offset)
		{
			$front 		= substr($password, 0, $offset);
			$tail 		= substr($password, $offset + 1);			
			$password	= $front . $tail;
		}
		
		return $password;
	}
	
	
	/**
	 * Makes the cookie key for this instance.
	 *
	 * @return string
	 */
	protected function make_cookie_key()
	{
		return $this->read_config('cookie_prefix') .  '_' . $this->name;
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
	
	
	/**
	 * Get the name of the 'User-type-objects' model from the config.
	 *
	 * @return 	string
	 */
	protected function user_model()
	{
		return 'Model_' . ucfirst($this->read_config('user_model'));
	}
	
	
	/**
	 * -------------- Permissions Functions ---------------------
	 */
	
	/**
	 * Finds if the current user can do something. Big top-level function.
	 *
	 * @param 	string 	Action they want to try and do
	 * @param 	params	Any extra parameters you want to throw in there.
	 * @return 	bool
	 */
	public function can()
	{
		$src_args = func_get_args();
		$args = array_merge(array($this->get_user()), $src_args);
		return call_user_func_array(array($this, 'user_can'), $args);
	}
	
	/**
	 * Finds if a specified user can or can't do something:
	 *
	 * @param 	Model 	The user-type object to check
	 * @param 	string 	Action
	 * @param 	...		Any extra things to pass along
	 * @return 	bool
	 */
	public function user_can($user, $action)
	{
		if(func_num_args() < 1)
		{
			throw new Exception('mauth::user_can() must contain at least 2 parameters');
		}
		
		// Check if there is a user:
		if(!$user)
		{
			return false;
		}
		
		// Get the args and shift off the first two which we know about:
		$args = func_get_args();
		array_shift($args);
		array_shift($args);
		
		// Build the permissions map thingie if it doesn't exist already:
		$this->build_permissions_for_user($user);
				
		// First see if the action is in the rules:
		if(array_key_exists($action, self::$permissions[$this->name][$user->id]['rules']))
		{
			// Awesome, permission exists, let em have it:
			return self::$permissions[$this->name][$user->id]['rules'][$action];
		}
		
		// Try a callback next:
		if(array_key_exists($action, self::$permissions[$this->name][$user->id]['callbacks']))
		{
			// Get the callback to run and, well, run it!
			list($class, $function) = self::$permissions[$this->name][$user->id]['callbacks'][$action];
			
			$cb_args = array_merge(array($user), $args);
			return call_user_func_array(array($class, $function), $cb_args);
		}
		
		// All else fails, no, no they can't do that:
		return false;
	}
	
	
	/**
	 * See if the current user has a package
	 *
	 * @param 	string 	Package name
	 * @return 	bool
	 */
	public function has_package($name)
	{
		return $this->user_has_package($this->get_user(), $name);
	}
	
	
	/**
	 * See if any given user has a package specified by $name
	 *
	 * @param 	Model 	User type model
	 * @param 	string 	Name to search for
	 * @return 	bool
	 */
	public function user_has_package($user, $name)
	{
		if(!$user)
		{
			return false;
		}
		
		$this->build_permissions_for_user($user);
		return in_array(strtolower($name), self::$permissions[$this->name][$user->id]['packages']);
	}
	
	/**
	 * Get the packages for the current user
	 *
	 * @return 	array
	 */
	public function user_packages()
	{
		return $this->packages_for_user($this->get_user());
	}
	
	
	/**
	 * Return the package names attached a given user:
	 *
	 * @return 	array
	 */
	public function packages_for_user($user)
	{
		if(!(bool)$user)
		{
			return array();
		}
		
		$this->build_permissions_for_user($user);
		return self::$permissions[$this->name][$user->id]['packages'];
	}
	
	
	/**
	 * Edit a package on a user. You can apply specific rules to users who are giving you problems by editing their package.
	 *
	 * @param 	Model 	User-type model
	 * @param 	string	Package Name (short name)
	 * @param 	array 	Changes to the rules.
	 * @return 	this
	 */
	public function edit_package_for_user($user, $package, array $changes)
	{
		if(!$user->has_package($package))
		{
			return false;
		}
		
		$changes_encoded = (!empty($changes))? json_encode($changes) : '';
		
		$sql = 'UPDATE packages_' . $user->mauth_table_name() . '
					SET
						extra = ' . Database::instance()->escape($changes_encoded) . '
					WHERE
						user_id = ' . $user->id .'
					  AND
						package = ' . Database::instance()->escape($package) . '
					LIMIT
						1;';
						
		if(Database::instance()->query(Database::UPDATE, $sql, true))
		{
			$this->rebuild_user_permissions($user);
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Returns a correctly formatted class name for a given short-package name. So 'default' becomes 'Package_Default'. 'super_awesome' becomes 'Package_Super_Awesome'
	 *
	 * @param 	string 	Package short name
	 * @return 	string
	 */
	protected function make_package_class_name($short)
	{
		$parts = explode('_', $short);
		foreach($parts as &$part)
		{
			$part = ucfirst($part);
		}
		
		return 'Package_' . implode('_', $parts);
	}
	
	
	/**
	 * Builds up the permissions for a particular user
	 *
	 * @param 	Model 	User to build for
	 * @return 	void
	 */
	protected function build_permissions_for_user($user)
	{
		if(!isset(self::$permissions[$this->name][$user->id]))
		{
			// Find all the permissions that they can have:
			$packages = array();
			
			// Try and find the permissions in the cache before bothering the database:
			if($cache_contents = $this->read_cache_for_user($user))
			{
				echo Kohana::debug($cache_contents);
				// 				exit();
				
				$extras = array();
				foreach($cache_contents['packages'] as $pkg_name)
				{
					$pkg_name = $this->make_package_class_name($pkg_name);
					
					if(array_key_exists($pkg_name, $cache_contents['extras']))
					{
						$local_extras = $cache_contents['extras'][$pkg_name];
						echo Kohana::debug($local_extras);
					}
					else
					{
						$local_extras = array();
					}
					
					$packages[] = new $pkg_name($local_extras);
					$extras[$pkg_name] = $local_extras;
				}
				
				// foreach($cache_packages as $pkg_name)
				// 				{
				// 					$pkg_name = $this->make_package_class_name($pkg_name);
				// 					$packages[] = new $pkg_name();
				// 				}
			}
			else
			{
				$sql = 'SELECT package, extra FROM packages_' . $user->mauth_table_name() . ' WHERE user_id = ' . $user->id;
				$res = Database::instance()->query(Database::SELECT, $sql, false);
			
				foreach($res as $row)
				{
					$pkg_name = $this->make_package_class_name($row['package']);
					//echo 'Row: ' . $row['extra'] . '<br />';
					$exceptions = ($row['extra'] != '')? json_decode($row['extra']) : array();
					$packages[] = new $pkg_name($exceptions);
					$extras[$pkg_name] = $exceptions;
				}
			}
			
			// Sort them as lowest precedence first:
			$packages 			= mauth_arr::order_by_member($packages, 'precedence');
			$package_names		= array();
			$rules 				= array();
			$callbacks 			= array();
			foreach($packages as $package)
			{
				foreach($package->rules() as $rule => $value)
				{
					$rules[$rule] = $value;
				}

				foreach($package->callbacks() as $name => $callback)
				{
					$callbacks[$name] = array(get_class($package), $callback);
				}
				
				$package_names[] = strtolower($package->name());
			}
			
			self::$permissions[$this->name][$user->id]['packages']		= $package_names;
			self::$permissions[$this->name][$user->id]['rules'] 		= $rules;
			self::$permissions[$this->name][$user->id]['callbacks']		= $callbacks;
			self::$permissions[$this->name][$user->id]['extras']		= $extras;
			
			// Write to the cache:
			$this->cache_permissions_for_user($user);
		}
		
	}
	
	
	/**
	 * Forces MAuth to rebuild a users permissions next time around. Used when adding or removing packages.
	 *
	 * @param 	Model 	User we're talking about
	 * @return 	this
	 */
	public function rebuild_user_permissions($user)
	{
		if(isset(self::$permissions[$this->name][$user->id]))
		{
			unset(self::$permissions[$this->name][$user->id]);
			
			// Remove the cache file too:
			@unlink($this->cache_dir() . '/' . $this->cache_filename($user));
		}
		
		return $this;
	}
	
	
	/**
	 * ------------------- Caching permissions -------------------------------
	 */
	
	/**
	 * Cache the permissions for fasterness.
	 *
	 * @param 	array 	The permissions to cache
	 * @return 	bool
	 */
	protected function cache_permissions_for_user($user)
	{
		if($this->read_config('cache'))
		{
			$this->prepare_cache();
		
			// Generate the filename:
			$filename = $this->cache_filename($user); //sha1($this->name . '-' . $user->id) . '.txt';
		
			// Encode and save the file:
			$encoded = json_encode(array(
						'packages' 	=> self::$permissions[$this->name][$user->id]['packages'], 
						'extras'	=> self::$permissions[$this->name][$user->id]['extras'],
			));
			
			echo '<hr />Encoded: ' . $encoded . '<hr />';
			//exit();
			
			return file_put_contents($this->cache_dir() . '/' . $this->cache_filename($user), $encoded);
		}
		
		return true;
	}
	
	
	/**
	 * Reads the cache for a user
	 *
	 * @param 	Model 	User-type model to look for
	 * @return 	array|bool
	 */
	protected function read_cache_for_user($user)
	{
		if($this->read_config('cache'))
		{
			$filename = $this->cache_dir() . '/' . $this->cache_filename($user);
		
			if(file_exists($filename))
			{
				$contents 	= file_get_contents($filename);
				$decoded 	= json_decode($contents);
				
				$packages 	= (array)$decoded->packages;
				$extras 	= (array)$decoded->extras;
				
				// Unencode the extras array:
				// $extras = array();
				// 				foreach($decoded->extras as $package => $extra)
				// 				{
				// 					
				// 					//$extras[$package] = (array)json_decode($extra);
				// 				}
				
				return array('packages' => $packages, 'extras' => $extras);
			}
		}
		
		return false;
	}
	
	
	/**
	 * Prepares the cache for our arrival
	 */
	protected function prepare_cache()
	{
		$dir = $this->cache_dir();
		
		@mkdir($dir);
		@chmod($dir, 0777);
		
		return true;
	}
	
	
	/**
	 * Gets the path to the cache directory
	 *
	 * @return 	string
	 */
	protected function cache_dir()
	{
		return kohana::$cache_dir . '/' . $this->read_config('cache_dir');
	}
	
	
	/**
	 * Returns the filename of a cache entry for a given user:
	 *
	 * @param 	Model 	User-type model
	 * @return  string
	 */
	protected function cache_filename($user)
	{
		return sha1($this->name . '-' . $user->id) . '.txt';
	}
	
}