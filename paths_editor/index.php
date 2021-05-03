<?php
/*
	PATHS EDITOR
*/	
	
	require "../common-functions.php";
$str = '';
?>

<div id="cv-ed-tabs">
  <ul>
    <li><a href="#cv-ed-tabs-1">paths</a></li>
    <li><a href="#cv-ed-tabs-2">path canvas</a></li>    
  </ul>
  <div id="cv-ed-tabs-1">
	<?php require('../searchForm.php');?>
    <div id='paths_list_container'>
	
		<?php
		// #sprites_list
			$str = list_selectable_paths();
			echo $str;			
		?>
	</div> 
  </div>
  <div id="cv-ed-tabs-2">
	<canvas id="path_canvas" width="400" height="700"></canvas>
	
	<div id='path_edit'></div>
	<ul id='points_list'></ul>
	
	<form id='path_canvas_settings_form'>
		<h3>canvas properties</h3>
		<label for='canvas-width'>canvas width</label><input id='canvas-width' type='text' />
		<label for='canvas-height'>canvas height</label><input id='canvas-height' type='text' />
		
		<h3>rulers</h3>
		<label for='x0'>x0:</label><input type="checkbox" id="x0" name="paths_canvas_rulers" value="x0" />
		<label for='x1'>x1:</label><input type="checkbox" id="x1" name="paths_canvas_rulers" value="x1" />
		<label for='y0'>y0:</label><input type="checkbox" id="y0" name="paths_canvas_rulers" value="y0" />
		<label for='y1'>y1:</label><input type="checkbox" id="y1" name="paths_canvas_rulers" value="y1" />		
	</form>
	
	<form id='path_ed_frm'>
		<h3>path</h3>
		<label>dest. dir.:</label>
		<input type='text' id='path_destination_dir' class='destination_dir' name='destination_dir' title='where the file will be saved' /><br />
		<!--  ! SECOND INPUT WILL ALWAYS GIVE THE NAME TO THE JSON FILE -->
		<label for='path_name'>name</label><input id='path_name' name='path_name' type='text' title='name of the objet and of the file' />
		<label for='path_pathMode'>pathMode</label><input id='path_pathMode' name='pathMode' type='text' title='path mode relative | absolute' />
		<label for='path_description'>description</label><textarea id='path_description' name='description' title=''></textarea>
		<label for='path_coords'>coords</label><textarea id='path_coords' name='path_coords' title=''></textarea>
		<label for='path_duration'>pathDuration</label><input id='path_duration' name='pathDuration' type='text' title='path duration' />		
		<input type='submit' id='save_path' value='save path' />
		<input type='reset' id='reset_paths_form' value='reset form' />
	</form>
	
	<form id='point_edit_form'>
		<h3>edit point</h3>
		<label for='point-x'>x:</label><input id='point-x' type='text' />
		<label for='point-y'>y:</label><input id='point-y' type='text' />
		<label for='point-n'>n:</label><input id='point-n' type='text' />
		<label for='point-delete'></label><a id='point-delete' href='#'>delete point</a>
	</form>
	
	<div id='path_edit_form'>
		<h3>tools</h3>
		<label for='horizontalSymmetry'>horizontal symmetry</label><input type='button' id='horizontalSymmetry' value='&harr;'/>
		<label for='verticalSymmetry'>vertical symmetry</label><input type='button' id='verticalSymmetry' value='&varr;'/>
		<label for='getRelativePathBtn'>get relative path</label><input type='button' id='getRelativePathBtn' value='&rarr; relative'/>
		<label for='getRelativePathBtn'>get absolute path</label><input type='button' id='generatePathCoords' value='&rarr; absolute'/>
		<label for='pathsCanvasScreenshotBtn'>thumbnail</label><input type='button' id='pathsCanvasScreenshotBtn' value='[o]'/>
	</div>
	
	 </div><!-- END #cv-ed-tabs-2-->	
