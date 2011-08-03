# Understanding Precedence

Precedence is the way that MAuth works out which package should have priority over another one if they're both attached to the same user.

## An example

Say we have two Packages, both attached to the user "Alex".

**Normal**

	class Package_Normal extends MAuth_Package
	{
		public function init()
		{
			$this->precedence(1)
					->add_rule('post', true);
			
			return parent::init();
		}
	}
	
**Locked**

	class Package_Locked extends MAuth_Package
	{
		public function init()
		{
			$this->precedence(10)
					->add_rule('post', false);
			
			return parent::init();
		}
	}
	
Notice how the Locked package has a precedence of 10, whereas Normal has 1. This means when we call <code>can()</code>, the Locked package will take over and refuse posting rights:

	$user = Model_User::find_by_name('Alex');
	if(!$user->can('post'))
	{
		echo 'Seems like Alex has had his posting rights removed';
	}
	
This makes it super easy to have a naughty-step type package set up to create limited accounts.