<?php 
function merge($filename_x, $filename_y, $filename_result, $mergeType = 0) {

    //$mergeType 0 for horizandal merge 1 for vertical merge

 // Get dimensions for specified images
 list($width_x, $height_x) = getimagesize($filename_x);
 list($width_y, $height_y) = getimagesize($filename_y);


$lowerFileName = strtolower($filename_x); 
if(substr_count($lowerFileName, '.jpg')>0 || substr_count($lowerFileName, '.jpeg')>0){
    $image_x = imagecreatefromjpeg($filename_x);    
}else if(substr_count($lowerFileName, '.png')>0){
    $image_x = imagecreatefrompng($filename_x); 
}else if(substr_count($lowerFileName, '.gif')>0){
    $image_x = imagecreatefromgif($filename_x); 
}


$lowerFileName = strtolower($filename_y); 
if(substr_count($lowerFileName, '.jpg')>0 || substr_count($lowerFileName, '.jpeg')>0){
    $image_y = imagecreatefromjpeg($filename_y);    
}else if(substr_count($lowerFileName, '.png')>0){
    $image_y = imagecreatefrompng($filename_y); 
}else if(substr_count($lowerFileName, '.gif')>0){
    $image_y = imagecreatefromgif($filename_y); 
}


if($mergeType==0){
    //for horizandal merge
     if($height_y<$height_x){
        $new_height = $height_y;

        $new_x_height = $new_height;
        $precentageReduced = ($height_x - $new_height)/($height_x/100);
        $new_x_width = ceil($width_x - (($width_x/100) * $precentageReduced));

         $tmp = imagecreatetruecolor($new_x_width, $new_x_height);
        imagecopyresampled($tmp, $image_x, 0, 0, 0, 0, $new_x_width, $new_x_height, $width_x, $height_x);
        $image_x = $tmp;

        $height_x = $new_x_height;
        $width_x = $new_x_width;

     }else{
        $new_height = $height_x;

        $new_y_height = $new_height;
        $precentageReduced = ($height_y - $new_height)/($height_y/100);
        $new_y_width = ceil($width_y - (($width_y/100) * $precentageReduced));

         $tmp = imagecreatetruecolor($new_y_width, $new_y_height);
        imagecopyresampled($tmp, $image_y, 0, 0, 0, 0, $new_y_width, $new_y_height, $width_y, $height_y);
        $image_y = $tmp;

        $height_y = $new_y_height;
        $width_y = $new_y_width;

     }

     $new_width = $width_x + $width_y;

     $image = imagecreatetruecolor($new_width, $new_height);

    imagecopy($image, $image_x, 0, 0, 0, 0, $width_x, $height_x);
    imagecopy($image, $image_y, $width_x, 0, 0, 0, $width_y, $height_y);

}else{


    //for verical merge
    if($width_y<$width_x){
        $new_width = $width_y;

        $new_x_width = $new_width;
        $precentageReduced = ($width_x - $new_width)/($width_x/100);
        $new_x_height = ceil($height_x - (($height_x/100) * $precentageReduced));

        $tmp = imagecreatetruecolor($new_x_width, $new_x_height);
        imagecopyresampled($tmp, $image_x, 0, 0, 0, 0, $new_x_width, $new_x_height, $width_x, $height_x);
        $image_x = $tmp;

        $width_x = $new_x_width;
        $height_x = $new_x_height;

     }else{
        $new_width = $width_x;

        $new_y_width = $new_width;
        $precentageReduced = ($width_y - $new_width)/($width_y/100);
        $new_y_height = ceil($height_y - (($height_y/100) * $precentageReduced));

         $tmp = imagecreatetruecolor($new_y_width, $new_y_height);
        imagecopyresampled($tmp, $image_y, 0, 0, 0, 0, $new_y_width, $new_y_height, $width_y, $height_y);
        $image_y = $tmp;

        $width_y = $new_y_width;
        $height_y = $new_y_height;

     }

     $new_height = $height_x + $height_y;

     $image = imagecreatetruecolor($new_width, $new_height);

    imagecopy($image, $image_x, 0, 0, 0, 0, $width_x, $height_x);
    imagecopy($image, $image_y, 0, $height_x, 0, 0, $width_y, $height_y);

}

$lowerFileName = strtolower($filename_result); 
if(substr_count($lowerFileName, '.jpg')>0 || substr_count($lowerFileName, '.jpeg')>0){
    imagejpeg($image, $filename_result);
}else if(substr_count($lowerFileName, '.png')>0){
    imagepng($image, $filename_result);
}else if(substr_count($lowerFileName, '.gif')>0){
    imagegif($image, $filename_result); 
}

 // Clean up
 imagedestroy($image);
 imagedestroy($image_x);
 imagedestroy($image_y);

}

/*
merge('images/h_large.jpg', 'images/v_large.jpg', 'images/merged_har.jpg',0); //merge horizontally
merge('images/h_large.jpg', 'images/v_large.jpg', 'images/merged.jpg',1); //merge vertically*/

$bgSrc = 'game_assets/stages/noscroll.png';
$bwSrc = 'game_assets/stages/nobrickwall.png';

if( isset($_REQUEST['bgSrc']) && !empty($_REQUEST['bgSrc']) ){
	$bgSrc = 'game_assets/sprites/'.$_REQUEST['bgSrc'];
}

if( isset($_REQUEST['bwSrc']) && !empty($_REQUEST['bwSrc']) ){
	$bwSrc = 'game_assets/brickwalls/'.$_REQUEST['bwSrc'];
}

$fileName = 'game_assets/stages/'.$_REQUEST['fileName'].'.png';


/*print_r($_REQUEST);
exit;
$urlRoot = 'http://localhost/breakout-phonegap-editor/';

$a = str_replace($urlRoot,'',$bgSrc);
$b = str_replace($urlRoot,'',$bwSrc);

//merge('game_assets/brickwalls/turtle_shell.bw.png', 'game_assets/sprites/grass_river.BG.sprt.png', 'game_assets/stages/myStage5.stg.png',1);
*/
//merge($a, $b, $fileName,1);
merge($bgSrc, $bwSrc, $fileName,1);
/*
print $a;
print $b;*/
?>