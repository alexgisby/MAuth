<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The User Model for MAuth (Kohana ORM Flavour)
 *
 * @package 	MAuth
 * @category  	Models
 * @author 		Alex Gisby
 */

class Model_MAuth_ORM_User extends ORM implements Interface_MAuth_Model_User
{
	protected $mauth_instance_name = 'default';
	
		
	/**
	 * Saves the current object. Performs the password hashing too if needs be. (mostly copied from the Auth/ORM driver)
	 *
	 * @return  ORM
	 */
	public function save()
	{
		if (array_key_exists('password', $this->_changed))
		{
			$this->_object['password'] = MAuth::instance($this->mauth_instance_name)->hash_password($this->_object['password']);
		}

		return parent::save();
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
		$user = ORM::factory('User')->where($field, '=', $username)->find();
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
		$user = ORM::factory('User');
		$user = $user->where($user->_primary_key, '=', $id)->find();
		return ($user->loaded())? $user : false;
	}
	
	
	/**
	 * Called when the user logs in, allows the model to update it's last-login time and the logins count.
	 *
	 * @return 	this
	 */
	public function mauth_event_logged_in()
	{
		$this->logins 		= $this->logins + 1;
		$this->last_login	= date('Y-m-d H:i:s', time());
		return $this->save();
	}
	
	/**
	 * Return the table name
	 *
	 * @return 	string
	 */
	public function mauth_table_name()
	{
		//return $this->meta()->table();
		return $this->_table_name;
	}
	
	
	/**
	 * A shortcut for the Auth::can function, way of checking permissions on a user that isn't logged in:
	 *
	 * @param 	string 	action
	 * @param 	...		Any additional params for the action
	 * @return 	bool
	 */
	public function can($action)
	{
		$src_args = func_get_args();
		$args = array_merge(array($this), $src_args);
		return call_user_func_array(array(MAuth::instance($this->mauth_instance_name), 'user_can'), $args);
	}
	
	
	/**
	 * Alias for checking if a user has a package attached to them or not
	 *
	 * @param 	string 	Package name
	 * @return 	bool
	 */
	public function has_package($name)
	{
		return MAuth::instance($this->mauth_instance_name)->user_has_package($this, $name);
	}
	
	
	/**
	 * Alias for adding a package to a user.
	 *
	 * @param 	string 	Package short-name
	 * @return 	this
	 */
	public function add_package($name)
	{
		MAuth::instance($this->mauth_instance_name)->add_package_for_user($this, $name);
		return $this;
	}
	
	
	/**
	 * Alias for removing packages from a user
	 *
	 * @param 	string 	Package short-name
	 * @return 	this
	 */
	public function remove_package($name)
	{
		MAuth::instance($this->mauth_instance_name)->remove_package_for_user($this, $name);
		return $this;
	}
	
	
	/**
	 * Alias for editing a package on this user
	 *
	 * @param 	string	Package name
	 * @param 	array 	Changes
	 * @return 	bool
	 */
	public function edit_package($name, array $changes = array())
	{
		return MAuth::instance($this->mauth_instance_name)->edit_package_for_user($this, $name, $changes);
	}
	
	
	/**
	 * Alias for resetting a package
	 *
	 * @param 	string 	package name
	 * @return 	bool
	 */
	public function reset_package($name)
	{
		return MAuth::instance($this->mauth_instance_name)->reset_package_for_user($this, $name);
	}
	
}