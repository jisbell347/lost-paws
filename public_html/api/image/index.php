<?php

require_once (dirname(__DIR__,3). "/vendor/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once  (dirname(__DIR__, 3) ."/php/lib/uuid.php");
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once (dirname(__DIR__, 3) . "/php/lib/jwt.php");

use Jisbell347\LostPaws\{OAuth, Profile, Animal};

/**
 * the user upload an image file to Cloudinary, the server grabs the secure image URL from Cloudinary
 * and updates the animalImageUrl field of a specified animal
 * @see OAuth
 * @see Profile
 * @see Animal
 * @author Asya Nikitina <a.f.nikitina@gmail.com>
 * @version 1.0.0
 */

// verify that a session is active, if not -- start the session
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;


try {
	//grab the mySQL Connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/lostfuzzy.ini");

	//sanitize input
	$id = filter_input(INPUT_POST, "animalId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// determine the HTTP method used (we only allow the POST method to be used for image uploaing)
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	if ($method !== "POST") {
		throw (new \Exception("This HTTP method is not supported for image upload.", 405));
	}

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// verify that a XSRF-TOKEN is present
	verifyXsrf();

	// make sure that a user is logged in before uploading a picture
	if(empty($_SESSION["profile"]) || empty($_SESSION["profile"]->getProfileId()->toString())) {
		throw(new \InvalidArgumentException("You are not logged in.", 403));
	}

	// validate header
	validateJwtHeader();

	$config = readConfig("/etc/apache2/lost-paws/lostfuzzy.ini");
	$cloudinary = json_decode($config["cloudinary"]);
	\Cloudinary::config(["cloud_name" => $cloudinary->cloudName, "api_key" => $cloudinary->apiKey, "api_secret" => $cloudinary->apiSecret]);

	// grab the animal record from the database using animal ID (from $_POST["animalImageUrl"])
	$animal = Animal::getAnimalByAnimalId($pdo, $id);
	if(!$animal) {
		throw(new \InvalidArgumentException ("Could not locate specified animal(s) fro this profile.", 404));
	}
	// assigning variable to the animal, add image extension
	$tempAnimalFileName = $_FILES["image"]["tmp_name"];
	// upload image to cloudinary and get public id
	$cloudinaryResult = \Cloudinary\Uploader::upload($tempAnimalFileName, array("width" => 500, "crop" => "scale"));
	// after sending the image to Cloudinary, set animalImageUrl to the animal record
	$animal->setAnimalImageUrl($cloudinaryResult["secure_url"]);
	$animal->update($pdo);
	// update reply
	$reply->message = "Image uploaded Ok";
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

//encode and return reply to the front-end caller
header("Content-type: application/json");
if (!$reply->data) {
	unset($reply->data);
}

// encode and return reply to front-end caller
echo json_encode($reply);



















// headers['Content-Length'].to_i > 3.megabytes


//
//
//The global $_FILES will contain all the uploaded file information. Its contents from the example form is as follows. Note that this assumes the use of the file upload name userfile, as used in the example script above. This can be any name.
//
//$_FILES['userfile']['name']
//
//    The original name of the file on the client machine.
//$_FILES['userfile']['type']
//
//    The mime type of the file, if the browser provided this information. An example would be "image/gif". This mime type is however not checked on the PHP side and therefore don't take its value for granted.
//$_FILES['userfile']['size']
//
//    The size, in bytes, of the uploaded file.
//$_FILES['userfile']['tmp_name']
//
//    The temporary filename of the file in which the uploaded file was stored on the server.
//$_FILES['userfile']['error']
//
//    The error code associated with this file upload.
//
//Files will, by default be stored in the server's default temporary directory, unless another location has been given with the upload_tmp_dir directive in php.ini. The server's default directory can be changed by setting the environment variable TMPDIR in the environment in which PHP runs. Setting it using putenv() from within a PHP script will not work. This environment variable can also be used to make sure that other operations are working on uploaded files, as well.
//
//
//$uploaddir = '/var/www/uploads/';
//$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
//
//echo '<pre>';
//if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
//	echo "File is valid, and was successfully uploaded.\n";
//} else {
//	echo "Possible file upload attack!\n";
//}
//
//echo 'Here is some more debugging info:';
//print_r($_FILES);
//
//print "</pre>";
//
//
////physical address of uploads directory
//$uploaddir = "/bootcamp-coders/cnm/edu/jisbell347/lostpaws/epic/images/";
//// full file name
//$uploadfile =  $uploaddir .basename($_FILES["name"]);
//
//
//
//$uploadfile = $uploaddir . basename($_FILES['myFile']['name']);
//
//if(move_uploaded_file($_FILES['myFile']['tmp_name'], $uploadfile)){
//	echo "File was successfully uploaded.\n";
//	/* Your file is uploaded into your server and you can do what ever you want with */
//}else{
//	echo "Possible file upload attack!\n";
//}
//
//
//
