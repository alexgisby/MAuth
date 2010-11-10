<?php defined('SYSPATH') or die('No direct script access.');

/**
 * See the notes on Configuration for info about how this works since it's a bti different to normal config files.
 */

return array(
	
	/**
	 * The Cookie prefix for MAuth
	 * @default 	mauth
	 */
	'cookie_prefix'		=> 'auth',
	
	/**
	 * The directory within /cache to write to. MAuth will create it if it doesn't exist
	 * @default 	mauth
	 */
	'cache_dir'			=> 'mauth',
	
	/**
	 * If no other config value is found, this will be used.
	 */
	'default' => array(
		
		/**
		 * The users model is the model that contains your user-type objects (could be customers, monkies, whatever)
		 */
		'user_model'		=> 'User',
	
		/**
		 * The column name of the 'username' field for login (probably either username or email, the way you want to identify your user).
		 * @default		'username'
		 */
		'login_username'	=> 'username',
	
		/**
		 * Salt pattern; same as Kohana Auth, define numbers between 1 and 40 to add to the string.
		 */
		'salt_pattern'		=> array(1, 11, 15, 17, 33, 36, 39),
	),
	
	
	/**
	 * A specific, other config setup. An example for development purposes.
	 */
	'admin'	=> array(
		
		//'user_model'		=> 'Admin',
	),
	
);