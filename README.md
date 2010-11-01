# MAuth

MAuth is a simple but powerful permissions / authentication module for Kohana 3.

## Ideas / Brainstorm

### Sane Ideas

- Database based permissions (JSON encoded probably)
- Default permissions list in config
- Needs a permissions creator (MPackage)
	- Set specific entities of the permissions, if not set, revert to default.
- Multiple Packages per user.
- Packages also stored in the database?
- Can have callback functions for permissions entities.
- ORM agnostic, favours Jelly.
- Simple and secure.

- ? Multiple authentication layers? So You can be logged into the main site, but not a sub-site?

### Insane Ideas

- Alter the schema of the database to reflect the entities?!


## Documentation

More notes really on how it works.

### Choose your ORM

MAuth uses the principle of Fat Model's to allow for any major ORM to interact with it. To choose your ORM,
just choose which classes the Model_User file extends. Default is Jelly, so:

	class Model_User extends Model_MAuth_Jelly_User
	
Seems a bit of a mouthful, but it works.

All 'driver' models should implement the Interface_MAuth_Model interface.