<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


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
	"app_id" => $facebook->clientId,
	"app_secret" => $facebook->secretId,
	"default_graph_version" => "v3.1",
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ["email"]; // Optional permissions
$loginUrl = $helper->getLoginUrl("https://bootcamp-coders.cnm.edu/~jisbell1/lost-paws/public_html/api/facebook/", $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';