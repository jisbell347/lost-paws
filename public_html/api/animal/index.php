<?php

require_once (dirname(__DIR__,3). "/vendor/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once  (dirname(__DIR__, 3) ."/php/lib/uuid.php");
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

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
		} else if(empty($animalProfileId) === false) {
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
	} else if($method === "PUT" || $method === "POST") {

		// enforce that the user hs an XSRF token
		verifyXsrf();

		$requestContent = file_get_contents("php://input");
		//Retrieves the JSON package that the front end has sent, and stores it in $requestContent. Here we are using file_get_contents ("php://input) to get the request from th efront end. File_get_contents() is a PHP function that reads a file into a string. THe argument for the function here is "php://input". This is a read only stream that allows raw data to be read from the front end request which is in this case a JSON package.
		$requestObject = json_decode($requestContent);
		//This line then decodes the json package and stores that result in $requestObject.

		//make sure the animal color is available (required field)
		if(empty($requestObject->animalColor) === true) {
			throw(new \InvalidArgumentException("No color input for the animal.", 405));
		}
		//make sure the animal description is available (required field)
		if(empty($requestObject->animalDescription) === true) {
			throw(new \InvalidArgumentException("No description for the animal.", 405));
		}
		//make sure the animal gender is available (required field)
		if(empty($requestObject->animalGender) === true) {
			throw(new \InvalidArgumentException("No gender input for the animal.", 405));
		}
		//make sure the animal species is available (required field)
		if(empty($requestObject->animalSpecies) === true) {
			throw(new \InvalidArgumentException("No species entered for the animal.", 405));
		}
		//make sure the animal status is available (required field)
		if(empty($requestObject->animalStatus) === true) {
			throw(new \InvalidArgumentException("No status input for the animal.", 405));
		}

		//make sure the animal date is accurate (optional field)
		if(empty($requestObject->animalDate) === true) {
			$requestObject->animalDate = null;
		} else {
			// if the date exists, Angular's milliseconds since the beginning of time must be converted
			$animalDate = DateTime::createFromFormat("U.u", $requestObject->animalDate / 1000);
			if($animalDate === false) {
				throw(new RuntimeException("invalid animal date", 400));
			}
			$requestObject->animalDate = $animalDate;
		}

		//perform the actual put or post
		if($method === "PUT") {

			//retrieve the animal to update
			$animal = Animal::getAnimalByAnimalId($pdo, $id);
			if($animal === null) {
				throw (new RuntimeException("Animal does not exist", 404));
			}

			//enforce the user is  signed in and only trying to edit their own animal post.
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $animal->getAnimalId()->toString()) {
				throw(new \InvalidArgumentException("You are not allow to edit this animal posting.", 403));
			}

			//update all attributes
			$animal->setAnimalColor($requestObject->animalColor);
			$animal->setAnimalDate($requestObject->animalDate);
			$animal->setAnimalDescription($requestObject->animalDescription);
			$animal->setAnimalGender($requestObject->animalGender);
			$animal->setAnimalImageUrl($requestObject->animalImageUrl);
			$animal->setAnimalLocation($requestObject->animalLocation);
			$animal->setAnimalName($requestObject->animalName);
			$animal->setAnimalSpecies($requestObject->animalSpecies);
			$animal->setAnimalStatus($requestObject->animalStatus);
			$animal->update($pdo);

			//update reply
			$reply->message = "Animal posting updated OK";

		} else if($method === "POST") {

			// enforce that the user is signed in
			if(empty($_SESSION[$profile]) === true) {
				throw(new \InvalidArgumentException("You must be logged in to post an animal.", 403));
			}

			//create new animal and insert it into the database
			$animal = new Animal(generateUuidV4(), $_SESSION["profile"]->getProfileId(), $requestObject->animalColor, null, $requestObject->animalDescription, $requestObject->animalGender, $requestObject->animalImageUrl, $requestObject->animalLocation, $requestObject->animalName, $requestObject->animalSpecies, $requestObject->animalStatus);
			$animal->insert($pdo);

			//update reply
			$reply->message = "Animal posting created OK";

		}

	} else if ($method === "DELETE") {

		//enforce that the end user has an XSRF token.
		verifyXsrf();

		//retrieve the Animal posting to be deleted.
		$animal = Animal::getAnimalByAnimalId($pdo, $id);
		if($animal === null) {
			throw(new RuntimeException("Animal posting does not exist.", 404));
		}

		//enforce the user is signed in and is only trying to edit their own animal post.
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $animal->getAnimalProfileId()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this animal post.", 403));
		}

		//delete the animal post
		$animal->delete($pdo);
		//update reply
		$reply->message = "Animal post deleted OK";
	} else {
		throw(new InvalidArgumentException("Invalid HTTP method request."));
}

	//update the $reply->status $reply->message
	} catch(\Exception | \TypeError $exception) {
		$reply->status = $exception->getCode();
		$reply->message = $exception->getMessage();
	}

	//encode and return reply to the fnont end caller
	header("Content-type: application/json");
	echo json_encode($reply);