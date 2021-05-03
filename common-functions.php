<?php

/*
	function list_files( $dirname, $file_types )	
	DOES: list files that have are from an accepted type in directory
	PARAMS: $dirname - directory
			$file_types - accepted file types
			$format - return format, i.e.: json | string
	examples: 	
	list_files( "game_assets/images/", 'png,jpeg,jpg,gif', 'json' );
*/
function list_files( $dirname, $file_types, $format, $keywords0 ){		
	$files = glob('../'."$dirname*.{".$file_types."}", GLOB_BRACE);		
	$image_file_types = array('png,jpeg,jpg,gif');
	$result = array();
	
	//$keywords = array('bow','angel');
	
	if ( strlen( $keywords0 ) > 0 ){		
		$keywords = explode( ',',$keywords0 );
	}else{
		$keywords = array();
	}
	
	//var_dump($keywords);
	
	foreach($files as $file){		
		$file_url = substr($file,3,strlen($file)-2);// GET RID OF ../

		$addToList = false;
		$n = 0;
		
		if( count($keywords) > 0 ){
			while( $n < count($keywords)-1 ){
				if( stripos($file_url, $keywords[$n]) != false ){
					$addToList = true;
					break;
				}			
				$n++;
			}
			
			if( $addToList == false ){continue;}
		}
		
		$img_tag = '<img src="'.$file_url.'" />';		
		$href_img_tag = '<a href="'.$file_url.'" target="_blank"><img src="'.$file_url.'" /></a>';		
		$href = '<a href="'.$file_url.'" target="_blank">'.$file_url.'</a>';
		// IMAGES		
		if( in_array($file_types,$image_file_types) ){						
			if( $format == 'string' ){
				// return anchor image tag 			
				array_push($result, $href_img_tag);
			}else if( $format == 'json' ){
				// return simple URL			
				array_push($result, $file_url);
			}	
		}else{
		// NOT IMAGES`
			// return a simple anchor to the resource 
			if( $format == 'string' ){
				// return anchor image tag 			
				array_push($result, $href);
			}else if( $format == 'json' ){
				// return simple URL			
				array_push($result, $file_url);
			}
		}		
	}// end foreach
	
	
	if( $format == 'json' ){		
		return json_encode( $result );
	}else if( $format == 'string' ){
		return implode('**',$result);
	}	
}

/*
	LIST SELECTABLE IMAGES
	DOES : MAKES A LIST OF SELECTABLE IMAGES 
*/
function list_selectable_images(){
	/*
		LIST IMAGES IN DIRECTORY: ../game_assets/images	
	*/	
	$dirname = "game_assets/images/";
	$file_types = 'png,jpeg,jpg,gif';
		
	$keywords = '';
	
	if( isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ){
		$keywords = $_REQUEST['keywords'];		
	}
	
	$collection = json_decode(list_files( $dirname, $file_types, 'json', $keywords ) ); 
	// IMG: FOR OVERRIDING CACHE
	$date = new DateTime();
	$now = $date->getTimestamp();
	
	$ul = '';
	$ul .= '<ul class="images_list">';
	foreach ($collection as $entry => $url):
		$ul .= '<li>';
		$ul .= '<a href="#" class="select_image"><img src="'.$url.'?t='.$now.'" /></a>';
		$ul .= '</li>';
	endforeach;
	$ul .= '</ul>';	
	return $ul;		
}

