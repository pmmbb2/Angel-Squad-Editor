<?php
/*
	campaigns EDITOR

	
	THIS FILE DOES THE FOLLOWING:
		create a campaign file
		update a campaign file
		read a campaign file
		delete a campaign file
		save/update to ./campaign/campaign.json	
		
		display a list of campaign files
*/
require "../common-functions.php";
$str = '';

?>

<div id='cmp_ed_wrapper'>
	<div id="cmp-ed-tabs">
	<form id='cmp_ed_frm' name='cmp_ed_frm'>
	  <ul>
		<li><a href="#cmp-ed-tabs-1">campaign</a></li>
		<li><a href="#cmp-ed-tabs-2">stages</a></li>
		<li><a href="#cmp-ed-tabs-3">sprites</a></li>					
		<li><a href="#cmp-ed-tabs-4">brickwalls</a></li>  
		<li><a href="#cmp-ed-tabs-5">entities</a></li>		
		<li><a href="#cmp-ed-tabs-6">paths</a></li>
		<li><a href="#cmp-ed-tabs-7">sounds</a></li>		
		<li><a href="#cmp-ed-tabs-8">campaigns</a></li>
	  </ul>
		<div id="cmp-ed-tabs-1">
			<div id="campaign_inputs">
				<label>dest. dir.:</label><input type='text' id='campaigns_destination_dir' name='destination_dir' class='destination_dir' value='' title='where the file will be saved' readonly='readonly'/><br />
				<!--  ! SECOND INPUT WILL ALWAYS GIVE THE NAME TO THE JSON FILE -->
				<label for='campaign_name'>name:</label><input type='text' id='campaign_name' name='name' value='' title='name of the objet and of the file' /><br />			
				<label for='campaign_title'>title:</label><input type='text' id='campaign_title' name='title' value='' title='title for the campaign' /><br />			
				<label for='campaign_description'>description</label><textarea id='campaign_description' name='description' title='' class='customStyle'></textarea><br />
				
				<!--<div id='expProgressLog'>
					<span id='loadingStatus'></span><br/>
					<div id='importLog'></div><br/>		
				</div>-->
				
			</div>
		</div>	  
	  
		<div id="cmp-ed-tabs-2">
			<a href='#' id='generateCampaingStagesMenuListBtn'>stages menu list .json</a>
			<?php require('../searchForm.php');?>
			<div id='campaign_stages_list_container'>			
			<?php
			// #stages_list
				$str = list_selectable_stages();
				echo $str;
			?>
			</div>
		</div>
		<div id="cmp-ed-tabs-3">
			<?php require('../searchForm.php');?>
			<div id='campaign_sprites_list_container'>			
			<?php
			// #sprites_list
				$str = list_selectable_sprites();
				echo $str;
			?>
			</div>
		</div>
		<div id="cmp-ed-tabs-4">
			<?php require('../searchForm.php');?>
			<div id='campaign_brickwalls_list_container'>			
			<?php
			// #brickwalls_list
				$str = list_selectable_brickwalls();
				echo $str;
			?>
			</div>
		</div>
		<div id="cmp-ed-tabs-5">
			<?php require('../searchForm.php');?>
			<div id='campaign_entities_list_container'>			
			<?php
			// #entities_list
				$str = list_selectable_entities();
				echo $str;
			?>
			</div>
		</div>
		<div id="cmp-ed-tabs-6">
			<?php require('../searchForm.php');?>
			<div id='campaign_paths_list_container'>			
			<?php
			// #paths_list
				$str = list_selectable_paths();
				echo $str;
			?>
			</div>
		</div>
		<div id="cmp-ed-tabs-7">
			<?php require('../searchForm.php');?>
			<div id='campaign_sounds_list_container'>			
			<?php
			// #sounds_list
				$str = list_selectable_sounds();
				echo $str;
			?>
			</div>
		</div>
		<div id="cmp-ed-tabs-8">
			<?php require('../searchForm.php');?>
			<div id='campaigns_list_container'>			
			<?php
			// #campaigns_list
				$str = list_selectable_campaigns();
				echo $str;
			?>
			</div>
		</div>
	  
	  
	<div class='submit_reset'>	
		<input type='submit' id='save_campaign' value='save campaign' />
		<input type='reset' id='reset_campaigns_form' value='reset form' />
		</form>	
	</div>
	</div><!--<div id="cmp-ed-tabs">-->

	
	

</div>

<link href='./campaigns_editor/campaigns_editor.css' rel="stylesheet" type="text/css" />