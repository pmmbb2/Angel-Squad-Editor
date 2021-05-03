<?php
/*
	GEENRATE THE FILE [campaignName].exp.json
	
	RECEIVE fileName by POST or GET
	RECEIVE JSON DATA FROM THE REQUEST BODY
	WRITE THE FILE $fileName
*/

	$fileName = $_REQUEST['fileName'];	
	$result = file_put_contents('campaignExp_files/'.$fileName, file_get_contents('php://input'));	
	if($result !== false ){
		echo json_encode(array("result"=>"success"));
	}else{
		echo json_encode(array("result"=>"error"));
	}
	
/*
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('file.txt'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('file.txt'));
    readfile('file.txt');
    exit;*/
?>	