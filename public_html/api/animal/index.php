<?php

require_once dirname(__DIR__,3 . "/vendor/autoload.php");
require_once dirname(__DIR__, 3 . "php/classes/autoload.php");
require_once dirname(__DIR__, 3 . "php/lib/xsrf.php");
require_once  dirname(__DIR__, 3 ."php/lib/uuid.php");
//Todo: in example api hhas jwt and etc/apache2/...

use Jisbell347\LostPaws\{
	Profile,
	Animal,
	OAuth
};


/**
 * aup for the Animal Class
 *
 * @author  Jude Baca-Mille <judeamiller@gmail.com>
 **/