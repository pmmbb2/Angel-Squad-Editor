<?php
require "../common-functions.php";
$str = '';
?>

<canvas id='brickwall_canvas' width='225' height='500'></canvas>
<canvas id='brickwall_nrg_canvas' width='225' height='500'></canvas>

<div class='tools'>	
	<div class='appearance_tools'>
		<h6>Edit appearance</h6>
	</div>
	<div class='resistance_tools'>
		<h6>Edit resistance</h6>
	</div>
</div>


<div id="BW-ed-tabs">
  <ul>
    <li><a href="#BW-ed-tabs-1">images</a></li>
    <li><a href="#BW-ed-tabs-2">colors</a></li>    
    <li><a href="#BW-ed-tabs-3">brickwalls</a></li>    
  </ul>
  <div id="BW-ed-tabs-1">
  <a href="#" class="refreshImagesListBtn">refresh</a>
	<div id='BW_images_list_container'>
	<?php	
		$str = list_selectable_images();
		echo $str;
	?>
	</div>
  </div>
  <div id="BW-ed-tabs-2">  
		<div id='colorWatchesContainer'></div>		
  </div>
  <div id="BW-ed-tabs-3">
	<div id='BW_brickwalls_list_container'>
	<?php	
		$str = list_selectable_brickwalls();
		echo $str;
	?>
	</div>
  </div>
  
</div>


<form id='brickwall_ed_frm'>
	<h3>brickwall</h3>
	<label>dest. dir.:</label><input type='text' id='destination_dir' class='destination_dir' name='destination_dir' value='' title='where the file will be saved' />
	<!-- SECOND INPUT WILL ALWAYS GIVE THE NAME TO THE JSON FILE -->
	<label for='brickwall_name'>name</label><input id='brickwall_name' name='brickwall_name' type='text' title='name of the objet and of the file' />
	
	<h4>bricks</h4>
	<label for='wallBrickMap'>wallBrickMap</label><textarea id='wallBrickMap' name='wallBrickMap' title=''></textarea>
	<label for='wallBrickMapEnergy'>wallBrickMapEnergy</label><textarea id='wallBrickMapEnergy' name='wallBrickMapEnergy' title=''></textarea>
	<label for='wallBrick_description'>description</label><textarea id='wallBrick_description' name='description' title=''></textarea>
	
	<label for='brickWidth'>brickWidth</label><input id='brickWidth' name='brickWidth' type='text' title='' />
	<label for='brickHeight'>brickHeight</label><input id='brickHeight' name='brickHeight' type='text' title='' />
		
	<label for='brickRowCount'>brickRowCount</label><input id='brickRowCount' name='brickRowCount' type='text' title='' />
	<label for='brickColumnCount'>brickColumnCount</label><input id='brickColumnCount' name='brickColumnCount' type='text' title='' />
		
	<label for='brickPadding'>brickPadding</label><input id='brickPadding' name='brickPadding' type='text' title='' />
	<label for='bricksFillStyle'>bricksFillStyle</label><input id='bricksFillStyle' name='bricksFillStyle' type='text' title='' />
	<hr/>	
	<label>brickWallSkin</label>
	<label for='img' class='customStyle'>image</label><input id='img' class='customStyle'name='brickWallSkin' type='radio' value="img" checked='checked' title='' />
	<label for='color' class='customStyle'>color</label><input id='color' class='customStyle'name='brickWallSkin' type='radio' value="color" title='' />
	<hr/>		
	<h4>image & tiles</h4>
	<label class=''>bricks properties = tiles properties</label>
	<label for='same' class='customStyle'>same</label>
	<input id='same' class='customStyle' name='bricksEqualTiles' type='radio' value="same" title='bricks = tiles' checked='checked' />
	<label for='different' class='customStyle'>different</label>
	<input id='different' class='customStyle' name='bricksEqualTiles' type='radio' value="different" title='bricks != tiles' />
	<br/>
	<label for='brickWallImage'>brickWallImage</label><input id='brickWallImage' name='brickWallImage' type='text' title='' />
	<label for='setWallBrickMapWholeSpriteSheetBtn'></label><input type='button' id='setWallBrickMapWholeSpriteSheetBtn' value='SET' />
	
	<label for='tileWidth'>tileWidth</label><input id='tileWidth' name='tileWidth' type='text' title='' />
	<label for='tileHeight'>tileHeight</label><input id='tileHeight' name='tileHeight' type='text' title='' />
	
	<label for='mapRows'>mapRows</label><input id='mapRows' name='mapRows' type='text' title='' />
	<label for='mapColumns'>mapColumns</label><input id='mapColumns' name='mapColumns' type='text' title='' />
	
	<label for='sourceWidth'>sourceWidth</label><input id='sourceWidth' name='sourceWidth' type='text' title='' />
	<label for='sourceHeight'>sourceHeight</label><input id='sourceHeight' name='sourceHeight' type='text' title='' />
	<hr/>		
	<label for='save_brickwall'></label><input type='submit' id='save_brickwall' value='save brickwall' />
	<label for='reset_brickwall_ed_frm'></label><input type='button' id='reset_brickwall_ed_frm' value='reset form' />
