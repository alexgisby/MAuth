<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The interface that all User Models should adhere to.
 *
 * @package 	MAuth
 * @category  	Interfaces
 * @author 		Alex Gisby
 */

interface Interface_MAuth_Model_User extends Interface_MAuth_Model
{
	/**
	 * Finds a user by their username field.
	 *
	 * @param 	string 	The username to search for
	 * @param 	string 	The username field name.
	 * @return 	Model_User
	 */
	public static function mauth_find_by_username($username, $field);
	
	/**
	 * Called when the user logs in, allows the model to update it's last-login time and the logins count.
	 *
	 * @return 	this
	 */
	public function mauth_event_logged_in();
	
	/**
	 * Return the name of the table this object works on
	 *
	 * @return 	string
	 */
	public function mauth_table_name();
	
	
	/**
	 * A shortcut for the Auth::can function, way of checking permissions on a user that isn't logged in:
	 *
	 * @param 	string 	action
	 * @param 	...		Any additional params for the action
	 * @return 	bool
	 */
	public function can($action);
	
	/**
	 * Alias for checking if a user has a package attached to them or not
	 *
	 * @param 	string 	Package name
	 * @return 	bool
	 */
	public function has_package($name);
	
	
}