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

For more info on how to configure the instances, see [Configuration](mauth.config)