<?php
/*
	SPRITES EDITOR
			
		SPRITE JSON FILE CONTENT SAMPLE
		{
			name: 'boy_walk_up',// used to identify and to select which sprite to spawn
			width: 20.5,
			height: 24,			
			src: './img/boy-spritesheet-mirrored.png',
			positions: [
					// specify a few sprite locations	
					[0, 0],  
					[20.5, 0], // 20.5
					[41, 0], // 41
					[61.5, 0]
				],
			frame: 0, // default sprite frame
			frameMax: 1, // when frame == frameMax, reset sprite animation to frame 0 		
			frameTicker: 0, // ticker for switching to next sprite  frame
			nextFrameAt: 12 // when ticker == nextFrameAt, switch to next frame ('animation speed')		
		}
		
		1 sprite has attributes:
		    name: STRING,
			width: INT,
			height: INT,
			src: STRING,
			positions: [ 
				[startX, startY]
				[,...]
			] ARRAY		
	

	create a sprite file
	update a sprite	file
	read a sprite file
	delete a sprite	file
	save/update to ./sprites/spritename.json	
	
	display a list of sprites files	
*/
require "../common-functions.php";
$str = '';

?>




<div id='sprt_ed_wrapper'>	
<div id='sprt-tabs-wrapper'>
	<div id="sprt-ed-tabs">
	  <ul>
		<li><a href="#sprt-ed-tabs-1">images</a></li>
		<li><a href="#sprt-ed-tabs-2">sprites</a></li>    
	  </ul>
	  <div id="sprt-ed-tabs-1">
	  <a href="#" class="refreshImagesListBtn">refresh</a>
	  <?php require('../searchForm.php');?>
		<div id='images_list_container'>
		
		<?php
		// #images_list
			$str = list_selectable_images();
			echo $str;
		?>
		</div>
	  </div>
	  <div id="sprt-ed-tabs-2">
			<?php require('../searchForm.php');?>
			<div id='spritePreviewContainer'>
				<canvas id="sprite_preview"></canvas>
			</div>
			
			<div id='sprites_list_container'>
			<?php /*require('../searchForm.php');*/?>
			<?php
				// #sprites_list
				$str = list_selectable_sprites();
				echo $str;			
			?>
			</div> 
		</div>
	</div>
</div>

	<a href='#' id='toggleSprtImgSelectorBtn'>Sprites / Images</a>

	

	1 - name the sprite<br/> 
	2 - select a spritesheet<br/>	
	3 - spritesheet[fill in width & height to see frames]:
	<div id='sprt_ed_container_image'>
		<canvas id="sprt_ed_image_grid"></canvas>		
	</div>
<hr/>
	4 - frames:[click to add to sprite]<div id='sprt_ed_container_frames'></div>
<hr/>	
	sprite:[click to remove from sprite]<div id='sprt_ed_container_sprite'></div>
	
<hr/>	
	<fieldset>
		<legend>spritesheet parsing</legend>
		<input type='radio' name='spritesheetParsing' value='horizontal' />horizontal
		<input type='radio' name='spritesheetParsing' value='vertical' />vertical
	</fieldset>
	
	<form id='sprt_ed_frm' name='sprt_ed_frm'>
		<label>dest. dir.:</label><input type='text' id='sprites_destination_dir' class='destination_dir' name='destination_dir' value='' title='where the file will be saved' /><br />
		<!--  ! SECOND INPUT WILL ALWAYS GIVE THE NAME TO THE JSON FILE -->
		<label>name:</label><input type='text' id='sprite_name' name='name' value='' title='name of the objet and of the file' /><br />
		<label>spritesheet:</label><input type='text' id='sprite_src' name='src' value='' title='' /><br />
		<label>width:</label><input type='text' id='sprite_width' name='width' value='' title='' /><br />
		<label>height:</label><input type='text' id='sprite_height' name='height' value='' title='' /><br />
		<label>positions:<button id='generatePositions'>generate</button></label><textarea id='sprite_positions' name='positions' title=''></textarea>
		
		<!--<label for='sprite_description'>description</label><textarea id='sprite_description' name='description' title=''></textarea>-->
		
		<br />
		<label>frame:</label><input type='text' id='sprite_frame' name='frame' value='0' title='default sprite frame' /><br />
		<label>frameMax:</label><input type='text'id='sprite_frameMax' name='frameMax' value='1' title='' /><br />
		<label>frameTicker:</label><input type='text' id='sprite_frameTicker' name='frameTicker' value='0' title='' /><br />
		<label>nextFrameAt:</label><input type='text' id='sprite_nextFrameAt' name='nextFrameAt' value='12' title='' /><br />
		<label>hitboxes:<a href='#' id='generateHitboxes'>generate</a></label><textarea id='sprite_hitboxes' name='hitboxes' title=''></textarea>
		
		<!--
		frame: 0, // default sprite frame
			frameMax: 1, // when frame == frameMax, reset sprite animation to frame 0 		
			frameTicker: 0, // ticker for switching to next sprite  frame
			nextFrameAt: 12 // when ticker == nextFrameAt, switch to next frame ('animation speed')-->
		
		<input type='submit' id='save_sprite' value='save sprite' />
		<input type='reset' id='reset_form' value='reset form' />
		
	</form>	
<!--</div>-->



</div>
<!--<link href='sprites_editor/sprites_editor.css'  type="text/css" rel="stylesheet" /> -->