<?php

namespace \namespace\here;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 *oAuth section of lostpaws.com.
 *
 * The user clicks on an authentication service (Google, Facebook, Github) which then
 * creates a profile on the lostpaws site based on the information gathered from the authentication site of their choice.
 * @author Joseph Isbell <jisbell1@cnm.edu>
 * @version 1.0.0
 **/

class OAuth {
	use ValidateDate;
	use ValidateUuid;

	/**
	 * id for this OAuth; This is the primary key
	 * @var int $oAuthId
	 **/
	private $oAuthId;

	/**
	 * Source where the data from the OAuth is being accessed from
	 * @var string $oAuthSource
	 **/
	private $oAuthSource;

	/**
	 * Constructor for the oAuth
	 *
	 * @param string|Uuid $newOAuthId id of the oAuth or null if a new oAuth
	 */
}