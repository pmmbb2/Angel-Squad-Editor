<?php
/*

	GENERATE [campaignFileName.cmp.json].zip FILE in ./campaign_zips/
	IT CONTAINS ALL FILES RELATIVE TO A CAMPAIGN
	IT MUST BE EXTRACTED IN THE ROOT DIR OF THE APP
	
	1 - extract the .zip in the root dir of APP
	2 - use ./dexie_db_export.html to generate .exp.json file	
	3 - launch APP to populate App's DB
	
	
		THE ARCHIVE CONTAINS:
			campaignFileName.cmp.json
			+ all files from all collections listed in campaignFileName.cmp.json
				sprites, entities, sounds, paths, stages
				+ images listed in sprites' entries
				+ thumbnails extracted from sprites & brickwalls
				+ game_assets/campaignStageMenus/stages-menu-select-campaignFileName.cmp.json
				
				
		ARE NOT INCLUDED:
			SOUND FX FILES,
			SEE IN APP's index.html FILE 
			
			soundsToLoad = [
				'angelShoutPunch.mp3',
				'ArrowSwoosh.mp3',
				'bounce1.mp3',
				'bounce2.mp3',
				'tik.mp3',
				'punchAMinor.mp3',
				'cartoonPunchWhack.mp3',
				'gotStar.mp3',
				'kid_ouch.mp3',
				'angel-choirs2s.mp3'
			]


usage of this script:

localhost/breakout-phonegap-editor/zipCreate.php?campaignFileName=shortCmp.cmp.json

OR USING $_POST in AJAX QUERY




*/


/*
	$json = $_REQUEST['campaignFileName'] i.e. [campaignFileName].cmp.json
	
	open/create  $zip
	$filename = [campaignFileName]
	
		parse $json
		for each entry
			get file, add it to $zip
		endforeach
*/
/* 
	$includeAssetsJson
	false = copy .sprt.json,.ent.json,.stg.json, ... into archive
	true = do not copy
	NOTE: all asset definition data are included in [campaignFileName].exp.json file
*/
$includeAssetsJson = false;

// DECLARE $zip
$zip = new ZipArchive();

// SELECT CAMPAIGN FILE FROM $_GET['campaignFileName'] OR $_POST['campaignFileName'] 
$filename = $_REQUEST['campaignFileName'];// ?campaignFileName = myFirstCampaign.cmp.json

// CREATE $zip
if ($zip->open('./campaign_zips/'.$filename.".zip", ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
}


/*  COPY CAMPAIGN .cmp FILE  */

// CAMPAIGNS .exp FILES FOLDER (contains all .json assets definitions)
$dir = 'campaignExp_files/';
// ADD [campaignFileName].cmp.json TO $zip
$expFileName = str_replace('.cmp','.exp',$filename);
$zip->addFile($dir .$expFileName ,$expFileName);

// CAMPAIGNS FILES FOLDER
$dir = 'game_assets/campaigns/';
// ADD [campaignFileName].cmp.json TO $zip
$zip->addFile($dir . "/". $filename, $filename);


/*  COPY stages-menu-select FILE  */
// e.g.: game_assets/campaignStageMenus/stages-menu-select-campaignFileName.cmp.json

$dir2 = 'game_assets/campaignStageMenus/';

if( file_exists($dir2.'/stages-menu-select-'.$filename ) ):
	$zip->addFile($dir2.'/stages-menu-select-'.$filename, 'stages-menu-select-'.$filename);
endif;

//$zip->addFromString("ArchiveFilesList.txt", ".\n");

$strJsonFileContents = file_get_contents($dir . "/". $filename);
// Convert to array 
$array = json_decode($strJsonFileContents, true);

// THESE PROPERTIES WILL NOT BE PARSED
$firstLevelProperties = array(
	'destination_dir',
	'name',
	'title',
	'description'	
);

$getThumbnails = array(	
	'sprites',
	'entities',
	'brickwalls'
);

//var_dump($array); // print array
foreach ($array as $file => $collection):

	if( in_array($file, $firstLevelProperties) == false ):
			
		print '<h4>'.$file.'</h4>\r\n<br />';
		foreach ($collection as $item):
		
			//print $item.'\r\n<br />';
			print $dir . "/". $item.".json".'<br />';
		
			// ADD FILE FROM COLLECTION TO $zip
			$dir = 'game_assets/'.$file;
			
			$ext = ($file == 'sounds')?'.mp3':'.json';
			
			$targetDir = ($file == 'sounds')?'game_assets/sounds/':'';
			
			/*
				ALWAYS COPY sound files from /sounds dir
				COPY .sprt.json, .ent.json, .stg.json, ...
				ONLY IF $includeAssetsJson === true
			*/
			if($includeAssetsJson === true || $file == 'sounds'){			
				$zip->addFile($dir . "/". $item.$ext, $targetDir.$item.$ext);
			}
			/*
				IF COLLECTION IS 'sprites'
				FETCH AND COPY IMAGE FILES FROM game_assets/images
				USING 'src' PROPERTY
			*/
			if ($file == 'sprites'):				
				if(file_exists($dir . "/". $item.$ext)):
					$strJsonFiles = file_get_contents($dir . "/". $item.$ext);
					$arr = json_decode($strJsonFiles, true);
					if( strpos($arr['src'],'?') !== false):
						$srcArr	= explode('?', $arr['src']);
						$src = $srcArr[0];
					else:
						$src = $arr['src'];
					endif;					
					print 'image src: '.$src.'<br />';							
					// ADD IMAGE FILE TO $zip
					$zip->addFile($src, $src);				
				endif;				
			endif;

			if( in_array($file, $getThumbnails) == true ):
				$ext = '.png';
				$src = $dir . "/". $item.$ext;// 'game_assets/entities/car.ent.png';
				$tgt = "game_assets/thumbnails/". $item.$ext;// 'game_assets/entities/car.ent.png';
				$zip->addFile($src, $tgt);	
			endif;
			
		endforeach;
		
	endif;
	
endforeach;

echo "numfiles: " . $zip->numFiles . "\n";
echo "status:" . $zip->status . "\n";

$zip->close();
?>