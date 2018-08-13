<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

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
	$pdo = connectToEncryptedMySQL("");

	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$commentId = filter_input(INPUT_GET, "commentId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentAnimalId = filter_input(INPUT_GET, "commentAnimalId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentProfileId = filter_input(INPUT_GET, "commentProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentText = filter_input(INPUT_GET, "commentText", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the comment id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && empty($commentId) === true) {
		throw(new InvalidArgumentException("comment id cannot be empty", 405));
	}

	// handle GET request - if id is present, that tweet is returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific comment and update it
		if(empty($commentId) === false) {

			var_dump($commentId);
			$comment = Comment::getCommentByCommentId($pdo, $commentId);
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

		// enforce the user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to post comments", 401));
		}

		// make sure the comment date is accurate (optional field)
	}
}