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
	 * @var 	int 	The precedence or importance of this package
	 */
	public $precedence = 1;
	
	/**
	 * @var 	string 	Package Name
	 */
	protected $name;
	

	/**
	 * @return this
	 */
	public function __construct()
	{
		$this->init();
	}
	
	
	/**
	 * Initializes the Package, adding rules and callbacks
	 * [!!] MUST call parent::init();! Otherwise puppies will die!
	 * 
	 * @return this
	 */
	protected function init()
	{
		if(!isset($this->name))
		{
			echo 'Setting package name';
			$this->name = str_replace('Package_', '', get_class($this));
		}
		
		return $this;
	}
	
	
	/**
	 * Gets or sets the name of this package
	 *
	 * @param 	string 	Optional, set the name
	 * @return 	string
	 */
	public function name($name = false)
	{
		if($name === false)
		{
			return $this->name;
		}
		
		$this->name = $name;
		return $this;
	}
	
	
	/**
	 * Adds a rule (simple yes-no response) to a package
	 *
	 * @param 	string 	action
	 * @param 	bool 	Yes or No for users of this package
	 * @return 	this
	 */
	protected function add_rule($action, $response)
	{
		$this->rules[$action] = $response;
		return $this;
	}
	
	
	/**
	 * Adds a whole slew of rules from an array, the key being the action and the value being the response
	 *
	 * @param 	array 	Rules
	 * @return 	this
	 */
	protected function add_rules(array $rules)
	{
		foreach($rules as $action => $response)
		{
			$this->add_rule($action, $response);
		}
		
		return $this;
	}
	
	
	/**
	 * Returns the array of rules
	 *
	 * @return 	array
	 */
	public function rules()
	{
		return $this->rules;
	}
	
	
	/**
	 * Adds a callback to this package
	 *
	 * @param 	string 	Action
	 * @param 	string 	Callback static function
	 * @return 	this
	 */
	protected function add_callback($action, $function)
	{
		$this->callbacks[$action] = $function;
		return $this;
	}
	
	
	/**
	 * Adds an array of callbacks, key being the action, value being the function
	 *
	 * @param 	array 	Callbacks
	 * @return 	this
	 */
	protected function add_callbacks(array $callbacks)
	{
		foreach($callbacks as $action => $function)
		{
			$this->add_callback($action, $function);
		}
		
		return $this;
	}
	
	/**
	 * Returns all the callbacks
	 *
	 * @return 	array
	 */
	public function callbacks()
	{
		return $this->callbacks;
	}
	
	/**
	 * Gets or sets the precedence of this package
	 *
	 * @param 	int 	Sets the precedence level
	 * @return 	this|int
	 */
	public function precedence($level = false)
	{
		if($level === false)
		{
			return $this->precedence;
		}
		
		$this->precedence = $level;
		return $this;
	}
	
}