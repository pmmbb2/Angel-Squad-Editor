<?php 
$image = $_REQUEST['image'];
$fileName = $_REQUEST['fileName'];

$location = $_REQUEST['destinationDir'];//"./game_assets/paths/";

$image_parts = explode(";base64,", $image);

$image_base64 = base64_decode($image_parts[1]);

$filename = $fileName.'.png';

$file = $location . '/' . $filename;

if( file_put_contents($file, $image_base64) !== false ):
	$im = imagecreatefrompng( $file );
	
	echo 'im: '.$im.'\r\n';
	
	$cropped = imagecropauto($im, IMG_CROP_DEFAULT);
	
	echo 'cropped: '.$cropped.'\r\n';
	
	if ($cropped !== false) { // in case a new image resource was returned
		imagedestroy($im);    // we destroy the original image
		//$im = $cropped;       // and assign the cropped image to $im
		@file_put_contents($file, $cropped);		
		echo '\r\nimage has been cropped';
	}else{
		//file_put_contents($file, $image);
		echo '\r\nimage was not cropped';
		echo $file;
	}

endif;