/*
	LIST SELECTABLE SPRITES
	DOES : MAKES A LIST OF SELECTABLE SPRITES 
*/
function list_selectable_sprites(){
	/*
		LIST IMAGES IN DIRECTORY: ../game_assets/images	
	*/	
	$dirname = "game_assets/sprites/";
	$file_types = 'json';
	
	$keywords = '';
	
	if( isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ){
		$keywords = $_REQUEST['keywords'];		
	}
	
	$collection = json_decode(list_files( $dirname, $file_types, 'json', $keywords ) ); 
	
	$ul = '';
	$ul .= '<ul class="sprites_list">';	
	
	// IMG: FOR OVERRIDING CACHE
	$date = new DateTime();
	$now = $date->getTimestamp();
	
	foreach ($collection as $entry => $val):	
		
		$val_array = explode('/',$val);		
		$name_array = explode('.',$val_array[count($val_array)-1]);
		array_pop($name_array);
		$name = implode('.',$name_array);
		$img = $dirname.$name.".png?t=".$now;	
		$ul .= '<li>';		
		//$img = $dirname.$name_array[0].".png?t=".$now;
		$ul .= '<a href="'.$val.'?t='.$now.'" class="select_sprite" >';		
		$ul .= '<img src="'.$img.'" title="'.$name.'" />';
		$ul .= '<span class="spriteLbl">'.$name.'</span></a>';
		$ul .= '<a href="'.$val.'?t='.$now.'" class="preview_sprite" ><div class="eye"></div></a>';	
		
		$ul .= '<a href="'.$val.'" class="delete_sprite" >&times;</a>';
		$ul .= '</li>';
	endforeach;
	$ul .= '</ul>';	
	return $ul;		
}


/*
	LIST SELECTABLE PATHS
	DOES : MAKES A LIST OF SELECTABLE PATHS 
*/
function list_selectable_paths(){
	/*
		LIST PATHS IN DIRECTORY: ../game_assets/paths	
	*/	
	$dirname = "game_assets/paths/";
	$file_types = 'json';
	$keywords = '';
	
	if( isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ){
		$keywords = $_REQUEST['keywords'];	
	}
	
	$collection = json_decode(list_files( $dirname, $file_types, 'json', $keywords ) ); 

	$ul = '';
	$ul .= '<ul class="paths_list">';
	
	// IMG: FOR OVERRIDING CACHE
	$date = new DateTime();
	$now = $date->getTimestamp();
	
	foreach ($collection as $entry => $val):
	
		$val_array = explode('/',$val);		
		$name_array = explode('.',$val_array[count($val_array)-1]);
		array_pop($name_array);
		$name = implode('.',$name_array);
		$img = $dirname.$name.".png?t=".$now;	
		
		// get the name with dots, but without .json
		// e.g.: path.rel
		//$fileNameWithDots = substr($val_array[count($val_array)-1], 0, strlen($val_array[count($val_array)-1])-5);				
		$ul .= '<li>';		
		$ul .= '<a href="'.$val.'" class="select_path" >';		
		$ul .= '<img src="'.$img.'" title="'.$name.'" />';		
		$ul .= '<span class="pathLbl">'.$name.'</span>';
		$ul .= '<span class="jsonObjectPathMode">'.getAttributeFromJsonFile($val,'pathMode').'</span>';		
		$ul .= '<span class="jsonObjectDescription">'.getAttributeFromJsonFile($val,'description').'</span>';
		$ul .= '</a>';
		$ul .= '<a href="'.$val.'" class="delete_path" >&times;</a>';
		$ul .= '</li>';
	endforeach;
	$ul .= '</ul>';	
	return $ul;		
}

/*
	LIST SELECTABLE BRICKWALLS
	DOES : MAKES A LIST OF SELECTABLE BRICKWALLS 
*/
function list_selectable_brickwalls(){
	/*
		LIST BRICKSWALLS IN DIRECTORY: ../game_assets/brickwalls	
	*/	
	$dirname = "game_assets/brickwalls/";
	$file_types = 'json';
	$keywords = '';
	
	if( isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ){
		$keywords = $_REQUEST['keywords'];		
	}
	
	$collection = json_decode(list_files( $dirname, $file_types, 'json', $keywords ) ); 

	$ul = '';
	$ul .= '<ul class="brickwalls_list">';
	
	// IMG: FOR OVERRIDING CACHE
	$date = new DateTime();
	$now = $date->getTimestamp();
	
	foreach ($collection as $entry => $val):	
		$val_array = explode('/',$val);		
		$name_array = explode('.',$val_array[count($val_array)-1]);
		array_pop($name_array);
		$name = implode('.',$name_array);
		$img = $dirname.$name.".png?t=".$now;
		// get the name with dots, but without .json
		// e.g.: path.rel
		//$fileNameWithDots = substr($val_array[count($val_array)-1], 0, strlen($val_array[count($val_array)-1])-5);				
		$ul .= '<li>';		
		$ul .= '<a href="'.$val.'" class="select_brickwall" >';		
		$ul .= '<img src="'.$img.'" title="'.$name.'" />';		
		$ul .= '<span class="BWLbl">'.$name.'</span>';
		$ul .= '<span class="jsonObjectDescription">'.getAttributeFromJsonFile($val,'description').'</span>';
		$ul .= '</a>';
		
		$ul .= '<a href="'.$val.'" class="delete_brickwall" >&times;</a>';
		$ul .= '</li>';
	endforeach;
	$ul .= '</ul>';	
	return $ul;		
}

