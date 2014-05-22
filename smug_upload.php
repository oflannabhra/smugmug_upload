<?php

require_once("phpSmug/phpSmug.php");
const EMAIL = "ben.flannery@gmail.com";
const PASS = "fl@n6o4o";
const APIKEY = "hNOYUvAmnTaQrxkh27ePYEauYAu1Cwvs";
const APPNAME = "uploadscript / 0.1 (http://www.oflannabhra.com";
const UPLOAD_DIRECTORY = "/Users/meph/Pictures/Ben-Kate/";
const UPLOAD_ALBUM_ID = "40941405";

$sm = new phpSmug(array("APIKey" => APIKEY,
   	        "AppName" => APPNAME,
   	        "APIVer" => "1.2.2")
   	       );

try{
	$sm->login("EmailAddress=" . EMAIL, "Password=" . PASS);
	$sm->setAdapter("socket");
	$files = getFiles(UPLOAD_DIRECTORY);

	foreach ($files as $file)
	{
		$sm->images_upload("AlbumID=".UPLOAD_ALBUM_ID, "File=".UPLOAD_DIRECTORY . $file);
		echo ("Uploaded: " . $file . "\n");
	}

}

catch (PhpSmugException $e){
	exit;
}

function getFiles($directory){
	$files = scandir($directory);
	$files = array_diff($files, array(".", ".."));
	return $files;
}

