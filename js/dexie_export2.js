window.totalPromises = 0
window.solvedPromises = 0
window.promises = []

const assetsDir = '/game_assets/';



//
// When document is ready, bind export/import functions to HTML elements
//
document.addEventListener('DOMContentLoaded', () => {

 window.loadWithPromise = function(url, callback) {
	 
    promises[promises.length] = new Promise(function(resolve, reject) {  
		window.totalPromises++    
		const myHeaders = new Headers({
		  "Content-Type": "application/json",
		  Accept: "application/json"
		}); 
		
		console.log('url: '+url)
		
		//alert('url: '+url)
		
		
		fetch('./'+url,
		{headers: myHeaders}
		)
		.then( (response) => {
		return response.json();
		})
		.then( (data)=>{		
			callback(JSON.stringify(data));
			resolve()
			
			window.solvedPromises++
			var percentage = window.solvedPromises / window.totalPromises * 100

			document.querySelector('#loadingStatus').innerHTML =  'Loading '+percentage.toFixed(0)+ '%'					
			document.querySelector('#loadingStatus').innerHTML +=  ' assets added: '+window.solvedPromises +'/'+ window.totalPromises				
			if ( window.solvedPromises == window.totalPromises){
				//alert('attempt exporting')
				
				window.solvedPromises = 0
				window.totalPromises = 0
				
				createExpFile()			
			}

		
		})// then data
	 
	});// promises[promises.length] = new Promise(function(resolve, reject) 
 }
 /*
	populate ASDB.assets
	'stages',
	'sprites',
	'brickwalls',
	'entities',
	'paths',
	'sounds'
	
	FROM campaign.cmp.json FILE
*/

window.populateAssetsTable = function(){
/*
	populateAssetsTable()
	createExpFile()
*/

 /*
 var campaign = 'shortCmp.cmp.json'
var campaign = 'myFirstCampaign.cmp.json'
 */

var campaign = assetsDir + 'campaigns/'+ document.querySelector('#campaign_name').value + '.json'		

 load(campaign, function(response){			
           
    var jsonObjParsed = JSON.parse(response)            
      
    // TRUNCATE assets
    db.assets.clear()

    var collections = ['stages','sprites','brickwalls','entities','paths','sounds']
	
	document.querySelector('#importLog').innerHtml = 'import logs'
	
	var logStr = ''// log will display all imnports
	var t = 0;
	collections.some(function(selectedCollection){
	   
	var collection = jsonObjParsed[selectedCollection]
	var ext = '.json'
	//alert(selectedCollection)
	if( selectedCollection == 'sounds' ){ext = '.mp3'; /*alert(collection.length)*/}
	
	let assetsLoaded = []
	
	if( collection.length > 0 ){
	 // populate sprites
		collection.some(function(assetName,idx){

			//var assetName  = jsonObjParsed.sprites[0]  
 
		    
			if( assetsLoaded.indexOf(assetName) == -1 ){
				
			
			
			// ASSET NOT IN ARRAY assetsLoaded, insert it 
			assetsLoaded.push(assetName)
			/*
				IF ASSET IS NOT A .json FILE
					i.e.: .mp3...
			*/
			if(ext != '.json'){
				
				/*
						DEXIE: start transactions
					*/   
					spawn(function* (){	

						logStr += '['+selectedCollection+']: '+assetName+' | '+assetName+ext+'\r\n<br/>'
						t += 1						
						
						var id = yield db.assets.add(
							{
								name: assetName,// e.g.: 'bgm_4-loop.mp3'
								object: {name:assetName}, // e.g.:{name:'xxx',...}
								description: assetName+ext,// e.g: 'Save the granny from the monsters!'
								collection: selectedCollection // e.g: 'sounds'
							})
						.then(function(id){						
							//console.log(assetJson+' '+id)
							
							document.querySelector('#importLog').innerHTML = logStr
							console.log(t)
							
						})
						.catch(function(err){
							alert(err)
							
						})// then
					
						
					})// spawn
				/*
					DEXIE: end t
				*/ 
								
			}else{
				
				loadWithPromise(assetsDir + selectedCollection + '/'+assetName+ext, function(jsonAsset){
					var assetJson = JSON.parse(jsonAsset) 
					
					/*
						DEXIE: start transactions
					*/   
					spawn(function* (){	

						logStr += '['+selectedCollection+']: '+assetName+' | '+assetJson.description+'\r\n<br/>'
						t += 1
						//console.log('['+selectedCollection+']: '+assetName+' '+assetJson.description+'\r\n')
						
						var id = yield db.assets.add(
							{
								name: assetName,// e.g.: 'stage5.stg'
								object: assetJson, // e.g.:{name:'xxx',...}
								description: assetJson.description,// e.g: 'Save the granny from the monsters!'
								collection: selectedCollection // e.g: 'stages'
							})
						.then(function(id){						
							//console.log(assetJson+' '+id)
							
							
							document.querySelector('#importLog').innerHTML = logStr
							console.log(t)
							
							
						})// then
						
						
						
					})// spawn
				/*
					DEXIE: end transactions
				*/ 
				}) // loadWithPromise(assetName+ext, function(jsonAsset){
				
			}// if(ext != '.json'){	} else
		
			}// if asset not in array already
		
		})// collection.some(function(assetName,idx){ 


		
	} //if( collection.length > 0 ){

	}) // collections.some(function(selectedCollection)

 })// load
 
}// populateAssetsTable()
 

  var Dexie = window.Dexie,
	  async = Dexie.async,
	  spawn = Dexie.spawn,
	  db = new Dexie('ASDB')
	
	db.version(1).stores({
		profile: '++id,creationDate,campaignId,characterId', // 
		  character: '++,profileId,name,strength,resistance,charisma',
		  characterAttributes: '++,profileId,characterId,id,cost,max,description',// id is the name of the attribute
			weapons: '++id,profileId,name,entityName,slot', // boxing-gloves.ent  
		  campaigns: '++id,profileId,name',// name : name.cmp
			 stages: '++id,campaignId,name', // name : name.stg
			 stagesList: '++id,campaignId,name,description', // name : name.stg, description, gold, score, combos
			 assets: '++id,campaignId,campaignName,name,description,collection,object'
			 /*
				asset contains all json objects listed in campaign.cmp.json
				collection: where it will be stored in: sprites|entities|brickwalls|paths|sounds 
				object: the json data of the object.xxx.json
			*/
	});		
		
	db.on("ready", function(){
		console.log('Dexie ready!')		
	})
		
	// OPEN DB	
	db.open()

  window.createExpFile = function(){
	
		console.log('attempt exporting')	
		spawn(function* (){	
		
			var tableObjects = yield db.assets.toArray()	
			
			console.log(tableObjects) 
			
			var fileName = 
			document.querySelector('#campaign_name')
			.value		
			
			fileName.replace(".cmp", ".exp");
			
			tableObjects.fileName = fileName+".json"
			
			var str_json = JSON.stringify(tableObjects)
			
			  fetch('json_download.php?fileName='+fileName+'.json', {
				
				method: 'POST',
				body: str_json,
				headers: {
					'Accept': 'application/json',			
					'Content-type': 'application/json; charset=UTF-8'
				}
				
			  })
			  .then(function(rawResponse){			  
				  loaderOff()
				  
				  console.log(rawResponse.statusText);
				  var msg = (rawResponse.statusText == 'OK')?
				  'exp file '+tableObjects.fileName+' created successfully!':
				  'error, exp file '+tableObjects.fileName+' could not be created'
				
				  dialogNotify(msg)
				  				  
				  if(rawResponse.statusText == 'OK'){
					  dialogNotify('Creating '+document.querySelector('#campaign_name').value+'.json.zip')
					  createCmpZip()
				  }
			  })	
			
			
		})// spawn
		
  }
    
  window.createCmpZip = function(){	  	
		var fileName = document.querySelector('#campaign_name').value+'.json'
		
		fetch('zipCreate.php?campaignFileName='+fileName', 
		{				
			method: 'POST',
			headers: {
				'Accept': 'application/json',			
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
		.then(function(rawResponse){
			var msg = fileName+'.zip created!'
			dialogNotify(msg)
		})	
  }
  
})//document.addEventListener('DOMContentLoaded'