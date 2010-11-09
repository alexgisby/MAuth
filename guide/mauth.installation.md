# Installation / Usage

MAuth is a module like any other Kohana 3 module, and has no dependancies other than some form of ORM being in play.

## Installation

Two main ways, download the master branch from GitHub and place in your modules folder, or install it as a submodule using Git:

	git submodule add git@github.com:alexgisby/MAuth.git modules/mauth
	
Be sure to enable MAuth in your bootstrap.php file.

You'll also need to run the [SQL Schema](mauth.schema) file to create the database structure. If there's enough demand, I might even write some converter scripts from Auth to MAuth.

## Basic Usage

MAuth is designed to be simple to understand if you've tinkered with the standard Kohana Auth module.

	$mauth = MAuth::instance();
	
	// Try and log them in:
	$mauth->login('Alex', 'mypassword');
	
	// Check if they're logged in:
	if($mauth->logged_in())
	{
		echo 'Hello, you are logged in!';
	}
	
	// Get the logged in user:
	$user = $mauth->get_user();
	
	// Log them out:
	$mauth->logout();
	
Simple right? MAuth takes care of all the nitty-gritty and you can get on writing your app.

## Tip of the iceberg

All of this looks, well, the same as Kohana Auth. MAuth is so much more than that though, check out the Features section for more detailed info on how MAuth is different.