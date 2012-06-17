<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set up Mauth, basically just include the phpass library.
 */

require_once Kohana::find_file('vendor', 'phpass/PasswordHash');