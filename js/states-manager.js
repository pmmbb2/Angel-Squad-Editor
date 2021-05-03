/*
	JS FOR CREATING THE STATES DIALOG
*/

   
var paramsObject = 
{
	statesOwnerId: 'flower2.ent',// THE name ATTRIBUTE OF ENTITY/EC/BW THIS STATES COLLECTION BELONGS TO, ex: flower2.ent
	statesDefaultModel: 'flower2.ent',// states[]WILL BE POPULATED WITH THE states[] FROM statesDefaultModel
	target: $('#entity_name'),// JQUERY OBJECT TO USE AS A REFERENCE TO INJECT THE STATES MANAGER BTN 
	injectMethod: 'insertAfter', //HOW TO INJECT THE BTN, i.e.: 'append | insertBefore | insertAfter | replaceWith
	statesManagerId: 'flower2', //ID OF THE INSTANCE OF THE MANAGER DIALOG
}

    
var paramsObject = 
{
	statesOwnerId: 'flower2.ent',// THE name ATTRIBUTE OF ENTITY/EC/BW THIS STATES COLLECTION BELONGS TO, ex: flower2.ent
	statesDefaultModel: 'flower2.ent',// states[]WILL BE POPULATED WITH THE states[] FROM statesDefaultModel
	target: $('#entity_name'),// JQUERY OBJECT TO USE AS A REFERENCE TO INJECT THE STATES MANAGER BTN 
	injectMethod: 'insertAfter', //HOW TO INJECT THE BTN, i.e.: 'append | insertBefore | insertAfter | replaceWith
	statesManagerId: 'flower2', //ID OF THE INSTANCE OF THE MANAGER DIALOG
}

function generateStatesManager( paramsObject ){
	
var statesOwnerId = paramsObject.statesOwnerId,
		statesDefaultModel = paramsObject.statesDefaultModel,
		target = paramsObject.target,
		injectMethod = paramsObject.injectMethod,
    statesManagerId = paramsObject.statesManagerId
	
	// THE LIST THAT CONTAINS THE SORTABLE STATES FORMS
    var statesList =
     $('<ul/>')
	   .addClass('statesList')
     .sortable({
       containment: "parent",
       cursor: "grab",
       placeholder: "sortable-placeholder",
       grid: [ 400, 600 ],
       handle: '.stateHeader'
     })
  /*  
	// DUMMY SORTABLE STATES FORMS INSERTED TO statesList
    for ( var n = 0; n < 5 ;n++){
      var stateLi =
       $('<li/>')
       .attr('id','state-'+n)
       .css({        
        backgroundColor:colors[n*4],
        fontSize: '5em'
       })
       .text(n)
       .appendTo(statesList)
    }
  */
	
	  // THE BUTTON THAT OPENS THE DIALOG    
    var selector = $('<a/>')
    .attr('href','#')
    .attr('dialog',statesManagerId)
    .text('states')
    .addClass('openStatesManager')
    
    /*
     // THE BUTTON THAT OPENS THE [ADD STATE] BTN
    var addStateBtn = $('<a/>')
    .attr('href','#') 
    .text('state')
    .addClass('addStateToStatesList')
    .append('<spam class="ui-icon ui-icon-plusthick"></span>')
     */  
    
    // INJECTION OF BTN INTO DOM
    var target = paramsObject.target
    switch( injectMethod ){
      case 'appendTo':  
        target.append(selector)
      break;
      case 'insertBefore':  
        selector.insertBefore(target)
      break;
      case 'insertAfter':  
        selector.insertAfter(target)
      break;
      case 'replaceWith':  
        target.replaceWith(selector)
      break;  
    }
  
    // THE DIALOG THAT CONTAINS THE STATES MANAGER
    var statesDialog = 
    $('<div/>')
    .attr('id',statesManagerId)
    /*.append(addStateBtn)*/
    .append(statesList)
    .dialog({      
      modal: true,
      autoOpen: false,
      width: 800,
      height: 600,
      dialogClass: "dialog-full-mode",
      buttons: [
        {
          text: "state",
          icon: "ui-icon-plusthick",
          click: function( e ) {            
            var statesList = 
            $(this).parents('.ui-dialog').find('.statesList')
            // ADD SORTABLE STATE TO ul.statesList
            addStateToStateList(statesList)            
          }
        }],
      create:function(){
        // ADD BUTTONS TO DIALOG'S HEADER
        $('.ui-dialog-buttonset').prependTo('.ui-dialog-titlebar');
        // ADD .addStateToStatesList TO [+ states]
        $(this).dialog('widget')
        .css({fontSize:'1em'})// FIX  
        .find('.ui-icon-plusthick').parent().addClass('addStateToStatesList')
        
      },
      close:function(){
        // ON CLOSE RESET FULLSCREEN 
        $(this)
        .dialog('disable')
        .find('.ui-button-fullscreen').remove()
        $(document).dialogfullmode();// ADD A FULL SCREEN BUTTON TO THE DIALOG  
        $(this).dialog('enable')
      }
    })  
}    

function addStateToStateList(statesList){  
   var removeStateBtn =
    $('<a/>')
    .attr('href','#')
    .addClass('removeStateBtn')
    .append('<span class="ui-icon ui-icon-circle-close"></span>')
      
    $('<li/>')
    .addClass('state')
    .appendTo(statesList)
    
    $('li.state:last')
    .html('<form></form>')
  
    $('li.state:last form')
    .append('<span class="stateHeader ui-widget-header"></span>')
    
    $('li.state:last form .stateHeader')
    /*.text(Date.now())  */    
    .append(removeStateBtn)
    
    var nextInputId = 'next'+Date.now()
  
    var nextInput =   
    $('<input/>')
    .attr('id',nextInputId)
    .attr('name','next')
    .val(0),        
    nextInputLbl = 
    $('<label for="'+nextInputId+'"><span class="seconds"></span></label>')    
    
   
    
   $('li.state:last form .stateHeader')     
   .append(nextInputLbl)  
   
   $('li.state:last form .stateHeader')    
   .append(nextInput)  
  
    $('#'+nextInputId)
    .spinner({
      spin:function(event,ui){
        decisecondsToseconds(ui,nextInputId)                    
      },
      change:function(event,ui){
        console.log('ui: '+ui)
        var ui = document.querySelector('#'+nextInputId)
        decisecondsToseconds(ui,nextInputId)
      }
    })
}

// ADD STATE FORM TO StatesList
$(document)
.delegate('.addStateToStatesList',"click", function(e) {
  e.preventDefault()      
  // GET ul.statesList
  var statesList = 
  $(this).parent().find('.statesList')
  addStateToStateList(statesList)
})

$(document)
.delegate('.removeStateBtn',"click", function(e) {
  e.preventDefault()      
  $(this).parents('.state').remove()  
})

$(document)
.delegate('.openStatesManager',"click", function(e) {
  e.preventDefault()      
  var statesManagerId =  $(this).attr('dialog')     
  var id = '#'+statesManagerId
  $(document).dialogfullmode();// ADD A FULL SCREEN BUTTON TO THE DIALOG  
  $(id).dialog("open");
})

generateStatesManager( paramsObject )
