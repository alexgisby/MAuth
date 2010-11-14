# SQL Schema

This is the SQL code you need to run to get the database structure MAuth requires.

## User-type objects

You can add whatever fields etc you like to this, but this is the bare-bones required for MAuth. For other user-type models, obviously change the table name.

	CREATE TABLE `users` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `username` varchar(32) NOT NULL,
	  `email` varchar(32) NOT NULL,
	  `password` varchar(50) NOT NULL,
	  `logins` int(11) NOT NULL DEFAULT '0',
	  `last_login` datetime DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	
## Packages

For every user-type you need to create a table called <code>packages_(user-type-table-name)</code>. Notice that this doesn't adhere to the convention of alphabeticalness. Sorry about that.

	CREATE TABLE `packages_users` (
	  `user_id` int(10) unsigned NOT NULL,
	  `package` varchar(64) NOT NULL,
	  `extra` text,
	  PRIMARY KEY (`user_id`,`package`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;