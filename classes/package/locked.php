<?php defined('SYSPATH') or die('No direct script access.');

/**
 * A locked down package that has a higher precedence than the default.
 *
 * @package 	MAuth
 * @category  	Packages
 * @author 		Alex Gisby
 */

class Package_Locked extends MAuth_Package
{
	public function init()
	{
		$this->precedence(5)
				->add_rule('post', false);
				
		return parent::init();
	}
	
}