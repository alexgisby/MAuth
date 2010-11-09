# Multiple Instances

MAuth allows you to have 1, 2, 8, or 2000 separate login systems in your webapp.

The classic setup being, you want Administrators to be able to log into the backend (looking at the 'users' table), but then have customers log in using the 'customers' table. MAuth can handle all of this seamlessly.

## But how?

Like this:

	// Set up the admin MAuth:
	$mauth_admin = MAuth::instance('admin');
	
	// Log in an admin:
	$mauth_admin->login('Alex', 'mypassword');
	
	// Now do the customer MAuth:
	$mauth_customer = MAuth::instance('customer');
	
	// If I check logged in, nothing will be there since they're logged into Admin and not Customer:
	if($mauth_customer->logged_in())
	{
		echo "This won't show, because you're logged into Admin and not customer";
	}
	
Everything is kept separate so you can be sure of your walled gardens.

## How to set up:

MAuth uses a cascading-config setup to manage how to deal with each instance. An example config is below:

	return array(

		/**
		 * The Cookie prefix for MAuth
		 * @default 	mauth
		 */
		'cookie_prefix'		=> 'auth',

		/**
		 * If no other config value is found, this will be used.
		 */
		'default' => array(

			/**
			 * The users model is the model that contains your user-type objects (could be customers, monkies, whatever)
			 */
			'user_model'		=> 'User',

			/**
			 * The column name of the 'username' field for login (probably either username or email, the way you want to identify your user).
			 * @default		'username'
			 */
			'login_username'	=> 'username',

			/**
			 * Salt pattern; same as Kohana Auth, define numbers between 1 and 40 to add to the string.
			 */
			'salt_pattern'		=> array(1, 11, 15, 17, 33, 36, 39),
		),


		/**
		 * A specific, other config setup. An example for development purposes.
		 */
		'admin'	=> array(

			'user_model'		=> 'Admin',
		),

	);
	
Some important things to notice here:

### User-type models

Whenever you see the word User in MAuth, it doesn't necessary mean rows in the users table. It's an object which represents something which can interact with the system, so, an Admin, or Customer etc.

[!!] To make ORM Agnosticism work, MAuth uses the principle of Fat-Models to provide a consistent API. It's therefore vital your User-Type models implement the Interface_MAuth_Model_User interface. More in the [Interfaces](mauth.interfaces) section.

### Cascading

As the comments mention, if you don't specify something in another instance (say 'admin'), the config from 'default' will be used instead. If you don't specify a config array at all for an instance, the entire default array will be used.

This is extremely powerful, as it allows you to set different models for User-Type objects for each instance (and in turn, different tables), as well as different salt-patterns for passwords. Keeping things secure.

### login_username

One of my gripes with Kohana's Auth is that it uses 'username' to find users when logging in, when most clients want it to be 'email'.

'login_username' allows you to specify which member (column usually) of the 'user_model' you want to be the login key.