<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The User Model for MAuth
 *
 * @package 	MAuth
 * @category  	Models
 * @author 		Alex Gisby
 */

class Model_MAuth_Jelly_User extends Jelly_Model implements Interface_MAuth_Model_User
{

	/**
	 * Set up the Model.
	 *
	 * @param 	Jelly_Meta
	 * @return 	void
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta
			->fields(array(
			
				'id' 			=> new Field_Primary,
				'username'		=> new Field_String,
				'email'			=> new Field_Email,
				'password'		=> new Field_Email,
				'logins'		=> new Field_Integer,
				'last_login'	=> new Field_Timestamp(array(
						'format'	=> 'Y-m-d H:i:s',
					)),
				
			));
	}
	
	
	/**
	 * Finds a user by their username
	 *
	 * @param 	string 	Username to search for
	 * @param 	config 	MAuth config
	 * @return 	Model_User
	 */
	public static function find_by_username($username, $config)
	{
		// $i = Jelly::factory('User');
		// 	echo Kohana::debug($i->meta());
		// 	exit();
		return Jelly::select('user')->where($config->login_username, '=', $username)->load();
	}
	
}