<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Packages are MAuth's way of defining permission sets
 *
 * @package 	MAuth
 * @category  	Packages
 * @author 		Alex Gisby
 */

abstract class MAuth_Package_Core
{
	/**
	 * @var 	array 	The static rules of this package
	 */
	protected $rules = array();
	
	/**
	 * @var 	array 	The callbacks of this package
	 */
	protected $callbacks = array();
	
	/**
	 * Sets up the permissions for this set.
	 *
	 * @return 	void
	 */
	//abstract public static function initialize();
	
	
	/**
	 * Adds rules to this package
	 *
	 * @param 	array 	Rules to add
	 * @return 	this
	 */
	public function add_rules(array $rules)
	{
		$this->rules = array_merge($this->rules, $rules);
	}
	
	
}