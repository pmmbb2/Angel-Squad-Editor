<?php
/*
	entities EDITOR
	
	An entity is an object, that can: 
		- move on stage
		- interract with other entities: 			
			ball -> paddle		
			ball -> brickwall
			ball -> direction changer			
			enemy -> ball
			enemy -> paddle
			paddle -> power-up
			
	An entity can represent an objet of a specific type (i.e. attribute 'type') : ...	
		- a ball - ball
		- a paddle - paddle
		- an enemy - enemy
		- a power-up - powerup
		- a decorative element - graphic
		
		... on the stage
		
	 - 1 currently displayed sprite object (spriteFrames)
	 - has 1+ sprites, 
	   each sprite is an attribute of animations{}, 
	   each sprite represents on action performed by the entity:
			eg. UP, LEFT, DOWN, RIGHT, APPEAR, DISAPPEAR, ... 
	 - has 0 | 1 path (absolute | relative) 
	 - has strength (attack)
	 - has energy (life amount)
	 - behaviour attributes, e.g.: bounceHorizontally: true | false
	 
		ENTITY JSON FILE CONTENT SAMPLE
	{	
		// IDENTITY
		name: 'entityName' (name is also used to name the json file, i.e. entityName.json) 
		type: ball | paddle | enemy | powerup | graphic (used to filter objects in sprites & balls collections)
		energy: 100,
	    strength: 5,		
		
		// POSITION ON STAGE
		x: 150, // horizontal position
		y: 50, // vertical position
		dx: 2, // horizontal vector
		dy: 1, // vertical vector,	
		
		// PATH RELATED 
		px : 0, // path x
		py : 0, // path y
		path : -1, // no path: -1 | "v.abs" // which path does the entity follow?
		pathStep : 1, // the higher, the faster the entity iterates through the PATH coords
		pathIdx : 0, // index used to iterate through path 
		pathMode : 'absolute', // move to : 'absolute' | move by 'relative' (can be combined with dx & dy)		
	   +pathEnd: loop | reverse | stop // WHAT TO DO WHEN THE ENTITY REACHES THE END OF THE PATH
		
		
		// ANIMATION RELATED
		
		// MAYBE NOT IN ENTITIES EDITOR -- these properties are set in the game engine:createSpriteFromJson()
		spriteFrames: frameObject,// default sprite(required), can be the only one 
		width: frameObject.width, // sprite frame width
		height: frameObject.height, // sprite frame 
		//
		
		animations:{
			UP: 'boyWalkUp',
			DOWN: 'boyWalkDown',
			LEFT: 'boyWalkLeft',
			RIGHT: 'boyWalkRight',
			... (animations is optional, it can be populated or null)
		},
		
		// MAYBE NOT IN ENTITIES EDITOR -- these properties are set in sprites_editor
		
			// ANIMATION FRAMES & SPEED	
			frame: frameObject.frame, // default sprite frame
			frameMax: frameObject.frameMax, // when frame == frameMax, reset sprite animation to frame 0 		
			frameTicker: frameObject.frameTicker, // ticker for switching to next sprite  frame
			nextFrameAt: frameObject.nextFrameAt, // when ticker == nextFrameAt, switch to next frame ('animation speed')
		
		//
		
		// BEHAVIOR ATTRIBUTES
		bounceHorizontally: true,
		bounceVertically: true,
		outOfHorizontalBoundaryRemove: true,
		outOfVerticalBoundaryRemove: true,
		collideWithPaddleRemove: false,// remove this on collide with paddle 
		collideWithPaddleBounce: true,
		BounceBallVertically: true,
		BounceBallHorizontally: true, 
		collideBallRemoveBall: false,// remove ball on collide
		collideBallAttacksBall: false,// on collision the sprite attacks the ball that loses energy
		collideBallLoseEnergy: false,
		collidePaddleLoseEnergy: false
	}
	
	THIS FILE DOES THE FOLLOWING:
		create an entity file
		update an entity	file
		read an entity file
		delete an entity	file
		save/update to ./entities/entityname.json	
		
		display a list of entities files	
*/
require "../common-functions.php";
$str = '';

?>

