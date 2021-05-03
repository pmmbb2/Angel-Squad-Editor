<?php
$fileUrl = $_POST['fileUrl'];
   if ($fileUrl != null){
		if( 
			empty($fileUrl) || !isset($fileUrl) 		
		){
		   echo json_encode(array('type'=>'error','msg'=>'file URL missing'));
		   die();
		}
		else{			
			if( unlink($fileUrl) ){
				
				$feedback = json_encode(
				array(
					'type'=>'success',
					'msg'=>'file "'.$fileUrl.'" deleted successfully!')
				);			
			}else{
				$feedback = json_encode(
				array(
					'type'=>'failure',
					'msg'=>'file "'.$fileUrl.'" could not be deleted!')
				);
			}			
			echo $feedback;
			die();
		
		}
   }
?>