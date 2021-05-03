<?php
/*
	stages EDITOR

	
	THIS FILE DOES THE FOLLOWING:
		create an stage file
		update an stage	file
		read an stage file
		delete an stage	file
		save/update to ./stages/stagename.json	
		
		display a list of stages files	
*/
require "../common-functions.php";
$str = '';

?>

<div id="stage_preview_container">
	<div id='liveUpdateContainer'>
		<a href='#' class='testIframeCreate'>live edit</a>
		<a href='#' class='testIframeToggleVerbose'>toggle verbose</a><br /><br />		
		<a href='#' class='testIframeStageLoad'>run</a>
		<a href='#' class='testIframeStagePause'>pause</a>
		<br />
		<input type='text' title="go to specific time" value='0' id='goToTimeUnit' name='goToTimeUnit' /><br /><br />
		<a href='#' class='liveEditorAddEntityBtn'>add entity</a>
	</div>			
	<div id='stage_preview'></div>
</div>

<div id="stage_timeline_container">
	<div id='timeline_controls'>
		<input type='text' id='stageNthFrameC' />
		<input type='text' id='timeUnitC' />	
		<a href='#' id='playTimeLineBtn'>&#9658</a>
		<a href='#' id='pauseTimeLineBtn'>&#10073; &#10073;</a>
		<a href='#' id='stopTimeLineBtn'>&#9726</a>
		
		<a href='#' id='stepDown'>&#9665</a>
		<a href='#' id='stepUp'>&#9655</a>
	</div>
	<div id='stage_timeline'>
		<canvas id='timeline_canvas' width='400' height='50'></canvas>  
	</div>	
</div>



