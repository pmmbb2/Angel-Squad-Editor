<?php

   $json = $_POST['json'];
   if (json_decode($json) != null){
   $json = json_decode($json);
   
	   if( $json->destination_dir == 'game_assets/sprites' ){			
			if( 
				   empty($json->width) || !isset($json->width) 		
				|| empty($json->height) || !isset($json->height) 		
				|| empty($json->name) || !isset($json->name) 		
				|| empty($json->src) || !isset($json->src) 		
				|| empty($json->positions) || !isset($json->positions) 		
				|| $json->frame =='' || !isset($json->frame) 		
				|| $json->frameMax =='' || !isset($json->frameMax) 		
				|| $json->frameTicker =='' || !isset($json->frameTicker) 		
				|| $json->nextFrameAt =='' || !isset($json->nextFrameAt) 		
			){
			   echo json_encode(array('type'=>'error','msg'=>'fill in all fields, then click on [export]'));
			   die();
			}			
	   }	
   }
    

  $asset_type_array =  explode('/',$json->destination_dir);
  $asset_type = $asset_type_array[count($asset_type_array)-1];// THE LAST CHUNK is 'sprites' | 'entities' | ...
  
	
	  //$json = json_decode($json);	  
	  $data = array();	  
	  $n = 0;
	  foreach( $json as $key => $value ){
		
		if( $key != 'destination_dir'){
			if( $key == 'wallBrickMapEnergy' ){
				
				$value = stripcslashes ( $value );
				$value = str_replace('I',"'I'",$value);				
				$data[$key] = $value;
			}
			else{				
				$data[$key] = $value;
			}
		}
		else{
			$destination_dir = $value;
			$data[$key] = $value;
		}
		
		// the first data is always the destination directory of the file
		// the second data is always used to be the name of the file
		if ( $n == 1 ){
			$fileName = $value;			
		}
		$n++;
	  }
	  
	 $fileName .= '.json';	 
     $file = fopen('./'.$destination_dir.'/'.$fileName,'w+');
     fwrite($file, json_encode($data,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE| JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK)); 
     fclose($file);


	//read the entire string
	$str = file_get_contents('./'.$destination_dir.'/'.$fileName);

	/*
		turn "[...]" into [...]
	*/
	// DO NOT DO IT FOR paths NOR brickwalls
	if( $asset_type != 'paths' 
		&& $asset_type != 'brickwalls' 
	){
		$str = str_replace('"[', '[',$str);
		$str = str_replace(']"', ']',$str);	
	}
	
	
	
	if( $asset_type == 'brickwalls'  ){
		//$str = str_replace('I', "\"I\"",$str);
		//$str = addslashes($str);
	}
	
	
	/*
		"true", -> true,
		"false", -> false,
	*/	
	$str = str_replace('"true",','true,',$str);
	$str = str_replace('"false",','false,',$str);
	
	/*
		"true" -> true
		"false" -> false
	*/
	$str = str_replace('"true"','true',$str);
	$str = str_replace('"false"','false',$str);
	
	// STAGES EDITOR
	$str = str_replace('\"','"',$str);
	$str = str_replace('\\', '',$str);

/*
	if( $asset_type != 'brickwalls'  ){
		$str = str_replace(']"', ']',$str);	
	}
	*/
	
	
	
	
	//write the entire string
	file_put_contents('./'.$destination_dir.'/'.$fileName, $str);
	 
	 
	 echo json_encode(array('type'=>'success','msg'=>'file "'.$fileName.'" created successfully in "'.$destination_dir.'"'));
	 die();
   
?>