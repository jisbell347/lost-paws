<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Jisbell347\LostPaws\Profile;

/**
 * Facebook login API
 *
 * @author Joe Isbell <jisbell1@cnm.edu>
 **/

//Verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$reply = new \stdClass();
$reply->status = 200;
$reply->data = null;
/**
 * Create the client object and set the authorization configuration
 **/
$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/lostfuzzy.ini");
$config = readConfig("/etc/apache2/capstone-mysql/lostfuzzy.ini");
$facebook = json_decode($config["facebook"]);

$fb = new Facebook\Facebook([
	"app_id" => $facebook->appId,
	"app_secret" => $facebook->secretId,
	"default_graph_version" => "v3.1",
]);

$helper = $fb->getRedirectLoginHelper();

try {
	$accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	// When Graph returns an error
	echo "Graph returned an error: " . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	// When validation fails or other local issues
	echo "Facebook SDK returned an error: " . $e->getMessage();
	exit;
}

if (! isset($accessToken)) {
	if ($helper->getError()) {
		header("HTTP/1.0 401 Unauthorized");
		echo "Error: " . $helper->getError() . "\n";
		echo "Error Code: " . $helper->getErrorCode() . "\n";
		echo "Error Reason: " . $helper->getErrorReason() . "\n";
		echo "Error Description: " . $helper->getErrorDescription() . "\n";
	} else {
		header("HTTP/1.0 400 Bad Request");
		echo "Bad request";
	}
	exit;
}

// Logged in
echo '<h3>Access Token</h3>';
//var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';
//var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId($facebook->appId); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
	// Exchanges a short-lived access token for a long-lived one
	try {
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	} catch (Facebook\Exceptions\FacebookSDKException $e) {
		echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
		exit;
	}

	echo '<h3>Long-lived</h3>';
	//var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

try {
	// Returns a `Facebook\FacebookResponse` object
	$response = $fb->get("/me?fields=id,name,email,location", $accessToken);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	echo "Graph returned an error: " . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	echo "Facebook SDK returned an error: " . $e->getMessage();
	exit;
}
$user = $response->getGraphUser();
$userName = $user->getName();
$userEmail = $user->getEmail();

$_SESSION["fbUserId"] = $user["id"];


if(empty($_SESSION["fb_access_token"] || empty($_SESSION["fbUserId"]) === true)) {
	throw(new \InvalidArgumentException("Please return to homepage and sign up or login", 403));
}

//Check to see if profile already exists in database
$profile = Profile::getProfileByProfileEmail($pdo, $userEmail);

if(!empty($profile)) {
	$_SESSION["profile"] = $profile;
	$profileId = $_SESSION["profile"]->getProfileId();

	//create the Auth payload
	$authObject = (object)[
		"profileId" => $profile->getProfileId(),
		"profileName" => $profile->getProfileName()
	];

	//create and set th JWT TOKEN
	setJwtAndAuthHeader("auth", $authObject);
	$reply->message = "Welcome to Lost Paws. Feel the fuzzy!";

//	header("Location: ../..");
} else {
	$newProfile = new Profile(generateUuidV4(), 2, null, $userEmail, $userName, "");
	$newProfile->insert($pdo);

	$profile = Profile::getProfileByProfileEmail($pdo, $userEmail);
	$_SESSION["profile"] = $newProfile;

	//create the Auth payload
	$authObject = (object)[
		"profileId" => $profile->getProfileId(),
		"profileName" => $profile->getProfileName()
	];

	// create and set th JWT TOKEN
	setJwtAndAuthHeader("auth", $authObject);
	$reply->message = "Welcome to Lost Paws. Feel the fuzzy!";

//	header("Location: ../..");
}

header("Content-type: application/json");
echo json_encode($reply);