</form>

<div id='result'>Click on the image at the bottom to select a tile, then click on the grid to draw.</div>
	
<link href='./brickwalls_editor/brickwalls_editor.css' rel="stylesheet" type="text/css" />



<script type='text/javascript'>
	
	// CREATE THE BRUSH
	$('<button/>')
	.attr('id','brush')	
	.html('brush')
	.appendTo('#brickwalls_editor .tools .appearance_tools')
	.on('mouseup',function(e){
		e.preventDefault()
		tool = 'brush'
		$(this).addClass('ui-state-highlight')
		$('#eraser,#energy_selector,#indestructibleBtn').removeClass('ui-state-highlight')
		//////alert(tool)		
	})
	
	// CREATE THE ERASER
	$('<button/>')
	.attr('id','eraser')
	.html('eraser')
	.appendTo('#brickwalls_editor .tools .appearance_tools')
	.on('mouseup',function(e){
		e.preventDefault()
		tool = 'eraser' // switch to eraser
		$(this).addClass('ui-state-highlight')
		$('#brush,#energy_selector,#indestructibleBtn').removeClass('ui-state-highlight')
			
	})
	
	
	// CREATE THE EDITOR MODE TOGGLE: BRICK APPEARANCE VS BRIK RESISTANCE (aka 'energy')
	$('<button/>')
	.attr('id','brickOrEnergyToggle')
	.addClass('brick')
	.html('edit resistance')
	.appendTo('#brickwalls_editor .tools')
	.on('mouseup',function(e){
		e.preventDefault()
		
		if( $(this).hasClass('brick') ){			
			$('#brickwall_nrg_canvas').css({zIndex:2})//show()
			$('#brickwall_canvas').css({zIndex:1})//hide()			
			$('#brush,#eraser,#indestructibleBtn').removeClass('ui-state-highlight')
			$(this)
			.html('edit appearance')
			.removeClass('brick')
			.addClass('energy')
			tool = 'energy_selector'
			$('.appearance_tools').hide()
			$('.resistance_tools').show()						
		}
		else if( $(this).hasClass('energy') ){	
			$('#brickwall_nrg_canvas').css({zIndex:1})//hide()
			$('#brickwall_canvas').css({zIndex:2})//show()
			$(this)
			.html('edit resistance')
			.removeClass('energy')
			.addClass('brick')
			$('#energy_selector,#eraser,#indestructibleBtn').removeClass('ui-state-highlight')			
			$('#brush').trigger('mouseup')// switch to brush
			$('.appearance_tools').show()
			$('.resistance_tools').hide()
		}
		
	})
	
	/*
		CREATE THE #energy SPINNER TO SET THE AMOUNT OF ENERGY THAT CAN BE ADDED
	*/
	$('<input/>')
	.attr('id','energy')
	.attr('type','text')
	.val(1)
	.attr('placeholder','resistance')	
	.appendTo('#brickwalls_editor .tools .resistance_tools')
	.spinner({
      spin: function( event, ui ) {
        if ( ui.value < 0 ) {
          $( this ).spinner( "value", 0 );
          return false;
        }
      }
    })
	
	// CREATE THE IINVICIBLE BRICK BUTTON
	/*
		WHEN DOWN AND HIGHLIGHTED, IT WILL SET BRICKS INDESTRUCTIBLE INSTEAD OF SETTING THEM AND ENERGY LEVEL
	*/
	$('<button/>')
	.attr('id','indestructibleBtn')
	.html('Indestructible')
	.appendTo('#brickwalls_editor .tools .resistance_tools')
	.on('mouseup',function(e){
		e.preventDefault()		
		if( $(this).hasClass('ui-state-highlight') ){
			tool = 'energy_selector' // switch to indestructible
			$(this).addClass('ui-state-highlight')		
			$('#indestructibleBtn').removeClass('ui-state-highlight')
		}else{
			tool = 'indestructible' // switch to indestructible
			$(this).addClass('ui-state-highlight')		
			$('#energy').removeClass('ui-state-highlight')			
		}	
	})
	
	// BUTTON TO COPY THE WHOLE SPRITESHEET TO CHVAS AND POPULATE TEXTAREA
	$('#setWallBrickMapWholeSpriteSheetBtn').on('mouseup',function(e){
		e.preventDefault()
		setWallBrickMapWholeSpriteSheet()			
	})