<div id='stg_ed_wrapper'>

	

	<div id="stg-ed-tabs">
	<form id='stg_ed_frm' name='stg_ed_frm'>
	  <ul>
		<li><a href="#stg-ed-tabs-1">stage</a></li>
		<li><a href="#stg-ed-tabs-2">objectives</a></li>					
		<li><a href="#stg-ed-tabs-3">BW</a></li>  
		<li><a href="#stg-ed-tabs-4">entities</a></li>		
		<li><a href="#stg-ed-tabs-5">BG/scrolling</a></li>
		<li><a href="#stg-ed-tabs-6">BGM</a></li>
		<li><a href="#stg-ed-tabs-7">stages</a></li>
	  </ul>
	  
	  <div id="stg-ed-tabs-1">
			<h4>Stage description</h4>
			<br />
		 <div id="stage_idstage_inputs">
			<label>dest. dir.:</label><input type='text' id='stages_destination_dir' name='destination_dir' class='destination_dir' value='' title='where the file will be saved' readonly='readonly'/><br />
			<!--  ! SECOND INPUT WILL ALWAYS GIVE THE NAME TO THE JSON FILE -->
			<label for='stage_name'>name:</label><input type='text' id='stage_name' name='name' value='' title='name of the objet and of the file' /><br />			
			<label for='stage_description'>description</label><textarea id='stage_description' name='description' title='description og the stage' class='customStyle'></textarea><br />
			<label for='stage_briefing'>stage briefing</label><textarea id='stage_briefing' name='briefing' title=stage briefing' class='customStyle'></textarea><br />			
			<br />
			<h4>Entity cannons</h4>
			<label for='stage_entityCannons'>entity cannons</label><textarea id='stage_entityCannons' name='stage_entityCannons' title='' class='customStyle'></textarea><br />	
			<br />
			<h4>Spawned balls / Escortees</h4>
			<label for='spawnBalls'>spawnBalls</label><textarea id="spawnBalls" name="spawnBalls"></textarea>
			<br />
			<label for='maxCasualties'>maxCasualties</label><input id="maxCasualties" name="maxCasualties" type="text" />
		</div>
	  </div>
	  
	  <div id="stg-ed-tabs-2"> 
		<div id='stage_objectives'>			
			<label for='objectives'>
				objectives			
				<a id="noObjectiveBtn">no</a>
			</label>
			<textarea id='objectives' name='objectives' title='' class='customStyle'></textarea><br />				
			<a href='#' id="addObjectiveBtn">add objective</a>
			<a href='#' id="updateStageObjectivesBtn">update</a>
		</div>
	  </div>	  
	  
	  <div id="stg-ed-tabs-3">
		<div id="stage_brickwall_inputs">				
			<label for='stage_BW_drawOnCanvas'>drawOnCanvas:</label><br />
			<label for='stage_brickwall_name'>brickwall:</label><input type='text' id='stage_brickwall_name' name='stage_brickwall_name' value='' title='' /><br />
			<label for='stage_BW_x'>x:</label><input type='text' id='stage_BW_x' name='BW_x' value='' title='' />
			<label for='stage_BW_y'>y:</label><input type='text' id='stage_BW_y' name='BW_y' value='' title='' />
			<label for='stage_BW_dx'>dx:</label><input type='text' id='stage_BW_dx' name='BW_dx' value='' title='' />
			<label for='stage_BW_dy'>dy:</label><input type='text' id='stage_BW_dy' name='BW_dy' value='' title='' />	
			<label for='stage_BW_path'>path:</label><input type='text' id='stage_BW_path' name='BW_path' value='' title='' /><br/>			
			<label for='stage_BW_pathMode'>pathMode:</label><input type='text' id='stage_BW_pathMode' name='BW_pathMode' value='' title='' />			
			<label for='stage_BW_pathEnd'>pathEnd:</label><input type='text' id='stage_BW_pathEnd' name='BW_pathEnd' value='' title='' />			
			<label for='stage_BW_pathStep'>pathStep:</label><input type='text' id='stage_BW_pathStep' name='BW_pathStep' value='' title='' />
			<label for='stage_BW_pathIdx'>pathIdx:</label><input type='text' id='stage_BW_pathIdx' name='BW_pathIdx' value='' title='' />			
			<label for='stage_BW_states'>states:</label><textarea id='stage_BW_states' name='BW_states' title='' ></textarea>			
			<label for='stage_BW_firstStateAt'>firstStateAt:</label><input type='text' id='stage_BW_firstStateAt' name='BW_firstStateAt' value='' title='' /><br />  
			<label id="openBWStatesManagerLbl" for='openBWStatesManager'>states:</label>	
		</div>
	  </div>
	  
	  <div id="stg-ed-tabs-4">	  
		<div id='stage_ec_manager_container'>
			<div id='stage_ec_manager_controls'>
				entity cannons
				<!--<a href="#" id="stageEntityBtn"><span class="ui ui-icon ui-icon-plus ui-corner-all"></span>add</a>				
				<a href='#' id='stage_ec_manager_refresh'>refresh</a>
				<a href='#' id='ECResetStartBtn'>reset ECs start</a>
				<a href='#' id='toggle_timeline_controls'>timeline</a>-->
				<a href="#" id="stageEntityBtn"><span class="ui ui-icon ui-icon-plus ui-corner-all"></span>add</a>				
				<a href='#' id='stage_ec_manager_refresh'></a>
				<a href='#' id='ECResetStartBtn' title="reset ECs start">ECs</a>
				<a href='#' id='toggle_timeline_controls'></a>
				
				<fieldset id="emptyFramesControl">
					<legend>empty frames</legend>
					<a href='#' id='hideEmptyStageFrames'>hide</a>
					<a href='#' id='showAllStageFrames'>show</a>
				</fieldset>
				
				<div id='spawnBallsContainer'>
					spawnBalls
					<a href='#' class='addSpawnBall'><span class="ui ui-icon ui-icon-plus ui-corner-all"></span>add</a>
					<ul id='spawnBallsList'></ul>
				</div>
				
				
				
			</div>
			
			<div id='stage_ec_manager'></div>
		</div>		
		
	  </div>
	  
	  <div id="stg-ed-tabs-5"> 
		<div id='stage_scrollings'>
			
			<br />
			<label for='scrollingScreens'>			
			scrollingScreens
				<a id="noScrollingBtn">no</a>
				
			</label>
			<textarea id='scrollingScreens' name='scrollingScreens' title='' class='customStyle'></textarea><br />				
			<a href='#' id="addParallaxBtn">add parallax</a>
			<a href='#' id="updateStageScrollingScreensBtn">update</a>			
			
		</div>
	  </div>
	  
	  <div id="stg-ed-tabs-6">
			music
			<label for='stage_music'></label>
			<input type='text' id='stage_music' name='music' title='stage music loop file' placeholder='click to select background music' />				
	  </div>	
	  	  
	  <div id="stg-ed-tabs-7"> 
		<?php require('../searchForm.php');?>
			<div id='stages_list_container'>
			
			<?php
			// #stages_list
				$str = list_selectable_stages();
				echo $str;
			?>
			</div>
	  </div>
	  
	  <input type='submit' id='save_stage' value='save stage' />
		<input type='reset' id='reset_stages_form' value='reset form' />
		</form>	
	</div><!--<div id="stg-ed-tabs">-->

	
	

</div>

<link href='./stages_editor/stages_editor.css' rel="stylesheet" type="text/css" />