# Asking for Permission

To ask if a user can do something, be it a rule or a callback, all goes through the same function: <code>can()</code>. The <code>can()</code> function appears on both the MAuth class and the User-Type objects.

## Rule example

	$mauth = MAuth::instance();
	if($mauth->can('post'))
	{
		echo 'The current site user can post';
	}
	
	$user = Model_User::find_by_name('Alex');
	if($user->can('post'))
	{
		echo 'Alex is able to post';
	}
	
## Callback example

	$comment = Model_Topic::find();
	$mauth = MAuth::instance();
	
	if($mauth->can('moderate', $comment))
	{
		echo 'The current user is allowed to moderate this comment';
	}
	
	$user = Model_User::find_by_name('Alex');
	if($user->can('moderate', $comment))
	{
		echo 'Alex can moderate this comment';
	}
