# Editing Packages

Another powerful feature of MAuth is that you can edit packages on a per-user basis. This allows for a very granular permissions setup.

To edit a package, all you need do is pass in an array of rules to change.

[!!] Only rules can currently be changed in this way. Callbacks are maintained.

## Editing packages

As always you can do this in two ways, via MAuth or the driver. Simply tell MAuth which package you want to edit on the user, and pass an array of <code>rule => response</code> for the changes.

### Via Driver

	$user = Model_User::find();
	$user->edit_package('normal', array('post' => false));
	
### Via MAuth

	$user = Model_User::find();
	$mauth = MAuth::instance();
	$mauth->edit_package_for_user($user, 'normal', array('post' => false));
	
All changes take effect immediately.


## Resetting packages

If you decide you don't want any extra rules attached to a user, you can reset the package setup.

### Via Driver

	$user = Model_User::find();
	$user->reset_package('normal');
	
### Via MAuth

	$user = Model_User::find();
	$mauth = MAuth::instance();
	$mauth->reset_package_for_user($user, 'normal');