/*
	LIST SELECTABLE ENTITIES
	DOES : MAKES A LIST OF SELECTABLE ENTITIES 
*/
function list_selectable_entities(){
	/*
		LIST ENTITIES IN DIRECTORY: ../game_assets/entities	
	*/	
	$dirname = "game_assets/entities/";
	$file_types = 'json';
	$keywords = '';
	
	if( isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ){
		$keywords = $_REQUEST['keywords'];		
	}
	
	$collection = json_decode(list_files( $dirname, $file_types, 'json', $keywords ) ); 
	
	$ul = '';
	$ul .= '<ul class="entities_list">';	
	
	// IMG: FOR OVERRIDING CACHE
	$date = new DateTime();
	$now = $date->getTimestamp();
	
	foreach ($collection as $entry => $val):	
		$val_array = explode('/',$val);
		$name_array = explode('.',$val_array[count($val_array)-1]);
		array_pop($name_array);
		$name = implode('.',$name_array);
		
		
		$ul .= '<li>';		
		$img = $dirname.$name.".png";//?t=".$now;
		$ul .= '<a href="'.$val.'?t='.$now.'" class="select_entity" >';		
		$ul .= '<span class="entityThumb"><img src="'.$img.'" title="'.$name.'" /></span>';			
		$ul .= '<span class="entityLbl">'.$name.'</span>';
		$ul .= '<span class="jsonObjectDescription">'.getAttributeFromJsonFile($val,'description').'</span>';		
		$ul .= '</a>';
		
		$ul .= '<a href="'.$val.'" class="delete_entity" >&times;</a>';
		$ul .= '</li>';
	endforeach;
	$ul .= '</ul>';	
	return $ul;		
}

/*
	LIST SELECTABLE STAGES
	DOES : MAKES A LIST OF SELECTABLE STAGES 
*/
function list_selectable_stages(){
	/*
		LIST ENTITIES IN DIRECTORY: ../game_assets/stages	
	*/	
	$dirname = "game_assets/stages/";
	$file_types = 'json';
	$keywords = '';
	
	if( isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ){
		$keywords = $_REQUEST['keywords'];		
	}
	
	$collection = json_decode(list_files( $dirname, $file_types, 'json', $keywords ) ); 
	
	$ul = '';
	$ul .= '<ul class="stages_list">';	
	
	// IMG: FOR OVERRIDING CACHE
	$date = new DateTime();
	$now = $date->getTimestamp();
	
	foreach ($collection as $entry => $val):	
		$val_array = explode('/',$val);
		$name_array = explode('.',$val_array[count($val_array)-1]);
		array_pop($name_array);
		$name = implode('.',$name_array);
		
		
		$ul .= '<li>';		
		$img = $dirname.$name.".png";//?t=".$now;
		$ul .= '<a href="'.$val.'?t='.$now.'" class="select_stage" >';		
		$ul .= '<span class="stageThumb"><img src="'.$img.'?t='.$now.'" title="'.$name.'" /></span>';			
		$ul .= '<span class="stageLbl">'.$name.'</span>';
		$ul .= '<span class="jsonObjectDescription">'.getAttributeFromJsonFile($val,'description').'</span>';		
		$ul .= '</a>';
		
		$ul .= '<a href="'.$val.'" class="delete_stage" >&times;</a>';
		$ul .= '</li>';
	endforeach;
	$ul .= '</ul>';	
	return $ul;		
}