/*
	THE APPEARANCE EDITION MODE IS SELECTED BY DEFAULT
*/
var tool = 'brush';
$('.appearance_tools').show()
$('.resistance_tools').hide()


// SPRITESHEET IMAGE	
var image = new Image();
image.src = './game_assets/images/starbucks-cafe-front.png';


const decalX = 0,// ADJUST mouse capture with layerX
	  decalY = 0 // ADJUST mouse capture with layerY

// TILES WIDTH & HEIGHT
var tileWidth = 22.5,
	  tileHeight = 20;

// BRICKWALL ROWS COUNT & BRICKWALL COLUMNS COUNT	  
var mapRows = 10,
	  mapColumns = 10;
	  
// SPRITESHEET IMAGE DIMENSIONS	  
var sourceWidth = 225,
	sourceHeight = 220;

var tiles = new Array(mapColumns * mapRows);
var mapHeight = mapRows * tileHeight;
var mapWidth = mapColumns * tileWidth;
var sourceX, sourceY, sourceTile;

var canvas = document.getElementById('brickwall_canvas'),
	canvasEnergy = document.getElementById('brickwall_nrg_canvas')

var context = canvas.getContext('2d'),
	contextNRG = canvasEnergy.getContext('2d');
	
var nrgAmount = new Array(mapColumns * mapRows),
	sourceXNRG,
	sourceYNRG,
	sourceNRG,
	targetNRG	

var mouseDown;
wallBrickMap = []

// DISABLE RIGHT CLICK / CONTEXT MENU TRIGGER
document.addEventListener('contextmenu', event => event.preventDefault());

/*
	BRICK CANVAS EVENT LISTENERS
*/
canvas.addEventListener('mousedown', doMouseDown);
canvas.addEventListener('mousemove', doMouseMove);
canvas.addEventListener('click', doMouseClick);

//image.addEventListener('load', initBWEditor);

canvas.addEventListener('mouseup', doMouseUp);

/*
	ENERGY CANVAS EVENT LISTENERS
*/
canvasEnergy.addEventListener('mousedown', doMouseDownNRG);
canvasEnergy.addEventListener('mousemove', doMouseMoveNRG);
canvasEnergy.addEventListener('click', drawTileNRG);
canvasEnergy.addEventListener('mouseup', doMouseUpNRG);


