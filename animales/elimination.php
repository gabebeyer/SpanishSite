<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<script src="./getimages.php"></script>
<script>
// get the list of image names from the images folder

var pics=galleryarray; 
var numTiles = 16; //8 tiles per screen
var images = new Array(); //hold the images
var tiles = new Array();  //hold the tiles
var itemSize; //the size of the tiles - derived from the window width
var fontSize; //derived from window width
var context; 
var matchImage; //image used for the background - the question
var matchLabel; //the name of the background pic
var interval; //tracks the loop
var audioElement = document.createElement('audio');  //makes a beep on wrong answer
audioElement.setAttribute('src', './beep.wav');
audioElement.load();
var audioVictory = document.createElement('audio');  //makes a beep on wrong answer
audioVictory.setAttribute('src', './bells.wav');
audioVictory.load();


//used to shuffle the array of image names. i want to randomly create each slide.
shuffle = function(o){ //v1.0
    for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
};

//i found this function on the web, because firefox was weird about document.height
function getDocHeight() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
};
//i made a copy of it for the width.
function getDocWidth() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollWidth, D.documentElement.scrollWidth),
        Math.max(D.body.offsetWidth, D.documentElement.offsetWidth),
        Math.max(D.body.clientWidth, D.documentElement.clientWidth)
    );
};
//found this function to find array element - need it for post elimination lookup
Array.prototype.findIndex = function(value){
	var ctr = "";
	for (var i=0; i < this.length; i++) {
		// use === to check for Matches. ie., identical (===), ;
		if (this[i] == value) {
			return i;
		}
	}
	return ctr;
};


//this function flips the card, checks for the right answer delivers the response
function flip(loc){
    for(t in tiles){
        if (tiles[t].Contains(loc)){ //if you click a tile        		
		// if it is not flipped, flip it and set the reflip timer
		tiles[t].flip(); 
		if (tiles[t].image.label == matchImage.label){ //and it's a match
			tiles.splice(tiles.findIndex(tiles[t]),1);
			matchImage=tiles[Math.floor(Math.random()*tiles.length)].image	
		}else{
			audioElement.play(); //otherwise, 
		}           
        }    
    }
};

//the tile is a picture on one side and the name of the file on the other.
function Tile(id,image,label){
    this.image=image;
    this.label=image.label;
    this.faceUp=0; //the counter for flipping
    this.context=context;    
    this.id=id; //used for placement
    this.size  = [1.8*itemSize,itemSize*.8]; //creates the elementSize based on the window size
    this.textPos = this.size[0]/2-fontSize*this.label.length*2/9; //text adjusted on tile based on lengths
    if(this.id<4){ //sorting the placement of two rows of tiles.
        this.x =canvas.width/25+canvas.width/4*this.id;
        this.y=12; //small adjustment from top of screen    
    }else if(this.id<8){
        this.x = canvas.width/25+canvas.width/4*(this.id-4);
        this.y=12+canvas.height/4; //small adjust from bottom.
    }else if (this.id<12){
    this.x = canvas.width/25+canvas.width/4*(this.id-8);
    this.y=12+canvas.height/2; //small adjust from bottom.
    }else if (this.id<numTiles){
    this.x = canvas.width/25+canvas.width/4*(this.id-12);
    this.y=12+canvas.height*3/4; //small adjust from bottom.
    }
    this.color  = '#'+(Math.random()*0xFFFFFF<<0).toString(16); //random color
};
Tile.prototype.flip = function(){ //flip tile
	if(this.faceUp > 0) this.faceUp=0; else this.faceUp=20;
    if (this.label == matchLabel){
        alert("¡Estás Correct@! Es " + this.label)
        window.location.reload();
    }
};

Tile.prototype.Contains = function (loc){
    if(this.x <= loc[0] && loc[0] <= this.x+this.size[0]){
        if(this.y <= loc[1] && loc[1] <= this.y+this.size[1]){
            return true;        
        }
    }
    return false;
};
Tile.prototype.Paint = function (){
	this.context.fillStyle = this.color;
	this.faceUp ? this.faceUp-1 : this.faceUp;
    if(this.faceUp > 0){  //draw image if faceUp
	this.faceUp--;
	this.context.fillRect(this.x,this.y,this.size[0],this.size[1]);
        this.context.drawImage(this.image,this.x+2,this.y+2,this.size[0]-4,this.size[1]-4);
    }else{		 //draw label if facedown
 	this.context.globalAlpha = 0.5;
        this.context.fillRect(this.x,this.y,this.size[0],this.size[1])
        this.context.globalAlpha = 1.0;
	this.context.fillStyle = "white";
        this.context.fillRect(this.x,this.y+this.size[1]/2-fontSize*5/6,this.size[0],fontSize)
        this.context.font = "small-caps "+fontSize+"px Helvetica";    
        this.context.strokeStyle = "black";
        this.context.strokeText(this.label,this.x+this.textPos,this.y+this.size[1]/2);
        this.context.stroke();
          }
    this.context.fill();
};

//the main loop
function draw(){    
	context.fillStyle="brown";
	context.fillRect(0,0,canvas.width,canvas.height);
	context.drawImage(matchImage,2,2,canvas.width-4,canvas.height-4);
	index = 0;
	while(index < tiles.length){
        	tiles[index].Paint();
		index++;
    	}
	if(tiles.length == 1){
		clearInterval(interval)
		audioVictory.play();
		setTimeout("window.location.reload(false);",3000);
	} 
	
	if(tiles.length == 0){ 
		 //reload the page		
	}
};

//the functions to get user input
function ProcessTouch(e){
    mouseX= e.touches.item(0).clientX-canvas.offsetLeft;
    mouseY= e.touches.item(0).clientY-canvas.offsetLeft;
    flip([mouseX,mouseY]);
    return false;
};
function ProcessMouse(e){
    mouseX= e.clientX-canvas.offsetLeft;
    mouseY= e.clientY-canvas.offsetLeft;
    flip([mouseX,mouseY]);   
};


function init(){
	canvas = document.getElementById("myCanvas");
      	canvas.ontouchstart = ProcessTouch;
 	canvas.height = getDocHeight()-25;
	canvas.width=getDocWidth()-20; 
	itemSize=canvas.width/9;
    	canvas.onclick = ProcessMouse;
	canvas.ontouch = ProcessTouch;
      	context = canvas.getContext('2d');
	fontSize=itemSize/5;
	images = new Array();
	tiles = new Array();
	shuffle(pics); //get random file names to front of list
	counter=0;
	while(counter<numTiles){ //grab 8 of the images and put in array
	    a=new Image();
	    a.src="./images/"+pics[counter];
	    a.label=pics[counter].substring(0,pics[counter].length-4);    
	    images.push(a);
	    counter++;
	}   
        matchImage = images[0];  //grab the first one as the background/answer.
	matchLabel = matchImage.label.toString();  //and get its label for comparison
	shuffle(images); //shuffle again to randomize the choices
	counter=0;
	while(counter<numTiles){
          	tiles.push(new Tile(counter,images[counter],images[counter].label)); 
          	counter=counter+1;
    	}
       	interval=setInterval(draw,100);
};

 </script>  
  </head>

  <body onLoad="init();">

  <div id="space">
 <canvas id="myCanvas"></canvas> 
</div>
  </body>
