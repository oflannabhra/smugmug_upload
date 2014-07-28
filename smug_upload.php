<?php

require_once("phpSmug/phpSmug.php");
include("config.php");




try{
	upload(setSession());
}

catch (Exception $e){
	echo($e->message);
	upload(setSession());
}

function setSession()
{
	$sm = new phpSmug(array("APIKey" => APIKEY,
	"AppName" => APPNAME,
	"APIVer" => "1.2.2")
	);
	$sm->login("EmailAddress=" . EMAIL, "Password=" . PASS);
	$sm->setAdapter("socket");

	return $sm;
}
function upload(phpSmug $sm)
{

	$files = getFiles(UPLOAD_DIRECTORY);
	$album = $sm->images_get("AlbumID=".UPLOAD_ALBUM_ID, "AlbumKey=".UPLOAD_ALBUM_KEY);
	
	// Get a List of images from the album to check for dupes
	$images = $album["Images"];
	$names = array();

	foreach ($images as $image) {
		array_push($names, $sm->images_getInfo("ImageID=".$image['id'], "ImageKey=".$image['Key'])['FileName']);
	}


	foreach ($files as $file)
	{
		if (array_search($file.', '.$file, $names)===FALSE){
			$response =  $sm->images_upload("AlbumID=".UPLOAD_ALBUM_ID, "File=".UPLOAD_DIRECTORY . $file, "FileName=".$file);
			var_dump($response);
			echo ("\nUploaded: " . $file . "\n");
		}
		else {
			echo ("\n{$file} already exists in the album");
		}
	}
}

function getFiles($directory){
	$files = scandir($directory);
	$files = array_diff($files, array(".", ".."));
	return $files;
}

