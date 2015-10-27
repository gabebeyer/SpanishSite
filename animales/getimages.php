<?php

//PHP SCRIPT: getimages.php
Header("content-type: application/x-javascript");

//This function gets the file names of all images in the current directory
//and ouputs them as a JavaScript array
function returnimages($dirname="./images") {

	$files = array();
	$curimage=0;
	if($handle = opendir($dirname)) {
		while(false !== ($file = readdir($handle))){

			echo 'galleryarray['.$curimage.']="'.$file .'";';
			$curimage++;
			
		}

		closedir($handle);
	}
	return($files);
}

echo "var galleryarray=new Array();"; //Define array in JavaScript
returnimages() //Output the array elements containing the image file names
?>
