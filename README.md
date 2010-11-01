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