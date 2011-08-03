# MAuth

One Auth to rule them all, one Auth to bring any ORM, one Auth to spawn multiple instances, one Auth to create granular permissions.

And in the darkness, bind them. [/geekery]

## What is this MAuth creature?

MAuth is (another) Auth library for Kohana 3, but this one aims to be a bit different.

- **ORM Agnostic**: MAuth doesn't care if you use Kohana's ORM, Jelly or anything else. You can choose whatever ORM you like, and we have drivers ready to go for the aforementioned two.
- **Create Multiple Instances**: Need a completely seperate login system for your admins and customers? On different tables? With different permissions? Not a problem.
- **Simple, powerful permissions**: Using [Packages](mauth.packages) you can create extremely granular, powerful permissions, all through a very simple API.
- **Light.ish**: With power comes responsibility, MAuth is designed to be as fast as possible whilst maintaining a great feature set.

## How do I get it?

Download it from GitHub, or install as a submodule if you're feeling fancy.
	
	git submodule add git@github.com:alexgisby/MAuth.git modules/mauth
	
The master branch will always be stable, the dev branch is always unstable.

## Docs?

I'm a documentation whore, so the code is all commented to the hilt, and this guide will be kept up to date. Enable the userguide module in your Kohana and view it locally.

## License?

MAuth is licensed under the MIT license, meaning you can do (almost) anything with it. Include it in commercial project, or open source ones.

A mention is always nice, and if you use this module, let me know. That said, I provide this code with zero (0) warranty. If it breaks your wonder-project, sorry but it's your problem.

## Author?

Me, Alex Gisby. Follow me on Twitter if you like ([@alexgisby](http://twitter.com/alexgisby)).