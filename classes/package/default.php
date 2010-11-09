<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Default package for development
 *
 * @package 	MAuth
 * @category  	Packages
 * @author 		Alex Gisby
 */

class Package_Default extends MAuth_Package
{
	/**
	 * Initialize the Package and the parent
	 *
	 * @return this
	 */
	public function init()
	{
		$this->precedence(1)
				->add_rule('post', true)
				->add_callback('moderate', 'moderate');
				
		return parent::init();
	}
	
	
	/**
	 * Moderate callback
	 */
	public static function moderate($user, $topic = '')
	{
		//$args = func_get_args();
		//echo '<br /><b>Running the Moderate Callback with params:</b><br />';
		//echo '<pre>' . print_r($args, true) . '</pre>';
		//echo '<br /><br />User ID: ' . $user->id . '<br />Topic: ' . $topic . '<br /><br />';
		return true;
	}
}