// ON IMAGE LOAD ...
 window.initBWEditor = function(){
	/*
	tiles = []
	nrgAmount = []
	*/
	tiles = new Array(mapColumns * mapRows)
    nrgAmount = new Array(mapColumns * mapRows)
		
	mapHeight = mapRows * tileHeight;
	mapWidth = mapColumns * tileWidth;
	
	canvas.width = mapWidth
	canvas.height = mapHeight*2		
	canvasEnergy.width = mapWidth
	canvasEnergy.height = mapHeight*2
	
	context.clearRect(0, 0, canvas.width, canvas.height);
		
	redrawSource()
	initWallBrickMap()	
	drawTheGrid()
	return true
}

 window.drawTheGrid = function(){	
	 context.strokeStyle = '#000';	
	// draw the grid 
	for (let i = 0; i <= mapColumns; i++) {
	  context.moveTo(i * tileWidth, 0);
	  context.lineTo(i * tileWidth, mapHeight);
	}
	context.stroke();
	for (let i = 0; i <= mapRows; i++) {
	  context.moveTo(0, i * tileHeight);
	  context.lineTo(mapWidth, i * tileHeight);
	}
	context.stroke();
}



function redrawSource() {
  context.drawImage(image, 0, 0, sourceWidth, sourceHeight, 0, mapHeight, sourceWidth, sourceHeight);
  //console.log(image, 0, 0, sourceWidth, sourceHeight, 0, mapHeight, sourceWidth, sourceHeight)
}



 window.initWallBrickMap = function() {  
  // update the string
  let string = '['
  wallBrickMap = []  
  var max = (mapColumns) * (mapRows)
  for (let i = 0; i < max; i++) {    
		string = string + -1;
		wallBrickMap[wallBrickMap.length] = -1
		if( i< max-1){
			string = string + ',';						
		}		
  }    
  string = string + ']';  
  document.getElementById('result').innerHTML = string;
}
 
function doMouseUp(e) {
	console.clear()
  mouseDown = false;
  
	if( tool == 'brush' || tool == 'eraser' ){	  
	  // update the string
	  let string = '['
	  wallBrickMap = []  
	  var max = mapColumns * mapRows
	  for (let i = 0; i < max; i++) {
		  //console.log(tiles[i])	   
	   if (tiles[i] !== 'undefined' && tiles[i] !== undefined){
		   if( $("input[name='brickWallSkin']:checked").val() == 'img' ){	
				string = string + tiles[i];
				wallBrickMap[i] = Math.floor(tiles[i])	
		   }
		   else if( $("input[name='brickWallSkin']:checked").val() == 'color' ){		
				string = string + tiles[i];
				wallBrickMap[i] = tiles[i]		
		   }		   
		}
		else{
			string += -1
			wallBrickMap[i] = -1
		}		
		if( i< max-1){
			string = string + ',';						
		}		
	  }    
	  string = string + ']';  
	  document.getElementById('result').innerHTML = string;
	  document.getElementById('wallBrickMap').value = "["+wallBrickMap+"]";
  }
  drawTheGrid()
}
 
function doMouseDown(e) {
  mouseDown = true;
  let x = e.layerX+decalX;
  let y = e.layerY+decalY;
  let gridX = Math.floor(x / tileWidth) * tileWidth;
  let gridY = Math.floor(y / tileHeight) * tileHeight;
 
  if( $("input[name='brickWallSkin']:checked").val() == 'color' ){	
	sourceTile = $('.color_select.selected').attr('idx')	
  }
  else if (y > mapHeight && y < (mapHeight + sourceHeight) && x < sourceWidth) { // source
    let tileX = Math.floor(x / tileWidth);
    let tileY = Math.floor((y - mapHeight) / tileHeight);
    sourceTile = tileY * (sourceWidth / tileWidth) + tileX;
    sourceX = gridX;
    sourceY = gridY - mapHeight;
    redrawSource();
	
    drawBox();
  }
  
}
 
