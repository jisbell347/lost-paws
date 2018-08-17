<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once (dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Jisbell347\LostPaws\{
	Profile
};
//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
$reply->message = null;
try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/lostfuzzy.ini");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//sanitize the search parameters
// echo "1";
	$uuid = generateUuidV4();
//	echo "2";
	$randomEmail = bin2hex(random_bytes(10)) . "@email.com";
//	$refreshToken = bin2hex(random_bytes(32));
	$userName = bin2hex(random_bytes(12));
//	$randomImage = bin2hex(random_bytes(12));
//echo "3";
	$profile = new Profile(generateUuidV4(), 1, null,  $randomEmail, $userName, "" );
	$profile->insert($pdo);
	$reply->message ="gus was sent to the play pen";
	$_SESSION["profile"] = $profile;
	setXsrfCookie();
	$object = new stdClass();
	// create and set th JWT TOKEN

	$authObject = (object) [
		"profileId" =>$profile->getProfileId(),
		"profileEmail" => $profile->getProfileEmail()
	];
	setJwtAndAuthHeader("auth",$authObject);
	//echo $profile->getProfileId();
	//$reply->data = $profile->getProfileId();

	$reply->message = "Sign in was successful.";
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	//$exception->getTraceAsString();
	//var_dump($reply);
}

header("Content-type: application/json");
echo json_encode($reply);