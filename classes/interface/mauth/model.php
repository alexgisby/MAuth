<?php defined('SYSPATH') or die('No direct script access.');

/**
 * MAuth's Model interface, defines what a Model must be able to do.
 *
 * @package 	MAuth
 * @category  	Interfaces
 * @author 		Alex Gisby
 */

interface Interface_MAuth_Model
{
	
	/**
	 * Find an item by it's ID:
	 *
	 * @param 	int 	ID to search for
	 * @return 	Model
	 */
	public static function mauth_find_by_id($id);
	
}