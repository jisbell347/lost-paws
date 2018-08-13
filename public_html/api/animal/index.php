<?php

require_once dirname(__DIR__,3 . "/vendor/autoload.php");
require_once dirname(__DIR__, 3 . "php/classes/autoload.php");
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once dirname(__DIR__, 3 . "php/lib/xsrf.php");
require_once  dirname(__DIR__, 3 ."php/lib/uuid.php");
//TODO: in example api has jwt and etc/apache2/...

use Jisbell347\LostPaws\{
	Profile,
	Animal,
	OAuth
};


/**
 * api for the Animal Class
 *
 * @author  Jude Baca-Miller <judeamiller@gmail.com>
 **/

//verify the session, start if inactive
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

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$animalProfileId = filter_input(INPUT_GET, "animalProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$animalColor = filter_input(INPUT_GET, "animalColor", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$animalDescription = filter_input(INPUT_GET, "animalDescription", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$animalGender = filter_input(INPUT_GET, "animalGender", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$animalSpecies = filter_input(INPUT_GET, "animalSpecies", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$animalStatus = filter_input(INPUT_GET, "animalStatus", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for methods that require it.
	if(($method === "DELETE" || $method === "PUT") && empty($id) === true){
		throw(new InvalidArgumentException("id cannot be empty", 405));
	}

	//handle GET request - if id is present, that animal is returned, otherwise all animals are returned.
	if($method === "GET") {
		//set XSFR cookie
		setXsrfCookie();

		//get a specific animal or all animals  and update reply
		if(empty($id) === false) {
			$reply->data = Animal::getAnimalByAnimalId($pdo, $id);
		}else if (empty($animalProfileId) === false) {
			$reply->data = Animal::getAnimalByAnimalProfileId($pdo, $animalProfileId)->toArray();
		} else if(empty($animalColor) === false) {
			$reply->data = Animal::getAnimalByAnimalColor($pdo,$animalColor)->toArray();
		} else if(empty($animalDescription) === false) {
			$reply->data = Animal::getAnimalByAnimalDescription($pdo, $animalDescription)->toArray();
		} else if(empty($animalGender) === false) {
			$reply->data = Animal::getAnimalByAnimalGender($pdo, $animalGender)->toArray();
		} else if(empty($animalSpecies) === false) {
			$reply->data = Animal::getAnimalByAnimalSpecies($pdo, $animalSpecies)->toArray();
		} else if(empty($animalStatus) === false) {
			$reply->data = Animal::getAnimalByAnimalStatus($pdo, $animalSpecies)->toArray();
		} else {
			$reply->data = Animal:: getAllCurrentAnimals($pdo)->toArray();
		}
	} else if($method === "PUT" || $method === "POST")


}

//