function doMouseMove(e) {
	//console.log('doMouseMove: '+this)
  let x = e.layerX+decalX;
  let y = e.layerY+decalY;
  let gridX, gridY;
  gridX = Math.floor(x / tileWidth) * tileWidth;
  gridY = Math.floor(y / tileHeight) * tileHeight; 
 
  if (y > mapHeight && y < (mapHeight + sourceHeight) && x < sourceWidth) { // source
    context.clearRect(0, mapHeight, sourceWidth, sourceHeight);
    redrawSource();
    context.beginPath();
    context.strokeStyle = 'yellow';
    context.rect(gridX, gridY, tileWidth, tileHeight);
    context.stroke();
    drawBox(); 
  }
  
  // PAINT TILES ON THE CANVAS
  if (mouseDown == true) {drawTile(e);}
}
 
function drawBox() {
  context.beginPath();
  context.strokeStyle = 'red';
  context.rect(sourceX, sourceY + mapHeight, tileWidth, tileHeight);
  context.stroke();
}
 
function doMouseClick(e) {
	drawTile(e);
}
 
// PAINT ONE TILE ON CANVAS 
function drawTile(e) {
	//console.log('drawTile: '+this)
  let x = e.layerX+decalX;
  let y = e.layerY+decalY;
  let gridX, gridY;
  gridX = Math.floor(x / tileWidth) * tileWidth;
  gridY = Math.floor(y / tileHeight) * tileHeight;  
  if( $("input[name='brickWallSkin']:checked").val() == 'color' ){
	context.clearRect(gridX, gridY, tileWidth, tileHeight);
	let tileX = Math.floor(x / tileWidth);
    let tileY = Math.floor(y / tileHeight);
    let targetTile = tileY * mapColumns + tileX;	
	// IF ERASER MODE, THEN ERASE AND LEAVE THE FUNCTION
	if( tool == 'eraser' ){
		tiles[targetTile] = -1
		return true		
	}	
	// ELSE PAINT THE TILE WITH THE SELECTED COLOR
	var color_idx = $('.color_select.selected').attr('idx')
	context.fillStyle = colors[color_idx];    
	context.fillRect(gridX, gridY, tileWidth, tileHeight);
	tiles[targetTile] = color_idx;
	console.log('tiles[targetTile]: '+tiles[targetTile])
  }
  else if (y < mapHeight && x < mapWidth) { // target
    context.clearRect(gridX, gridY, tileWidth, tileHeight);
    context.drawImage(image, sourceX, sourceY, tileWidth, tileHeight, gridX, gridY, tileWidth, tileHeight);
    let tileX = Math.floor(x / tileWidth);
    let tileY = Math.floor(y / tileHeight);
    let targetTile = tileY * mapColumns + tileX;
    tiles[targetTile] = sourceTile;
	
    if (e.button == 2 || tool == 'eraser') {
      context.clearRect(gridX, gridY, tileWidth, tileHeight);
      context.beginPath();
      context.strokeStyle = 'black';
      context.rect(gridX, gridY, tileWidth, tileHeight);
      context.stroke();
      tiles[targetTile] = -1
    }
  }
  
}

 setWallBrickMapWholeSpriteSheet = function(){
	/*
	  set wallBrickMap to take the whole spritesheet
	*/
	var n = 0,x = 0, y = 0
	while ( n <= wallBrickMap.length-1){
		wallBrickMap[n] = n      
		var e = {
			layerX: x,
			layerY: y,
			button: 0 
		}
		x += Number(tileWidth)
		if( x >= Number(sourceWidth) ){
		  x = 0
			y += Number(tileHeight)
			if( y >= Number(sourceHeight)){
			  //return
			}   
		}  
	  n++
	}    
	//console.log(wallBrickMap)
	// UPDATE THE TEXTAREA
	document.querySelector('#wallBrickMap').value = wallBrickMap
    
	// UPDATE THE CANVAS
	context.clearRect(0,0,sourceWidth, sourceHeight)
	context.drawImage(image, 0, 0, sourceWidth, sourceHeight, 0, 0, sourceWidth, sourceHeight);
	drawTheGrid()	
}

