<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Default package for development
 *
 * @package 	MAuth
 * @category  	Packages
 * @author 		Alex Gisby
 */

class Package_Default
{
	public $precedence = 1;
	public $rules = array(
	
		'post'	=> true,
		
	);
	
	public $callbacks = array(
	
		'moderate'	=> 'moderate'
		
	);
	
	
	/**
	 * Moderate callback
	 */
	public static function moderate($user, $topic)
	{
		$args = func_get_args();
		//echo '<br /><b>Running the Moderate Callback with params:</b><br />';
		//echo '<pre>' . print_r($args, true) . '</pre>';
		echo '<br /><br />User ID: ' . $user->id . '<br />Topic: ' . $topic . '<br /><br />';
		return true;
	}
}