<div id='ent_ed_wrapper'>	
	
	<form id='ent_ed_frm' name='ent_ed_frm'>
		
	<div id="ent-ed-tabs">
	  <ul>
		<li><a href="#ent-ed-tabs-1">entities</a></li>
		<li><a href="#ent-ed-tabs-2">identity</a></li>
		<li><a href="#ent-ed-tabs-3">position & movement</a></li>    
		<li><a href="#ent-ed-tabs-4">animation</a></li>    
		<li><a href="#ent-ed-tabs-5">behavior</a></li>
		<!--<li><a href="#ent-ed-tabs-6">hitbox</a></li>		-->
	  </ul>
	  <div id="ent-ed-tabs-1">
	  <?php require('../searchForm.php');?>
		<div id='entities_list_container'>
		
		<?php
		// #images_list
			$str = list_selectable_entities();
			echo $str;
		?>
		</div>
	  </div>
	  <div id="ent-ed-tabs-2">
		<div id="entity_identity_inputs">
			<label>dest. dir.:</label><input type='text' id='entities_destination_dir' name='destination_dir' class='destination_dir' value='' title='where the file will be saved' /><br />
			<!--  ! SECOND INPUT WILL ALWAYS GIVE THE NAME TO THE JSON FILE -->
			<label for='entity_name'>name:</label><input type='text' id='entity_name' name='name' value='' title='name of the objet and of the file' /><br />
			<label for='entity_type'>type:</label><input type='text' id='entity_type' name='type' value='' title='' /><br />
			<label for='entity_energy'>energy:</label><input type='text' id='entity_energy' name='energy' value='0' title='' /><br />
			<label for='entity_strength'>strength:</label><input type='text' id='entity_strength' name='strength' value='0' title='' /><br />  			
			<label for='entity_resistance'>resistance:</label><input type='text' id='entity_resistance' name='resistance' value='0' title='' /><br />  			
			<label for='entity_description'>description:</label><textarea id='entity_description' name='description' title=''></textarea><br />  
			<label for='entity_firstStateAt'>firstStateAt:</label><input type='text' id='entity_firstStateAt' name='firstStateAt' value='' title='' /><br />  
			<label for='entity_states'>states:</label><textarea id='entity_states' name='states' title=''></textarea><br />  
			<label id='openStatesManagerLbl'></label>
			
		</div>
	  </div>
	  
	  <div id="ent-ed-tabs-3">
		<div id="entity_position_movements_preview">
			<div id='entity_stage_position_preview'></div>
		</div>
		<div id="entity_position_movements_inputs">
			<label for='entity_drawOnCanvas'>drawOnCanvas:</label><br />
			<label for='entity_x'>x:</label><input type='text' id='entity_x' name='x' value='' title='' /><br />
			<label for='entity_y'>y:</label><input type='text' id='entity_y' name='y' value='' title='' /><br />
			<label for='entity_dx'>dx:</label><input type='text' id='entity_dx' name='dx' value='' title='' /><br />
			<label for='entity_dy'>dy:</label><input type='text' id='entity_dy' name='dy' value='' title='' /><br />	
			<label for='entity_path'>path:</label><input type='text' id='entity_path' name='path' value='' title='' /><br />			
			<label for='entity_pathEnd'>pathEnd:</label><input type='text' id='entity_pathEnd' name='pathEnd' value='' title='' /><br />			
			<label for='entity_pathStep'>pathStep:</label><input type='text' id='entity_pathStep' name='pathStep' value='' title='' /><br />
			<label for='entity_pathIdx'>pathIdx:</label><input type='text' id='entity_pathIdx' name='pathIdx' value='' title='' /><br />
			<!-- 
			<label>width:<label><input type='text' id='entity_width' name='width' value='' title='' /><br />
			<label>height:<label><input type='text' id='entity_height' name='height' value='' title='' /><br />
			-->
			<!--
			<label>frame:<label><input type='text' id='entity_frame' name='frame' value='0' title='default entity frame' /><br />
			<label>frameMax:<label><input type='text'id='entity_frameMax' name='frameMax' value='1' title='' /><br />
			<label>frameTicker:<label><input type='text' id='entity_frameTicker' name='frameTicker' value='0' title='' /><br />
			<label>nextFrameAt:<label><input type='text' id='entity_nextFrameAt' name='nextFrameAt' value='12' title='' /><br />
			-->	
		</div>
	  </div>
	  
	  <div id="ent-ed-tabs-4">	 
			
			<div id='entities_sprites_list_container'>
			<!--<?php
			// #sprites_list
				$str = list_selectable_sprites();
				echo $str;			
			?>-->
			</div> 
						
	  </div>
	  
	  <div id="ent-ed-tabs-5">
		<div class='wrapper'></div>
	  </div>
	  
	  <!--<div id="ent-ed-tabs-6">
		<div class='wrapper'></div>
		<div id='hitboxPreview'></div>
		
	  </div>-->
	  
	</div><!--<div id="ent-ed-tabs">-->

	<input type='submit' id='save_entity' value='save entity' />
	<input type='reset' id='reset_entities_form' value='reset form' />
	
</form>	
</div>

<link href='./entities_editor/entities_editor.css?v=1' rel="stylesheet" type="text/css" />