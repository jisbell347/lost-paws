<?php

require_once (dirname(__DIR__,3). "/vendor/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once  (dirname(__DIR__, 3) ."/php/lib/uuid.php");
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once (dirname(__DIR__, 3) . "/php/lib/jwt.php");

use Jisbell347\LostPaws\{OAuth, Profile};

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

	// handle different HTTP methods
	if($method === "GET") {
		// set up a XSRF-TOKEN to prevent CSRF
		setXsrfCookie();

		// sanitize inputs
		$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$profileName = filter_input(INPUT_GET, "profileName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$profilePhone = filter_input(INPUT_GET, "profilePhone", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		// we allow the logged in user to search for another user using the other user's phone number or email address
		if (!empty($profileEmail)) {
			$reply->data = Profile::getProfileByProfileEmail($pdo, $profileEmail);
		} else if (!empty($profilePhone)) {
			$reply->data = Profile::getProfileByProfilePhone($pdo, $profilePhone);
		} else if (!empty($id)) {
			$reply->data = Profile::getProfileByProfileId($pdo, $id);
		}
	} else if ($method === "PUT") {
		// verify that a XSRF-TOKEN is present
		verifyXsrf();

		// the user is allowed to modify only his/her own profile,
		// thus, we get profile ID using $_SESSION global variable
		$profile = Profile::getProfileByProfileId($pdo, $_SESSION["profile"]->getProfileId());
		if (!$profile) {
			throw(new \InvalidArgumentException("You must be logged in to modify your profile.", 405));
		}

		// validate header
		validateJwtHeader();

		// use PHP’s stream handling to get request content and decode JSON object
		$decodedObject = json_decode(file_get_contents("php://input"));

		if (!$decodedObject) {
			throw (new Exception("Data missing or invalid.", 404));
		}

		// we only allow the logged in user to modify his/her email address, name, and/or phone number
		if (!empty($decodedObject->profileEmail)) {
			$profile->setProfileEmail($decodedObject->profileEmail);
		}
		if (!empty($decodedObject->profileName)) {
			$profile->setProfileName($decodedObject->profileName);
		}
		if (!empty($decodedObject->profilePhone)) {
			$profile->setProfilePhone($decodedObject->profilePhone);
		}
		// update the current profile
		$profile->update($pdo);
		// update message
		$reply->message = "Profile information was updated.";
	} else if ($method === "DELETE") {
		// verify that a XSRF-TOKEN is present
		verifyXsrf();

		// get the current profile from the database using $_SESSION global variable
		// since we allow the user to delete his/her profile only
		$profile = Profile::getProfileByProfileId($pdo, $_SESSION["profile"]->getProfileId());
		if (!$profile) {
			throw(new \InvalidArgumentException("You must be logged in to delete your profile.", 405));
		}

		validateJwtHeader();

		//delete the profile from the database
		$profile->delete($pdo);
		$reply->message = "Profile was deleted.";
	} else {
		throw (new \Exception("This HTTP request is not supported.", 405));
	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

//encode and return reply to the front-end caller
header("Content-type: application/json");
echo json_encode($reply);


















