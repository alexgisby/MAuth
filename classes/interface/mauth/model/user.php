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
	 * @param 	Config 	Current config of MAuth
	 * @return 	Model_User
	 */
	public static function find_by_username($username, $config);
}