/*
	BRICKS ENERGY EDITION
*/

function doMouseUpNRG(e) {
	console.clear()
  mouseDown = false;
  
	if( tool == 'energy_selector' || tool == 'indestructible'){
	  // update the string
	  let string = '['
	  wallBrickMapEnergy = []  
	  var max = mapColumns * mapRows
	  for (let i = 0; i < max; i++) {
	
		if (nrgAmount[i] !== 'undefined' && nrgAmount[i] !== undefined){
			var Value = nrgAmount[i]			
			// IF nrgAmount[i] == -1, it is indestructible
			if( nrgAmount[i] == -1 ){				
				Value = nrgAmount[i]
			}					
			string = string + Value;
			wallBrickMapEnergy[i] =  Value
		}
		else{
			// BRICK UNDEFINED, ENERGY = -1			
			string += 0
			wallBrickMapEnergy[i] = 0
		}
		
		if( i< max-1){
			string = string + ',';						
		}		
	  }    
	  string = string + ']';  
	  document.getElementById('result').innerHTML = string;
	  document.getElementById('wallBrickMapEnergy').value = "["+wallBrickMapEnergy+"]";
  }
  
}
 
function doMouseDownNRG(e) {
  mouseDown = true;
  let x = e.layerX+decalX;
  let y = e.layerY+decalY;
  let gridX = Math.floor(x / tileWidth) * tileWidth;
  let gridY = Math.floor(y / tileHeight) * tileHeight;
 
 var nrgValue = document.querySelector('#energy').value
 
  if (y > mapHeight && y < (mapHeight + sourceHeight) && x < sourceWidth) { // source
    let tileX = Math.floor(x / tileWidth);
    let tileY = Math.floor((y - mapHeight) / tileHeight);
    sourceNRG = tileY * (sourceWidth / tileWidth) + tileX;
	
	let Value = (tool == 'indestructible')?-1:nrgValue	  
	
	nrgAmount[sourceNRG] = Value
	
    sourceXNRG = gridX;
    sourceYNRG = gridY - mapHeight;   
  }
}



function drawTileNRG(e) {	
  let x = e.layerX/*-decalX*/;
  let y = e.layerY/*-decalY*/;
  let gridX, gridY;
  gridX = Math.floor(x / tileWidth) * tileWidth;
  gridY = Math.floor(y / tileHeight) * tileHeight;  
  var nrgValue = document.querySelector('#energy').value    
  if (y < mapHeight && x < mapWidth) { // target     
    contextNRG.clearRect(gridX, gridY, tileWidth, tileHeight);
	
	//let Value = (tool == 'indestructible')?"I":nrgValue	
	let Value = (tool == 'indestructible')?-1:nrgValue	
	
    let tileX = Math.floor(x / tileWidth);
    let tileY = Math.floor(y / tileHeight);
    let targetNRG = tileY * mapColumns + tileX;
	
	contextNRG.font = 0.5*tileWidth+"px Comic Sans MS";
	contextNRG.fillStyle = (tool == 'indestructible')?"red":"lime";
	contextNRG.textAlign = "right";
	contextNRG.fillText(Value, gridX+tileWidth*0.8, gridY+tileHeight*0.8);			
    nrgAmount[targetNRG] = Value
  }
}

function doMouseMoveNRG(e) {
  let x = e.layerX+decalX;
  let y = e.layerY+decalY;
  let gridX, gridY;
  gridX = Math.floor(x / tileWidth) * tileWidth;
  gridY = Math.floor(y / tileHeight) * tileHeight; 
  
  if (mouseDown == true) {drawTileNRG(e);}
}



