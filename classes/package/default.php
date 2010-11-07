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
	public function moderate($user)
	{
		return true;
	}
}