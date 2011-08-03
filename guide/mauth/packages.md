# Packages

Packages are the very core of permissions in MAuth.

Packages are classes containing rules and callbacks used to decide if a user can do something.

## Creating Packages

As an example, let's create a basic package containing a rule and a callback:

File: application/classes/package/normal.php

	class Package_Normal extends MAuth_Package
	{
		/**
		 * Set up the rules and callbacks on a package
		 */
		public function init()
		{
			$this->precedence(1)
					->add_rule('post', true)
					->add_callback('moderate', 'can_user_moderate');
					
			return parent::init();
		}
		
		
		/**
		 * The 'moderate' action's callback
		 *
		 * @param 	Model 	The user model asking for permission
		 * @param 	Comment	The comment they want to moderate
		 * @return 	bool
		 */
		public function can_user_moderate($user, $comment)
		{
			return ($user->id == $comment->author->id);
		}
		
	}
	
### Precedence

Precedence is the way that MAuth organises the heirachy of Packages. See [Understanding Precedence](mauth.packages.precedence) for more.
	
### Rules

Rules are simple yes-no responses to an action. Use these for very clear cut decisions in your code.

### Callbacks

Sometimes you need more info about the action to work out if they can do something or not. Use callbacks for this.

The callback must be a function on the current package. The first parameter is **always** the User-type object which is requesting the action. Any other parameters passed to the can() function (see below) will be passed along to the callback.


## Assigning Packages to Users

Packages can be added to a user-type object in two ways; either using mauth directly, or by using the drivers themselves.

### Assigning via driver

	$user = Model_User::find();	// Psuedo-code for finding a user instance
	$user->add_package('normal');
	
### Assign via Mauth

	$user = Model_User::find();
	$mauth = MAuth::instance();
	$mauth->add_package_for_user($user, 'normal');
	
## Removing Packages from Users

Packages are removed in the same way:

### Via Driver

	$user = Model_User::find();
	$user->remove_package('normal');
	
### Via MAuth

	$user = Model_User::find();
	$mauth = MAuth::instance();
	$mauth->remove_package_for_user($user, 'normal');
	