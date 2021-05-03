<html>
<body>
<?php
/*
	SOUNDs EDITOR

	
	THIS FILE DOES THE FOLLOWING:
	
		display a list of SOUNDS loop files	
*/
require "../common-functions.php";
?>
	  
<div id='sounds_list_container'>
	<?php
	// .bgms_list
		$str =  list_selectable_sounds();
		echo $str;
	?>
</div>

<link href='./sounds_editor/sounds_editor.css' rel="stylesheet" type="text/css" />

</body>
</html>