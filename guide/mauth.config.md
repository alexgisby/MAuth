# Configuring MAuth

MAuth uses a funky cascading config system which means that each instance can roll differently to the others.

## Cascading

Basically, MAuth does the following when trying to work out a config value:

**1** - Look for an array key in the config with the same name as the instance name, then see if it contains the config key.<br />
**2** - If it doesn't, look in the config for the default array and read that<br />
**3** - Panic. Not really, if it can't find anything here, it just returns false.<br />

## Global values

### cookie_prefix

Sets the prefix on the cookie name.

### cache_dir

The directory to use within APPPATH/cache for cacheing.

## Instance Specific

### user_model

Each instance can use a different model to represent it's user-type object. So, this could be Customer, or Monkey instead. Table names and such will be taken from this model.

### login_username

The column name to use when logging in. Default is 'username' but you could always change it to 'email' or 'refno' or whatever you want to use.

### salt_pattern

As a security precaution, we salt the passwords we save. This defines the points to enter the salt.

[!!] Your password column on the database table should have a length of 40 + the count() of this array. So if you have 5 numbers here, your database column needs to be of length 45.

### cache

Boolean to say whether you want us to cache the permissions for users. Doing this will save a few database calls on pages. Use more for database-load optimisation than speed since it stores in the filesystem.

[!!] If you change any of your package class files, you'll need to clear down the cache directory to rebuild them. However if you edit the package via the editing functions you don't need to, MAuth will rebuild it for you. I'll probably add a feature to check for changes at some point to stop this.