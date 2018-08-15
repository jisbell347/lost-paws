<?php

require_once (dirname(__DIR__,3). "/vendor/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once  (dirname(__DIR__, 3) ."/php/lib/uuid.php");
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once (dirname(__DIR__, 3) . "/php/lib/jwt.php");

use Jisbell347\LostPaws\{OAuth, Profile};
use Ramsey\Uuid;

/**
 * api for the Profile Class
 *
 * @see OAuth
 * @see Profile
 * @author Asya Nikitina <a.f.nikitina@gmail.com>
 * @version 1.0.0
 **/

// verify that a session is active, if not -- start the session
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try{
//grab the mySQL Connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/lostfuzzy.ini");

//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	$method = strtoupper($method);

	// sanitize input (one of these inputs shouldn't be empty
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileOAuthId = filter_input(INPUT_GET, "profileOAuthId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// if updating or deleting the Profile record, id field cannot be empty
	if(($method === "DELETE" || $method === "PUT") && empty($id)) {
		throw(new InvalidArgumentException("ID is invalid.", 405));
	}

	// handle different HTTP methods
	switch ($method) {
		case "GET":
			// set up a XSRF-TOKEN to prevent CSRF
			setXsrfCookie();

			if(!empty($id)) {
				$profile = Profile::getProfileByProfileId($pdo, $id);
				if($profile) {
					$reply->data = $profile;
				}
			} else if(!empty($profileOAuthId)) {
				$profile = Profile::getProfileByProfileOAuthId($pdo, $profileOAuthId);
				if($profile) {
					$reply->data = $profile;
				}
			} else if(!empty($profileEmail)) {
				$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
				if($profile) {
					$reply->data = $profile;
				}
			}
			break;
		case "PUT":
			// verify that a XSRF-TOKEN is present
			verifyXsrf();

			break;
		case "DELETE":

			break;
	}



	if () {
		// set up a XSRF-TOKEN to prevent CSRF
		setXsrfCookie();
	}

}






// use PHP’s stream handling to create the complete request to send
$url = 'http://localhost/book/get-form-page.php';
$data = ["category" => "technology", "rows" => 20];

$get_addr = $url . '?' . http_build_query($data);
$page = file_get_contents($get_addr);
echo $page;


	$url = 'http://localhost/book/post-form-page.php';
	$data = ["email" => "lorna@example.com", "display_name" => "LornaJane"];
	$options = ["http" =>
		["method"  => "POST",
			"header"  => "Content-Type: application/x-www-form-urlencoded",
			"content" => http_build_query($data)
		]
	];





















