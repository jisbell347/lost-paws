<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
//require_once (dirname(__DIR__, 3) . "php/lib/jwt.php");
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once (dirname(__DIR__, 3) . "/php/lib/jwt.php");

use Jisbell347\LostPaws\{
	Animal,
	OAuth,
	Profile,
	Comment
};

/**
 * api for the Comment class
 *
 * @author Adel Moreno <amoreno28@cnm.edu>
 **/

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//TODO: WHAT GOES HERE?
	// grab the mySQL connection
	//$pdo = connectToEncryptedMySQL("");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentAnimalId = filter_input(INPUT_GET, "commentAnimalId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentProfileId = filter_input(INPUT_GET, "commentProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentText = filter_input(INPUT_GET, "commentText", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the comment id is valid for methods that require it (required field)
	if(($method === "DELETE" || $method === "PUT") && empty($id) === true) {
		throw(new InvalidArgumentException("comment id cannot be empty", 405));
	}

	// handle GET request - if id is present, that tweet is returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific comment and update reply
		if(empty($id) === false) {

			var_dump($id);
			$comment = Comment::getCommentByCommentId($pdo, $id);
			if($comment !== null) {
				$reply->data = $comment;
			}

			//TODO how to get comments from animal?
		} else if(empty($commentAnimalId) === false) {

			// grab all the comments that are on this animal
			$comments = Comment::getCommentByCommentAnimalId($pdo, $commentAnimalId->getAnimalId())->toArray();
			if($comments !== null) {
				$reply->data = $comments;
			}
		} else if(empty($commentProfileId) === false) {

			// if the user is logged in grab all the comments by that user based on who is logged in
			$comments = Comment::getCommentByCommentProfileId($pdo, $_SESSION["profile"]->getProfileId()->toArray());
			if($comments !== null) {
				$reply->data = $comments;
			}
		} else if(empty($commentText) === false) {
			//var_dump($commentText);
			$comments = Comment::getCommentByCommentText($pdo, $commentText)->toArray();
			if($comments !== null) {
				$reply->data = $comments;
			}
		}
	} else if($method === "PUT" || $method ==="POST") {

		//enforce the user has a XSRF token
		verifyXsrf();

		$requestContent = file_get_contents("php://input");
		//Retrieves the JSON package that the front end has sent, and stores it in $requestContent. Here we are using file_get_contents ("php://input) to get the request from the front end. File_get_contents() is a PHP function that reads a file into a string. THe argument for the function here is "php://input". This is a read only stream that allows raw data to be read from the front end request which is in this case a JSON package.
		$requestObject = json_decode($requestContent);
		//This line then decodes the json package and stores that result in $requestObject.

		// enforce the user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("You must be logged in to post comments", 401));
		}

		// make sure the comment date is accurate (optional field)
		if (empty($requestObject->commentDate) === true) {
			$requestObject->commentDate = null;
		} else {
			// if the date exists, Angular's milliseconds since the beginning of time must be converted
			$commentDate = DateTime::createFromFormat("U.u", $requestObject->commentDate / 1000);
			if($commentDate === false) {
				throw(new RuntimeException("Invalid comment date", 400));
			}
		}

		//perform the actual put or post
		if($method === "PUT") {

			// retrieve the comment to update
			$comment = Comment::getCommentByCommentId($pdo, $id);
			if($comment === null) {
				throw(new RuntimeException("Comment does not exist", 404));
			}

			//enforce the end user has a JWT token
			validateJwtHeader();


			//enforce the user is signed in and only trying to edit their own comment
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $comment->getCommentProfileId()->toString()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this comment", 403));
			}


			//update all attributes
			$comment->setCommentDate($requestObject->commentDate);
			$comment->setCommentText($requestObject->commentText);
			$comment->update($pdo);

			// update reply
			$reply->message = "Comment update OK";

		} else if($method === "POST") {


			// enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \ InvalidArgumentException("You must be logged in to post comments", 403));
			}

			//enforce the end user has a JWT token

			validateJwtHeader();

			//create a new comment and insert into the database
			$comment = new Comment(generateUuidV4(), $requestObject->getAnimalId(), $_SESSION["profile"]->getProfileId(), null, $requestObject->commentText);
			$comment->insert($pdo);

			// update comment
			$reply->message = "Comment created OK";
		}
	} else if($method === "DELETE") {

		//enforce that the end user has a XSRF token.
		verifyXsrf();

		// retrieve the Comment to be deleted
		$comment = Comment::getCommentByCommentId($pdo, $id);
		if($comment === null) {
			throw(new RuntimeException("Comment does not exist", 404));
		}


		//enforce the user is signed in and only trying to edit their own comment
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $comment->getCommentProfileId()->toString()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this comment", 403));
		}

		//enforce the end user has a JWT token
		validateJwtHeader();

		//delete the comment
		$comment->delete($pdo);
		//update reply
		$reply->message = "Comment deleted OK";
	} else {
		throw(new InvalidArgumentException("Invalid HTTP method request", 418));
	}

} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}


//encode and return reply to front end caller
echo json_encode($reply);