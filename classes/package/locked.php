<?php defined('SYSPATH') or die('No direct script access.');

/**
 * A locked down package that has a higher precedence than the default.
 *
 * @package 	MAuth
 * @category  	Packages
 * @author 		Alex Gisby
 */

class Package_Locked
{
	public $precedence = 5;
	public $rules = array(
	
		'post'	=> false,
		
	);
}