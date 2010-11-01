<?php defined('SYSPATH') or die('No direct script access.');

return array(
	
	/**
	 * The column name of the 'username' field for login (probably either username or email, the way you want to identify your user).
	 * @default		'username'
	 */
	'login_username'	=> 'username',
	
	/**
	 * The Cookie prefix for MAuth
	 * @default 	mauth
	 */
	'cookie_prefix'		=> 'mauth',
	
	
	/**
	 * Salt pattern; same as Kohana Auth, define numbers between 1 and 40 to add to the string.
	 */
	'salt_pattern'		=> array(1, 11, 15, 17, 33, 36, 39),
	
);