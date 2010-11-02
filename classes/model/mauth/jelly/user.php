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
	 * @param 	string	Field that represents the 'username'
	 * @return 	Model_User|false 	Will return false if it can't find a user.
	 */
	public static function mauth_find_by_username($username, $field)
	{
		$user = Jelly::select('user')->where($field, '=', $username)->load();
		return ($user->loaded())? $user : false;
	}
	
	
	/**
	 * Finds a user by their ID
	 *
	 * @param 	int 	ID to search for
	 * @return 	Model_User
	 */
	public static function mauth_find_by_id($id)
	{
		$user = Jelly::select('User')->where(':primary_key', '=', $id)->load();
		return ($user->loaded())? $user : false;
	}
	
}