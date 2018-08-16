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

	// sanitize inputs
	$id = filter_input(INPUT_GET, "profileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileName = filter_input(INPUT_GET, "profileName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profilePhone = filter_input(INPUT_GET, "profilePhone", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// make sure that the user is logged in
	if(empty($_SESSION["profile"]) || ($id !== strval($_SESSION["profile"]->getProfileId()))) {
		throw(new \InvalidArgumentException("You are not logged into your account.", 403));
	}

	// get profile by profile ID form the database
	$profile = Profile::getProfileByProfileId($pdo, $_SESSION["profile"]->getProfileId());

	if (!$profile) {
		throw(new \InvalidArgumentException ("Could not locate specified profile.", 404));
	}

	// handle different HTTP methods
	if ($method === "GET") {
		// set up a XSRF-TOKEN to prevent CSRF
		setXsrfCookie();

		$reply->data = $profile;


	} else if ($method === "PUT") {
		// verify that a XSRF-TOKEN is present
		verifyXsrf();
		// validate header
		validateJwtHeader();
		// use PHP’s stream handling to get request content and decode JSON object
		$decodedObject = json_decode(file_get_contents("php://input"), true);

		if (!$decodedObject) {
			throw (new Exception("Data missing or invalid.", 404));
		}

		// make sure that decoded JSON contains a valid profile
		if(empty($decodedObject["profileId"])) {
			throw(new \InvalidArgumentException ("No profile was located.", 404));
		}

		// make sure that changes are being made before updating a profile
		$changed = false;
		// check what needs to be updated
		if ($profile->getProfileEmail() !== $decodedObject["profileEmail"]) {
			$profile->setProfileEmail($decodedObject["profileEmail"]);
			$changed = true;
		}
		if ($profile->getProfileName() !== $decodedObject["profileName"]) {
			$profile->setProfileName($decodedObject["profileName"]);
			$changed = true;
		}
		if ($profile->getProfilePhone() !== $decodedObject["profilePhone"]) {
			$profile->setProfilePhone($decodedObject["profilePhone"]);
			$changed = true;
		}
		if ($changed) {
			$profile->update($pdo);
			// update message
			$reply->message = "Profile was updated OK.";
		}
		else {
			$reply->message = "No changes were detected Profile wasn't updated.";
		}
	} else if ($method === "DELETE") {
		// verify that a XSRF-TOKEN is present
		verifyXsrf();

		$profile->delete($pdo);
		$reply->message = "Profile was deleted OK.";
	} else {
		throw (new \Exception("Method Not Supported.", 405));
	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

//encode and return reply to the fnont end caller
header("Content-type: application/json");
echo json_encode($reply);


