function list_selectable_sounds(){
	/*
		LIST ENTITIES IN DIRECTORY: ../game_assets/stages	
	*/	
	$dirname = "game_assets/sounds/";
	$file_types = '*';
	$keywords = '';
	
	if( isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ){
		$keywords = $_REQUEST['keywords'];		
	}
	
	$collection = json_decode(list_files( $dirname, $file_types, 'json', $keywords ) ); 
	
	$ul = '';
	$ul .= '<ul class="sounds_list">';	
	
	// IMG: FOR OVERRIDING CACHE
	$date = new DateTime();
	$now = $date->getTimestamp();
	
	foreach ($collection as $entry => $val):
		
		// ADD ONLY LOOPS TO THE SELECTABLE LIST ITEMS
		if( stripos($val,'loop') == false ){continue;}
		
		$val_array = explode('/',$val);
		$name_array = explode('.',$val_array[count($val_array)-1]);
		array_pop($name_array);
		$name = implode('.',$name_array);		
		$ul .= '<li>';				
			$ul .= '<a href="'.$val.'?t='.$now.'" class="select_bgm" >';				
			$ul .= '<span class="bgmLbl">'.$name.'</span>';		
			$ul .= '</a>';
			$ul .= '<audio id="'.$name.'" src="'.$val.'?t='.$now.'" ></audio>';
			$ul .= '<a href="'.$name.'" class="play_bgm" >';				
			//$ul .= ' <span class="icon">play</span>';		
			$ul .= '</a>';
			$ul .= '<a href="'.$name.'" class="stop_bgm" >';				
			//$ul .= ' <span class="icon">stop</span>';		
			$ul .= '</a>';
		$ul .= '</li>';
	endforeach;
	$ul .= '</ul>';	
	return $ul;		
}


/*
	LIST SELECTABLE CAMPAIGNS
	DOES : MAKES A LIST OF SELECTABLE CAMPAIGNS 
*/
function list_selectable_campaigns(){
	/*
		LIST CAMPAIGNS IN DIRECTORY: ../game_assets/campaigns	
	*/	
	$dirname = "game_assets/campaigns/";
	$file_types = 'json';
	$keywords = '';
	
	if( isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ){
		$keywords = $_REQUEST['keywords'];		
	}
	
	$collection = json_decode(list_files( $dirname, $file_types, 'json', $keywords ) ); 
	
	$ul = '';
	$ul .= '<ul class="campaigns_list">';	
	
	// IMG: FOR OVERRIDING CACHE
	$date = new DateTime();
	$now = $date->getTimestamp();
	
	foreach ($collection as $entry => $val):	
		$val_array = explode('/',$val);
		$name_array = explode('.',$val_array[count($val_array)-1]);
		array_pop($name_array);
		$name = implode('.',$name_array);
				
		$ul .= '<li>';		
		$img = $dirname.$name.".png";//?t=".$now;
		$ul .= '<a href="'.$val.'?t='.$now.'" class="export_campaign" >';
			$ul .= '<span class="campaignLbl">export zip</span>';		
		$ul .= '</a>';
		
		$ul .= '<a href="'.$val.'?t='.$now.'" class="select_campaign" >';		
		/*$ul .= '<span class="stageThumb"><img src="'.$img.'" title="'.$name.'" /></span>';	*/
			$ul .= '<span class="jsonObjectDescription">'.getAttributeFromJsonFile($val,'title').'</span>';		
			$ul .= '<span class="campaignLbl">'.$name.'</span>';		
			$ul .= '<span class="jsonObjectDescription">'.getAttributeFromJsonFile($val,'description').'</span>';		
		$ul .= '</a>';
		
		$ul .= '<a href="'.$val.'" class="delete_campaign" >&times;</a>';
		$ul .= '</li>';
	endforeach;
	$ul .= '</ul>';	
	return $ul;		
}



/*
	GET DESCRIPTION ATTRIBUTE FROM JSON FILES

function getDescriptionFromJsonFile($fileUrl){
	$json = file_get_contents('../'.$fileUrl);
	$obj = json_decode($json);
	return $obj->description;
}
*/

function getAttributeFromJsonFile($fileUrl,$attributeName){
	$json = file_get_contents('../'.$fileUrl);
	$obj = json_decode($json);
	if(!empty($obj)){$return = $obj->$attributeName;}
	else{$return = '';}
	return $return;
}
	
	
?>