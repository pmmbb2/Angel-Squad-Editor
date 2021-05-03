<?php 
$out = array();

switch($_REQUEST['selector']):
	case 'selectCmp':
		$needle = '.cmp.json';
		$lookIntoFolder = './game_assets/campaigns/*';
	break;
	case 'selectCmpExp':
		$needle = '.exp.json';
		$lookIntoFolder = './campaignExp_files/*';
	break;
endSwitch;


foreach (glob($lookIntoFolder) as $filename) {
	if( stripos( $filename, $needle ) ){
		if( stripos( $filename,'stages-menu-select-' ) == false ){
			$p = pathinfo($filename);
			$out[] = $p['basename'];
		}
	}
}
echo json_encode($out); 
?>