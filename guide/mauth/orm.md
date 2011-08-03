# Choose your ORM

MAuth is ORM agnostic, meaning you can use any ORM you like with it. We currently have drivers for Jelly (my personal preference) and Kohana 3's ORM. If you use something different, the drivers are super easy to write, and if there's enough demand, I'll write one to include in the repo.

## How to select an ORM

ORM selection is all about which class Model_User (the only model in MAuth) extends.

### Using Jelly

model/user.php
	
	class Model_User extends Model_MAuth_Jelly_User
	
### Using Kohana ORM

model/user.php

	class Model_User extends Model_MAuth_ORM_User
	
## ORM Drivers

The ORM driver must implement the Interface_MAuth_Model_User interface. This allows MAuth to talk to it. See below for the code to the Jelly driver to see how simple it is:

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
	
Most of the drivers are optional aliases, the only things required are the functions prefixed with mauth_ and the can() function. Effectively, just copy-paste the contents of this file and rewrite accordingly.