</div><!-- END #cv-ed-tabs-->	
			
	
	<!--<link href='paths_editor/paths_editor.css'  type="text/css" rel="stylesheet" /> -->

   	<script type='text/javascript'>
			/*
				PATHS EDITOR ONLY
			*/
			// When true, moving the mouse draws on the canvas
			let isDrawing = false;
			let x = 0;
			let y = 0;
			let rulersObjet = {}
			window.decalLeft = 22,
				decalTop = 16
			
			window.path_coords = []
			
			const path_canvas = document.getElementById('path_canvas');
			const context = path_canvas.getContext('2d');

			// The x and y offset of the canvas from the edge of the layer
			const rect = path_canvas.getBoundingClientRect();
						/*decalLeft = rect.left
						decalTop = rect.top*/
			// Add the event listeners for mousedown, mousemove, and mouseup
			
			path_canvas.addEventListener('mousedown', e => {
				
			 timerStart()// start to count the time	
							
			  x = e.layerX - decalLeft;
			  y = e.layerY - decalTop;
			  
			  if( isDrawing == false ){
				//  DELETE EXISTING PATH  
				reset_path_coords()
				clear_canvas('path_canvas')// clear canvas
				delete_existing_path()
			  }
			  
			  isDrawing = true;
			});

			path_canvas.addEventListener('mousemove', e => {
				console.clear()
				console.log(e.layerX+' '+e.layerY)
				console.log(e.layerX+' '+e.layerY)
				console.log(e.offsetX+' '+e.offsetY)
				
				var xTo = e.layerX - decalLeft,
					yTo = e.layerY - decalTop
				
			  if (isDrawing === true) {
				  path_coords.push([xTo + decalLeft, yTo + decalTop*4])
				  //console.log(path_coords)
				drawLine(context, x, y, xTo, yTo);
				x = xTo;
				y = yTo;
				
				console.log('path_coords[ path_coords.length-1]: '+path_coords[ path_coords.length-1])
				
				
			  }else{
				//console.clear()			
				
				//path_coords = []
			  }
			  
			  
			});

			window.addEventListener('mouseup', e => {
			  if (isDrawing === true) {
				drawLine(context, x, y, e.layerX - decalLeft, e.layerY - decalTop);
				x = 0;
				y = 0;
				isDrawing = false;
				
						// DRAW RULERS OR NOT 
				redraw_rulers()
				//document.querySelector('#loader').style.opacity = 1
				trace_editable_path()
				//document.querySelector('#loader').style.opacity = 0

				timerStop()// stop counting the time
				
			  }
			});

			 /*
			 window.drawLine = function(context, x1, y1, x2, y2) {
			//console.log('drawLine(): '+x1+' '+y1+' '+x2+' '+y2)
			  context.beginPath();
			  context.strokeStyle = 'white';
			  context.lineWidth = 1;
			  context.moveTo(x1, y1);
			  context.lineTo(x2, y2);
			  context.stroke();
			  context.closePath();
			  return true
			}
			*/
			
			/*
			  TRACE EDITABLE PATH
			*/
			  window.trace_editable_path = function(){
				 
				
			  var n = 0,
				  x,
				  y,y
				  bgc = 'white';
				  
			  if( arguments.length == 1 ){
				  bgc = arguments[0];
			  }
			  
			  while( n <= path_coords.length-1){
				//console.log(path_coords[n][0]+' '+path_coords[n][1]) 

				x = path_coords[n][0]
				y = path_coords[n][1]
				
				// draw the path with editable divs
				var pointId = 'point-'+n
					
				$('<div/>')
				.attr('id',pointId)
				.attr('title','n:'+n+' x:'+x+' y:'+y)
				.addClass('editable_point')
				.offset({ top: y, left: x })
				.css({
				 position: 'absolute',
				   zIndex:2,
				  top: $(this).offset.top,
				  left: $(this).offset.left,
				  backgroundColor: bgc,
				  color: bgc,
				  width:1,
				  height:1
				})
				.appendTo('#path_edit')
				
				/* 
				  generate edit controls data to
				  - modify a point's coordinates
				  - delete a point      
				*/
				
				var liHTML = 'n:'
				+n
				+'<br/>x:'
				+x
				+'<br/>y:'
				+y
				
				$('<li/>')
				//.addClass(pointId)
				.addClass('point_select')
				.attr('pointId',pointId)
				.addClass(pointId)
				.html(liHTML)
				.appendTo('#points_list') 
				n++
				/**if( n == path_coords.length ){
					document.querySelector('#loader').style.opacity = 0	
					return true
				}*/
			  }
			  
			}
			
			window.reset_path_coords = function(){
				path_coords = []; //unregister points				
			}
			
			 delete_existing_path = function(){
				//console.log('delete_existing_path')								
				$('#points_list>*').remove()
				$('#path_edit>*').remove()
			}
			
			
			
			$(document).delegate('#pathsCanvasScreenshotBtn','click',function(e){    
				//e.preventDefault()
				paths_canvas_screenshot()
			})
			
			$(document)
			.delegate('.editable_point','mouseover',function(){    
			  var this_id = $(this).attr('id') 
			  
			  $('#points_list li.point_select')
			  .removeClass('point_currently_editable') 
			  
			  $('.editable_point')
			  .removeClass('highlighted-point')
			  
			  if( $('#points_list li.'+this_id) ){
				  
				$('#points_list li.'+this_id)
				.addClass('point_currently_editable')    
				$('#points_list').animate({
				  scrollLeft: $('#points_list li.'+this_id).offset().left
				}, 1000);
				
				$(this).addClass('highlighted-point')
				
			  }  
			})

			$(document)
			.delegate('.point_select','mouseover',function(){

			if( $(this).hasClass('point_currently_editable') == false ){
				
				 $('#points_list li.point_select')
				.removeClass('point_currently_editable')
				
				$('.editable_point')
				.removeClass('highlighted-point')
				
				$(this).addClass('point_currently_editable')				
			}
				
				
			 var pointId = $(this).attr('pointId')
			  
			/* var x = $('#'+pointId).css('left')
			 var y = $('#'+pointId).css('top')*/
			 //console.clear()
			 //console.log(x+' / '+y)
			 $('#'+pointId).addClass('highlighted-point')
			})
			
			$(document)
			.delegate('.point_select','click',function(){
				
				var pointId = $(this).attr('pointId')			  
				var x = $('#'+pointId).css('left')
				var y = $('#'+pointId).css('top')
				
				
				var id_arr = pointId.split('-')
				var n = id_arr[1];// get the number out of id, e.g.: point-1
				
				var dataObject = {
					n:n,
					x:x,
					y:y
				}
				point_edit_form_populate(dataObject)
			})
			
			function point_edit_form_populate(dataObject){
				$('#point-x').val(dataObject.x)
				$('#point-y').val(dataObject.y)
				$('#point-n').val(dataObject.n)
				$('#point-delete').attr('n',dataObject.n)								
			}
			
			$(document)
			.delegate('#point-delete','click',function(e){
				e.preventDefault()
				
				if( $(this).attr('n') == -1 ){					
					return false					
				}
				
				// get n (= idx) in path_coords
				var n = $(this).attr('n')
				
				// delete div #point-n
				var pointId = 'point-'+n		
				$('#'+pointId).remove()
				
				$('#point-delete').attr('n',-1)
				
				// remove control li
				$('.'+pointId).remove()
				
				//console.clear()
				//console.log('path_coords.length: '+path_coords.length)				
				var arr = path_coords.splice(n, 1); // remove the entry with index n from path_coords				
				console.log('element removed: '+arr)
				console.log('path_coords.length: '+path_coords.length)
				
			})
			
			$(document)
			.delegate('#generatePathCoords','click',function(e){
				e.preventDefault()
								
				$('#path_coords')
				.val(JSON.stringify(path_coords))
				
				$('#path_pathMode')
				.val('absolute')
			})
			
			// DRAW A RULER ON CANVAS
			/*
				PARAMS: paramsObject{
							canvasId: canvas id -- req
							axis: xo | x1 | y0 | y1 --req
							step, integer --req							
							axisDecal, mark text distance from axis int
							markLengthMax, int
							ftName, font name string
							ftColor, font color string
							ftSize font size int
						}
					
			*//*
			window.draw_axis_ruler = function( paramsObject ){
			  
			   // PUBLIC PARAMS
			  var params = paramsObject
			  
			  var canvasId = paramsObject.canvasId,// req
				  axis = paramsObject.axis ,// req
				  step = paramsObject.step ,// req           
				  ftSize = (paramsObject.ftSize)?paramsObject.ftSize:10,
				  axisDecal =  (paramsObject.axisDecal)?paramsObject.axisDecal:0,
				  markLengthMax =  (paramsObject.markLengthMax)?paramsObject.markLengthMax:ftSize,
				  ftName =  (paramsObject.ftName)?paramsObject.ftName:'Officina Sans',
				  ftColor =  (paramsObject.ftColor)?paramsObject.ftColor:'white'
			  
			  var canvas = document.getElementById(canvasId);
			  var ctx = canvas.getContext("2d")	  
			  
			  // PRIVATE PARAMS
			  var cvWidth = canvas.clientWidth,
				  cvHeight = canvas.clientHeight,  
				  length,
				  n = 0,
				  n_to,
				  x1, y1,
				  x2, y2,
				  tx, ty,
				  markLength
				
			  // GET THE LENGTH OF THE RULER
			  switch( axis ){
				case 'x0':
				case 'x1':
				   length = cvWidth
				break;
				case 'y0':
				case 'y1':
				   length = cvHeight
				break;
				case 'stage_time_line':
					length = cvWidth
				break;
			  }
				
				
							
			   n_to = length-1
			  
			   // MARKS ON THE AXIS
			  ctx.font= ftSize+"px "+ftName;
			  ctx.fillStyle = ftColor;
			  
			  while ( n <= n_to){
				
				markLength = ( n%20 == 0 )?markLengthMax:markLengthMax/2
				
				switch( axis ){
				  case 'x0':
					 x1 = n
					 y1 = 0
					 x2 = n
					 y2 = markLength
					 tx = x2-ftSize/2
					 ty = y2+axisDecal
				  break;
				  case 'x1':
					 x1 = n
					 y1 = cvHeight
					 x2 = n
					 y2 = cvHeight-markLength 
					tx = x2-ftSize/2
					ty = y2-axisDecal
				  break;
				  case 'y0':
					 x1 = 0
					 y1 = n
					 x2 = markLength
					 y2 = n 
					tx = x2+axisDecal
					ty = y2//-ftSize/2
				  break;
				  case 'y1':
					 x1 = cvWidth
					 y1 = n
					 x2 = cvWidth-markLength
					 y2 = n
					tx = x2-axisDecal
					ty = y2-ftSize/2
				  break;
				}
				
				drawLine(ctx, x1, y1, x2, y2)
				
				if (markLength == markLengthMax){
				  ctx.fillText(n, tx, ty );
				  if( axisDecal == markLengthMax){
					axisDecal = markLengthMax/2
				  }else{
					axisDecal = markLengthMax
				  }
				}
				
				
				n += step
			  }
			}
			*/
			draw_n_axis = function( rulersObject ){
			 clear_canvas('path_canvas')// clear canvas
			 delete_existing_path()
			 trace_editable_path()
			 
			 var x0 = y0 = x1 = y1 = false;
			 
			 x0 = rulersObject.x0
			 x1 = rulersObject.x1
			 y0 = rulersObject.y0
			 y1 = rulersObject.y1
			 
			 if( x0 == true ){
				 // DRAW X0
				 var params = 
				 {canvasId:'path_canvas',axis:'x0',step:5,ftColor:'lime',markLengthMax:20}
				 draw_axis_ruler( params )
			 }
			 
			 if( x1 == true ){
				 // DRAW X1
				 var params = 
				 {canvasId:'path_canvas',axis:'x1',step:5,ftColor:'lime',markLengthMax:20}
				 draw_axis_ruler( params )
			 }
			 if( y0 == true ){
				 // DRAW Y0
				 var params = 
				 {canvasId:'path_canvas',axis:'y0',step:5,ftColor:'lime',markLengthMax:20}
				 draw_axis_ruler( params )
			 }
			 if( y1 == true ){
				 // DRAW Y1
				 var params = 
				 {canvasId:'path_canvas',axis:'y1',step:5,ftColor:'lime',markLengthMax:20}
				 draw_axis_ruler( params )
			 }
			  
			}
			
						
			
			/*
				REDRAW EDITABLE PATH, and RULERS 
				ON RULERS SELECTION CHANGE
			*/
			$(document).on('change', 'input[name="paths_canvas_rulers"]', function() {
				 redraw_rulers()
			});


			function redraw_rulers(){
				rulersObjet = {}
			  $('input[type=checkbox][name=paths_canvas_rulers]')
				.each(function(i,e){
				if ($(this).is(":checked")) { 
				  
				  switch($(this).attr('id')){
					  case 'x0':
						rulersObjet.x0 = true
					  break;
					  case 'x1':
						rulersObjet.x1 = true
					  break;
					  case 'y0':
						rulersObjet.y0 = true
					  break;
					  case 'y1':
						rulersObjet.y1 = true
					  break;					  
				  }
				}   
			  })
			  draw_n_axis(rulersObjet)
				
			}
			
			/*
				 function mirror_path( paramsObject )
				 DOES: generate the mirrored version of existing path
					   changes path_coords
				 PARAMS: paramsObject{
						 canvasId: 'path_canvas',
						 symmetryType: 'horizontal' | 'vertical'   
						 }
				 example:
				var paramsObject = {
				   canvasId:'path_canvas',
				   symmetryType:'horizontal'
				}
				mirror_path( paramsObject )
			*/
			 function mirror_path( paramsObject ){
				
				
				 var canvasId = paramsObject.canvasId 
				 var canvas = document.getElementById(canvasId);
				 var ctx = canvas.getContext("2d")	
				 var cvWidth = canvas.clientWidth,
					 cvHeight = canvas.clientHeight
				 var symmetryType = paramsObject.symmetryType     
				 var x,y
				/*
					horizontal symmetry
					be x and x2
					x2 is the symmetrical point of x through the x axis
					x2 = cvWidth + (cvWidth- x1)   
				 */
				
				 //delete_existing_path()
				
				 var n = 0
				  
				 while ( n <= path_coords.length-1 ){         
					 x = path_coords[n][0]
					 y = path_coords[n][1]					 
					 if( $('#point-'+n) ){$('#point-'+n).remove()}					 
					 // HORIZONTAL SYMMETRY
					 if( symmetryType == 'horizontal' ){
					  path_coords[n][0] = cvWidth/2 + (cvWidth/2-x) + decalLeft * 2.2       
					  path_coords[n] = [path_coords[n][0],y]					 
					 } 
					 else if( symmetryType == 'vertical' ){
					 // VERTICAL SYMMETRY
					  path_coords[n][1] = cvHeight/2 + (cvHeight/2-y) + decalTop * 4.5			        
					  path_coords[n] = [x,path_coords[n][1]]					 
					 }    
					 n++					 
				  }				 				 
				 
				 trace_editable_path()				 
				 
				 // HORIZONTAL SYMMETRY
				 if( symmetryType == 'horizontal' ){
					  // DRAW VERTICAL MIDDLE LINE
					   drawLine(ctx, cvWidth/2, 0, cvWidth/2, cvHeight)
				 }else if( symmetryType == 'vertical' ){
					  // DRAW VERTICAL MIDDLE LINE						  
					  drawLine(ctx, 0, cvHeight/2, cvWidth, cvHeight/2)
				 } 
				
			}
			
			//$(document).delegate('#horizontalSymmetry', 'click', function(event) {
			$('#horizontalSymmetry').on('click', function(event) {
				event.preventDefault();
				clear_canvas('path_canvas')// clear canvas
				delete_existing_path()
				
					var paramsObject = {
						canvasId:'path_canvas',
						symmetryType:'horizontal'
					}
					mirror_path( paramsObject )
				
			})
			
			function paths_canvas_screenshot(){
				var paramsObject = {
					canvasId: 'path_canvas',
					fileName: $('#path_name').val(),
					destinationDir: destination_dir.paths,//$('#path_ed_frm .destination_dir').val()
				}
				snapshot_canvas(paramsObject)
			}
			
			//$(document).delegate('#verticalSymmetry', 'click', function(event) {
			$('#verticalSymmetry').on('click', function(event) {
				event.preventDefault();
				clear_canvas('path_canvas')// clear canvas				
				delete_existing_path()
				
				var paramsObject = {
					canvasId:'path_canvas',
					symmetryType:'vertical'
				}
				mirror_path( paramsObject )
			})
			
			
			function portraitModeSwitch(){
				var h = document.querySelector('#path_canvas').height				
				var w = document.querySelector('#path_canvas').width
				if( h > w ){
					$('#body').addClass('portrait')
				}else{
					$('#body').removeClass('portrait')
				}
			}
			
			$('#canvas-height')
			.on('change',function(e){
				document.querySelector('#path_canvas').height = $(this).val()
				portraitModeSwitch()
			})

			$('#canvas-width')
			.on('change',function(e){
				document.querySelector('#path_canvas').width = $(this).val()
				portraitModeSwitch()
			})
			
			// EXPORT A SPRITE TO A JSON FILE
			$(document).delegate('form#path_ed_frm', 'submit', function(event) {
			  event.preventDefault();
			  loader()
			  // GENERATE THE PATH THUMBNAIL
			  paths_canvas_screenshot()
			  
			  // POPULATE THE TEXTAREA WITH path_coords[]
			  if( $('#path_pathMode').val() == 'relative' ){
				  $('#getRelativePathBtn').trigger('click')						
			  }else if( $('#path_pathMode').val() == 'absolute' ){
				  $('#generatePathCoords').trigger('click')	
			  }
			  /*
			  $('#path_coords')
			  .val(JSON.stringify(path_coords))// POPULATE THE TEXTAREA WITH path_coords[]
			  */
			  
			  var json = $('form#path_ed_frm').serializeObject()  
				
			  $.ajax({
				  type : "POST",
				  async: true,
				  url : "export_json_file.php",
				  data : {json:JSON.stringify(json)}
			  })
			  .done(function(response){
				  loaderOff()
				  console.log(response)
				  // REFRESH PATHS LIST
				  load_paths_list()
				  
				  var jsonObj = JSON.parse(response)	
				 // //alert( jsonObj.type+':\r\n'+jsonObj.msg  )
				  dialogNotify(jsonObj.type+':\r\n'+jsonObj.msg)
			  })  
			})
			
			
			// LOAD PATH into paths editor FROM THE .json file
			$('#paths_list_container').delegate('.paths_list a.select_path', 'click', function(e) {
			  e.preventDefault()
			 loader()			  
			  load_path($(this).attr('href'))				
			})
			
			// LOAD PATH into paths editor FROM THE .json file
			function load_path(pathFileUrl){
				
				$.getJSON(pathFileUrl)
					.done(function(json){
						loaderOff()
					$('#path_name').val(json.path_name)
					
					$('#path_pathMode')
					.val(json.pathMode)
					
					$('#path_description')
					.val(json.description)
					
					//  DELETE EXISTING PATH  
					reset_path_coords()
					clear_canvas('path_canvas')// clear canvas
					delete_existing_path()		
					
					$('#path_coords').val(path_coords)			
					$.each( JSON.parse( json.path_coords ) ,function(i,e){
						var x = e[0], 
							y = e[1]
						path_coords[i] = [x,y]
					})
									
					
					// TRACE THE EDITABLE POINTS
					trace_editable_path()
					// FOCUS IN EDITION PANEL
					$('#cv-ed-tabs a[href="#cv-ed-tabs-2"]').trigger('click')
					
					// NOTIFY OBJET LOADED
					var msg = 'path '+json.path_name+' loaded'					
					dialogNotify(msg)					
					
				})					
			}
			
			// CONVERT ABSOLUTE PATH INTO RELATIVE PATH
			// NO PARAM
			function getRelativePathFromAbsolute(){
				if( path_coords.length > 0 ){
					$('#path_coords')
					.val(JSON.stringify(get_relative_path_from_absolute()))
				}
			}

			$(document)
			.delegate('#getRelativePathBtn','click',function(e){
				e.preventDefault()
				getRelativePathFromAbsolute()
				
				$('#path_pathMode')
				.val('relative')
				
				
				
			})
			
			
			// POPULATE INPUTS DEFAULT VALUES PERTAINING THE CANVAS DIMENSIONS 
			$('#canvas-height').val(document.querySelector('#path_canvas').height)
			$('#canvas-width').val(document.querySelector('#path_canvas').width)
			
	</script>
