<?php

require_once (dirname(__DIR__,3). "/vendor/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once (dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once  (dirname(__DIR__, 3) ."/php/lib/uuid.php");
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once (dirname(__DIR__, 3) . "/php/lib/jwt.php");

use Jisbell347\LostPaws\{OAuth, Profile, Animal};

// verify that a session is active, if not -- start the session
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

// to create or update an image on the server we are going to use returning values from forms: multipart/form-data
// the user upload an image file, the server receives it using either POST (image created) or PUT (image updated) protocols

$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

// make sure that a user is logged in
if(empty($_SESSION["profile"])) {
	throw(new \InvalidArgumentException("You are not logged in.", 403));
}

// handle two cases: POST and PUT
switch ($method) {
	case "POST":
		break;
	case "PUT":
		break;
	default:
		throw (new \Exception("Method Not Supported.", 405));
}



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
