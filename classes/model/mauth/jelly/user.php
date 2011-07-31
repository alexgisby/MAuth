<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The User Model for MAuth
 *
 * @package 	MAuth
 * @category  	Models
 * @author 		Alex Gisby
 */

if(version_compare(kohana::VERSION, '3.1.0', '>='))
{
	class Model_MAuth_Jelly_User extends Jelly_Model implements Interface_MAuth_Model_User
	{
		/**
		 * Set up the model.
		 */
		public static function initialize(Jelly_Meta $meta)
		{
			$meta
				->fields(array(

					'id' => Jelly::field('primary'),

					'username' => Jelly::field('string', array(
							'unique'	=> true,
							'rules'		=> array(
								array('not_empty'),
							),
						)),

					'email' => Jelly::field('email', array(
							'unique'	=> true,
							'rules'		=> array(
								array('not_empty'),
							),
						)),

					'password'	=> Jelly::field('password', array(
							'hash_with'	=> array(MAuth::instance(), 'hash_password'),
							'rules'		=> array(
								array('not_empty'),
							)
						)),

					'logins'		=> Jelly::field('integer', array(
							'default'	=> 0,
						)),

					'last_login'	=> Jelly::field('timestamp', array(
							'format'	=> 'Y-m-d H:i:s',
						)),

					'created'	=> Jelly::field('timestamp', array(
						'format'			=> 'Y-m-d H:i:s',
						'auto_now_create'	=> true,
					)),

					'deleted'	=> Jelly::field('timestamp', array(
						'format'			=> 'Y-m-d H:i:s',
					)),

					'mauth_instance_name'	=> Jelly::field('string', array(
							'in_db'		=> false,
							'default'	=> 'default',
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
			$user = Jelly::query('user')->where($field, '=', $username)->limit(1)->select();
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
			$user = Jelly::query('User')->where(':primary_key', '=', $id)->limit(1)->load();
			return ($user->loaded())? $user : false;
		}
	
	
		/**
		 * Called when the user logs in, allows the model to update it's last-login time and the logins count.
		 *
		 * @return 	this
		 */
		public function mauth_event_logged_in()
		{
			return $this->set(array(
					'logins' 		=> $this->logins + 1,
					'last_login'	=> time(),
				))
				->save();
		}
	
		/**
		 * Return the table name
		 *
		 * @return 	string
		 */
		public function mauth_table_name()
		{
			return $this->meta()->table();
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
}
else
{
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
					'password'		=> new Field_Password(array(
							'hash_with'	=> array(MAuth::instance(), 'hash_password'),
						)),
					'logins'		=> new Field_Integer(array(
							'default'	=> 0,
						)),
					'last_login'	=> new Field_Timestamp(array(
							'format'	=> 'Y-m-d H:i:s',
						)),
					
					'mauth_instance_name'	=> new Field_String(array(
							'in_db'		=> false,
							'default'	=> 'default',
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
	
	
		/**
		 * Called when the user logs in, allows the model to update it's last-login time and the logins count.
		 *
		 * @return 	this
		 */
		public function mauth_event_logged_in()
		{
			return $this->set(array(
					'logins' 		=> $this->logins + 1,
					'last_login'	=> time(),
				))
				->save();
		}
	
		/**
		 * Return the table name
		 *
		 * @return 	string
		 */
		public function mauth_table_name()
		{
			return $this->meta()->table();
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
}