$(document).delegate('form#brickwall_ed_frm', 'submit', function(event) {
  event.preventDefault();
  
  // GENERATE THE BRICKWALL THUMBNAIL
  brickwalls_canvas_screenshot()
  /*
  $('#wallBrickMap')
  .val('['+wallBrickMap+']')//JSON.stringify(wallBrickMap)
  
  $('#wallBrickMapEnergy')
  .val('['+wallBrickMapEnergy+']')//JSON.stringify(wallBrickMapEnergy)
  */
  var json = $('form#brickwall_ed_frm').serializeObject()  
	
  $.ajax({
	  type : "POST",
	  async: true,
	  url : "export_json_file.php",
	  data : {json:JSON.stringify(json)}
  })
  .done(function(response){
	  //console.log(response)
	  // REFRESH BRICKWALLS LIST
	  load_brickwalls_list()
	  
	  var jsonObj = JSON.parse(response)	
	  //////alert( jsonObj.type+':\r\n'+jsonObj.msg  )
	  dialogNotify(jsonObj.type+':\r\n'+jsonObj.msg)
			 
	  
  })  
})


$(document)
  .delegate('#BW_images_list_container .select_image','mouseup',function(e){
  e.preventDefault();	

  // SPRITESHEET IMAGE SELECTION
  let imgSrc = $(this).children('img:eq(0)').attr('src')  
  $('#brickWallImage').val(imgSrc)
  
  // SPRITESHEET IMAGE LOAD	
  //let image = new Image();
   //console.clear()
   //console.log(image)
	let tmpImg = new Image() ;
    		
	// UPDATE SPRITESHEET IMAGE INPUTS
	/*console.log(JSON.stringify(tmpImg))
	console.log(tmpImg.naturalWidth+' ' +tmpImg.naturalHeight)*/
	
    tmpImg.onload = function() {
		sourceWidth = tmpImg.naturalWidth
		sourceHeight = tmpImg.naturalHeight
		$('#sourceWidth').val(tmpImg.naturalWidth);
		$('#sourceHeight').val(tmpImg.naturalHeight);
		
		brickwall_canvas.width = tmpImg.naturalWidth
		brickwall_canvas.height = tmpImg.naturalHeight*2		
		brickwall_nrg_canvas.width = tmpImg.naturalWidth
		brickwall_nrg_canvas.height = tmpImg.naturalHeight*2
		
		image = tmpImg
		initBWEditor()	
    }// tmpImg.onload
	tmpImg.src = imgSrc+'?t='+Date.now() ;
	
	
})

/*
	ON EDITOR LOAD
*/
/*
// can be used to do actions on x_edito load event...
var $brickwalls_editor = $('#brickwalls_editor');
$brickwalls_editor.bind("DOMSubtreeModified", function(){
	$('#brickwalls_editor .tools').draggable()	
	$('#BW-ed-tabs')
	.tabs()
	.draggable()	
	////alert('yo')
	$('#tileWidth').val(22.5)
	$('#tileHeight').val(20)
	$('#mapRows').val(10)
	$('#mapColumns').val(10)

});
*/

/*
	brickHeight = sourceHeight/brickRowCount	
	brickRowCount = sourceHeight/brickHeight
	
	brickWidth = sourceWidth/brickColumnCount
	brickColumnCount = sourceWidth/brickWidth
	
	tileHeight = sourceHeight/mapRows
	mapRows = sourceHeight/tileHeight
	
	tileWidth = sourceWidth/mapColumns
	mapColumns = sourceWidth/tileWidth
*/

$(document).delegate('#sourceWidth','change',function(){
	if( $(this).val() == '' ){return}
	sourceWidth = $(this).val()
	mapWidth = sourceWidth
	initBWEditor()
})

$(document).delegate('#sourceHeight','change',function(){
	if( $(this).val() == '' ){return}
	sourceHeight = $(this).val()
	mapHeight = sourceHeight
	initBWEditor()
})

$(document).delegate('#brickRowCount','change',function(){
	if( $(this).val() == '' ){return}
	brickRowCount = $(this).val()
	brickHeight = Math.floor(sourceHeight/brickRowCount)
	$('#brickHeight').val(brickHeight)
	if( $("input[name='bricksEqualTiles']:checked").val() == 'same' ){
		tileHeight = brickHeight
		$('#tileHeight').val(tileHeight)		
		mapRows = brickRowCount		
		$('#mapRows').val(mapRows)
	}
	initBWEditor()
})

