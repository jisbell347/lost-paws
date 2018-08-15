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
	$profileName = filter_input(INPUT_GET, "profileName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profilePhone = filter_input(INPUT_GET, "profilePhone", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);







//	"New user email: " . filter_input(INPUT_POST,
//		"email", FILTER_VALIDATE_EMAIL);

	// if updating or deleting the Profile record, id field cannot be empty
	if(($method === "PUT" || $method === "DELETE") && empty($id)) {
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
		case "POST":
			$profileEmail = filter_input(INPUT_POST, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$profileName = filter_input(INPUT_POST, "profileName", FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
			$profilePhone = filter_input(INPUT_POST, "profilePhone", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			// make sure that the user is logged in
			if(empty($_SESSION["profile"])) {
				throw(new \InvalidArgumentException("You must be logged in to modify your profile.", 403));
			}
			// the logged in user already has a valid profile record in our database (his profile is being updated)
			// pull out the corresponding profile object from the database
			$profile = Profile::getProfileByProfileId($pdo, $_SESSION["profile"]->getProfileId());
			if(!$profile) {
				throw(new RuntimeException("Profile does not exist.", 404));
			}
			// make sure that changes are being made before updating a profile
			$changed = false;

			// check what needs to be updated
			if  (!emtpy($requestObject->profileEmail)) {
				$profile->setProfileEmail($requestObject->profileEmail);
				$changed = true;
			}
			if (!emtpy($requestObject->profileName)) {
				$profile->setProfileName($requestObject->profileName);
				$changed = true;
			}
			}
			if (!emtpy($requestObject->profilePhone)) {
				$profile->setProfilePhone($requestObject->profilePhone);
				$changed = true;
			}

			if ($changed) {
				$profile->update($pdo);
			}


			// update reply
			$reply->message = "Tweet created OK";
			break;
		case "PUT":
			// verify that a XSRF-TOKEN is present
			verifyXsrf();

			// make sure that the user is logged in before undating his/her profile
			if(empty($_SESSION["profile"]) || $_SESSION["profile"]->getProfileId()->toString() !== $id) {
				throw(new \InvalidArgumentException("You are not allowed to access this profile.", 403));
			}
			// validate header
			validateJwtHeader();

			// use PHP’s stream handling to get request content and then decode json object
			$requestContent = file_get_contents("php://input");
			$requestObject = json_decode($requestContent);

			//retrieve the profile to be updated
			$profile = Profile::getProfileByProfileId($pdo, $id);
			if(!$profile) {
				throw(new RuntimeException("Profile does not exist.", 404));
			}

			// check whether all necessary data are present
			//profile OAuth ID
			if(empty($requestObject->profileOAuthId)) {
				throw(new \InvalidArgumentException ("No profile OAuth ID.", 405));
			}

			//profile email is a required field
			if(empty($requestObject->profileEmail) === true) {
				throw(new \InvalidArgumentException ("No profile email present", 405));
			}

			//profile phone # | if null use the profile phone that is in the database
			if(empty($requestObject->profilePhone) === true) {
				$requestObject->ProfilePhone = $profile->getProfilePhone();
			}

			$profile->setProfileAtHandle($requestObject->profileAtHandle);
			$profile->setProfileEmail($requestObject->profileEmail);
			$profile->setProfilePhone($requestObject->profilePhone);
			$profile->update($pdo);













			break;
		case "DELETE":

			break;
	}

} catch() {

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





