$(document).delegate('#brickColumnCount','change',function(){
	if( $(this).val() == '' ){return}
	brickColumnCount = $(this).val()
	brickWidth = Math.floor(sourceWidth/brickColumnCount)
	$('#brickWidth').val(brickWidth)		
	if( $("input[name='bricksEqualTiles']:checked").val() == 'same' ){
		tileWidth = brickWidth
		$('#tileWidth').val(tileWidth)		
		mapColumns = brickColumnCount		
		$('#mapColumns').val(mapColumns)
	}
	initBWEditor()
})

$(document).delegate('#brickWidth','change',function(){
	if( $(this).val() == '' ){return}
	brickWidth = $(this).val()
	brickColumnCount = Math.floor(sourceWidth/brickWidth)
	$('#brickColumnCount').val(brickColumnCount)	
	if( $("input[name='bricksEqualTiles']:checked").val() == 'same' ){
		tileWidth = brickWidth
		$('#tileWidth').val(tileWidth)		
		mapColumns = brickColumnCount		
		$('#mapColumns').val(mapColumns)
	}
	initBWEditor()
})

$(document).delegate('#brickHeight','change',function(){
	if( $(this).val() == '' ){return}
	brickHeight = $(this).val()	
	brickRowCount = Math.floor(sourceHeight/brickHeight)
	$('#brickRowCount').val(brickRowCount)
	if( $("input[name='bricksEqualTiles']:checked").val() == 'same' ){
		tileHeight = brickHeight
		$('#tileHeight').val(tileHeight)		
		mapRows = brickRowCount		
		$('#mapRows').val(mapRows)
	}
	initBWEditor()
})


$(document).delegate('#tileWidth','change',function(){	
	if( $(this).val() == '' ){return}
	tileWidth = $(this).val()	
	mapColumns = Math.floor(sourceWidth/tileWidth)
	$('#mapColumns').val(mapColumns)
	if( $("input[name='bricksEqualTiles']:checked").val() == 'same' ){		
		brickWidth = tileWidth
		$('#brickWidth').val(brickWidth)		
		brickColumnCount = mapColumns		
		$('#brickColumnCount').val(brickColumnCount)		
	}
	initBWEditor()
})

$(document).delegate('#tileHeight','change',function(){
	if( $(this).val() == '' ){return}
	tileHeight = $(this).val()	
	mapRows = Math.floor(sourceHeight/tileHeight)
	$('#mapRows').val(mapRows)
	if( $("input[name='bricksEqualTiles']:checked").val() == 'same' ){
		brickHeight = tileHeight
		$('#brickHeight').val(brickHeight)		
		brickRowCount = mapRows		
		$('#brickRowCount').val(brickRowCount)	
	}
	initBWEditor()
})

$(document).delegate('#mapRows','change',function(){
	if( $(this).val() == '' ){return}
	mapRows = $(this).val()
	tileHeight = Math.floor(sourceHeight/mapRows)
$('#tileHeight').val(tileHeight)	
	if( $("input[name='bricksEqualTiles']:checked").val() == 'same' ){
		brickHeight = tileHeight
		$('#brickHeight').val(brickHeight)		
		brickRowCount = mapRows		
		$('#brickRowCount').val(brickRowCount)	
	}
	initBWEditor()
})

$(document).delegate('#mapColumns','change',function(){
	if( $(this).val() == '' ){return}
	mapColumns = $(this).val()	
	tileWidth = Math.floor(sourceWidth/mapColumns)
	$('#tileWidth').val(tileWidth)
	if( $("input[name='bricksEqualTiles']:checked").val() == 'same' ){
		brickWidth = tileWidth
		$('#brickWidth').val(brickWidth)		
		brickColumnCount = mapColumns		
		$('#brickColumnCount').val(brickColumnCount)		
	}
	initBWEditor()
})
</script>