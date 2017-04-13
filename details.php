<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Akash & Dono">

    <title>Spectra</title>
    <script src="/ColorThief/src/color-thief.js"></script>
    <script src="/ColorThief/examples/js/jquery.js"></script>
    <script src="/ColorThief/examples/js/mustache.js"></script>
    <script src="/ColorThief/examples/js/demo.js"></script>
	<script src="/libs/Resources/html2canvas.js" type="text/javascript"></script>
    <!-- Bootstrap Core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link href="/main.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
<body onload="checkFileAPI(); readText();">

	<div id="myModal" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
		<!-- Modal content-->
			<div class="row">
				<div class="col-sm-1">
					<i class="fa fa-caret-left arrow" id="arrowLeft" aria-hidden="true"></i>
				</div>
				<div class="col-sm-10">
					<div class="modal-content" id="photoFrame">
						<img id="photoPreview">
						<canvas id="canvas"></canvas>
					</div>
				</div>
				<div class="col-sm-1">
					<i class="fa fa-caret-right arrow" id="arrowRight" aria-hidden="true"></i>
				</div>
			</div>
			<div id="colorsContainer">
				<div id=domCircle class="circles"> </div>
				<div id=circle1 class="circles"> </div>
				<div id=circle2 class="circles"> </div>
				<div id=circle3 class="circles"> </div>
				<div id=circle4 class="circles"> </div>
				<div id=circle5 class="circles"> </div>
				<div id=circle6 class="circles"> </div>
				<div id=circle7 class="circles"> </div>
				<div id=circle8 class="circles"> </div>
				<div id=circle9 class="circles"> </div>
			</div>
	  </div>
	</div>

	<div class="container-fluid" style="padding:0 !important;">  
		<div class="row">
			<a href="#" id="spectrum2jpg"><i class="fa fa-download"></i></a>
			<div class="col-sm-12" style="text-align:center; padding:0 !important;">
					<ul id="spectraVisualizationUL">
					</ul>
			</div>
		</div> 
    </div>
</body>
<script>
	Url = {
		get get(){
			var vars= {};
			if(window.location.search.length!==0)
				window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value){
					key=decodeURIComponent(key);
					if(typeof vars[key]==="undefined") {vars[key]= decodeURIComponent(value);}
					else {vars[key]= [].concat(vars[key], decodeURIComponent(value));}
				});
			return vars;
		}
	};
	$(document).ready(function(){
		$(".btn").click(function(){
			$("#myModal").modal('show');
		});
	});
    var reader; //GLOBAL File Reader object for demo purpose only, lol
	var currFilePath;
	var currImageIndex;
	var maxIndex;
	
	$('#spectrum2jpg').click(function(){
    html2canvas($('#spectraVisualizationUL'), 
    {
      onrendered: function (canvas) {
        var a = document.createElement('a');
        // toDataURL defaults to png, so we need to request a jpeg, then convert for file download.
        a.href = canvas.toDataURL("image/png").replace(/^data:image\/png/, "data:application/octet-stream");
        a.download = 'somefilename.png';
        a.click();
      }
    });
  });
    	$('#arrowRight').click(function(){
			if(currImageIndex < maxIndex){
				currImageIndex++;
			}
			else{
				currImageIndex = 1;
			}
						var sortColors = function(colors) {
			for (var c = 0; c < 9; c++) {
				/* Gets RGB vales */
				var r = colors[c][0];
				var g = colors[c][1];
				var b = colors[c][2];
				
				/* Getting the Max and Min values for Chroma. */
				var max = Math.max.apply(Math, colors[c]);
				var min = Math.min.apply(Math, colors[c]);
				/* Variables for HSV value of hex color. */
				var chr = max-min;
				var hue = 0;
				var val = max;
				var sat = 0;
				if (val > 0) {
				  /* Calculate Saturation only if Value isn't 0. */
				  sat = chr/val;
				  if (sat > 0) {
					if (r == max) { 
					  hue = 60*(((g-min)-(b-min))/chr);
					  if (hue < 0) {hue += 360;}
					} else if (g == max) { 
					  hue = 120+60*(((b-min)-(r-min))/chr); 
					} else if (b == max) { 
					  hue = 240+60*(((r-min)-(g-min))/chr); 
					}
				  }
				}
				/* Modifies existing objects by adding HSV values. */
				colors[c].hue = hue;
				colors[c].sat = sat;
				colors[c].val = val;
			  }
			  /* Sort by Hue. */
			  return colors.sort(function(a,b){return a.hue - b.hue;});
			}
			
						// These two functions convert an RGB value to HEX
			function componentToHex(c) {
				var hex = c.toString(16);
				return hex.length == 1 ? "0" + hex : hex;
			}
			
			// I changed the parameter/calls of this funciton to take in/reference an array. Aw yea.
			function rgbToHex(arr) {
				return "#" + componentToHex(arr[0]) + componentToHex(arr[1]) + componentToHex(arr[2]);
			}
			var previewDiv = document.getElementById("photoPreview");
			var previewFrame = document.getElementById("photoFrame");
			previewDiv.src = "/libs/" + Url.get.year + '/' + Url.get.school_year + '/' + Url.get.film + "/image-" + currImageIndex + ".jpeg";
			previewFrame.style.background = document.getElementById(currImageIndex.toString()).style.background;
						$("#photoPreview").one('load', function() {
			  //Akash's nifty js
			// Hiding the image that is displayed when I create img variable^^
				//$('#photoPreview').hide();
				// Creates a canvas and displays the image assigned to 'GoT' variable
				var canvas = document.getElementById('canvas');
				var context = canvas.getContext('2d');
				canvas.style.width="100%";
				canvas.style.height="100%";
				context.drawImage(photoPreview, 0, 0, canvas.width, canvas.height);
			// Contains RGB values for each pixel in giant array
			var myData = context.getImageData(0, 0, canvas.width, canvas.height);
			var data = myData.data;
			// Creates new Color Thief object
			var colorThief = new ColorThief();
			// Gets the 9 most common colors in the image. Stores the RGB values into an array of dim. 9x3
			var rgbVals = colorThief.getPalette(photoPreview, 10);
			// DONAVIN COMMENT OUT THIS LINE OF TEXT BELOW TO SEE WHAT THE HUE SORTING IS DOING
			
			rgbVals = sortColors(rgbVals);
		
			// Converts rgbVals to hexVals
			var hexVals = [rgbToHex(rgbVals[0]), rgbToHex(rgbVals[1]), rgbToHex(rgbVals[2]), rgbToHex(rgbVals[3]), rgbToHex(rgbVals[4]), rgbToHex(rgbVals[5]), rgbToHex(rgbVals[6]), rgbToHex(rgbVals[7]), rgbToHex(rgbVals[8])];
			console.log(rgbToHex);
			
			//Changes background color of the circles to the colorPalette
			document.getElementById("circle1").style.backgroundColor = hexVals[0];
			document.getElementById("circle2").style.backgroundColor = hexVals[1];
			document.getElementById("circle3").style.backgroundColor = hexVals[2];
			document.getElementById("circle4").style.backgroundColor = hexVals[3];
			document.getElementById("circle5").style.backgroundColor = hexVals[4];
			document.getElementById("circle6").style.backgroundColor = hexVals[5];
			document.getElementById("circle7").style.backgroundColor = hexVals[6];
			document.getElementById("circle8").style.backgroundColor = hexVals[7];
			document.getElementById("circle9").style.backgroundColor = hexVals[8];
			
			//Assigns Dominant Color (Skips Hex/RGB array steps) 
			document.getElementById("domCircle").style.backgroundColor = rgbToHex(colorThief.getColor(photoPreview));
			$('#canvas').hide();
			})
	});
  	$('#arrowLeft').click(function(){
			if(currImageIndex > 1){
				currImageIndex--;
			}
			else{
				currImageIndex = maxIndex;
			}
						var sortColors = function(colors) {
			for (var c = 0; c < 9; c++) {
				/* Gets RGB vales */
				var r = colors[c][0];
				var g = colors[c][1];
				var b = colors[c][2];
				
				/* Getting the Max and Min values for Chroma. */
				var max = Math.max.apply(Math, colors[c]);
				var min = Math.min.apply(Math, colors[c]);
				/* Variables for HSV value of hex color. */
				var chr = max-min;
				var hue = 0;
				var val = max;
				var sat = 0;
				if (val > 0) {
				  /* Calculate Saturation only if Value isn't 0. */
				  sat = chr/val;
				  if (sat > 0) {
					if (r == max) { 
					  hue = 60*(((g-min)-(b-min))/chr);
					  if (hue < 0) {hue += 360;}
					} else if (g == max) { 
					  hue = 120+60*(((b-min)-(r-min))/chr); 
					} else if (b == max) { 
					  hue = 240+60*(((r-min)-(g-min))/chr); 
					}
				  }
				}
				/* Modifies existing objects by adding HSV values. */
				colors[c].hue = hue;
				colors[c].sat = sat;
				colors[c].val = val;
			  }
			  /* Sort by Hue. */
			  return colors.sort(function(a,b){return a.hue - b.hue;});
			}
			
						// These two functions convert an RGB value to HEX
			function componentToHex(c) {
				var hex = c.toString(16);
				return hex.length == 1 ? "0" + hex : hex;
			}
			
			// I changed the parameter/calls of this funciton to take in/reference an array. Aw yea.
			function rgbToHex(arr) {
				return "#" + componentToHex(arr[0]) + componentToHex(arr[1]) + componentToHex(arr[2]);
			}
			var previewDiv = document.getElementById("photoPreview");
			var previewFrame = document.getElementById("photoFrame");
			previewDiv.src = "/libs/" + Url.get.year + '/' + Url.get.school_year + '/' + Url.get.film + "/image-" + currImageIndex + ".jpeg";
			previewFrame.style.background = document.getElementById(currImageIndex.toString()).style.background;
						$("#photoPreview").one('load', function() {
			  //Akash's nifty js
			// Hiding the image that is displayed when I create img variable^^
				//$('#photoPreview').hide();
				// Creates a canvas and displays the image assigned to 'GoT' variable
				var canvas = document.getElementById('canvas');
				var context = canvas.getContext('2d');
				canvas.style.width="100%";
				canvas.style.height="100%";
				context.drawImage(photoPreview, 0, 0, canvas.width, canvas.height);
			// Contains RGB values for each pixel in giant array
			var myData = context.getImageData(0, 0, canvas.width, canvas.height);
			var data = myData.data;
			// Creates new Color Thief object
			var colorThief = new ColorThief();
			// Gets the 9 most common colors in the image. Stores the RGB values into an array of dim. 9x3
			var rgbVals = colorThief.getPalette(photoPreview, 10);
			// DONAVIN COMMENT OUT THIS LINE OF TEXT BELOW TO SEE WHAT THE HUE SORTING IS DOING
			
			rgbVals = sortColors(rgbVals);
		
			// Converts rgbVals to hexVals
			var hexVals = [rgbToHex(rgbVals[0]), rgbToHex(rgbVals[1]), rgbToHex(rgbVals[2]), rgbToHex(rgbVals[3]), rgbToHex(rgbVals[4]), rgbToHex(rgbVals[5]), rgbToHex(rgbVals[6]), rgbToHex(rgbVals[7]), rgbToHex(rgbVals[8])];
			console.log(rgbToHex);
			
			//Changes background color of the circles to the colorPalette
			document.getElementById("circle1").style.backgroundColor = hexVals[0];
			document.getElementById("circle2").style.backgroundColor = hexVals[1];
			document.getElementById("circle3").style.backgroundColor = hexVals[2];
			document.getElementById("circle4").style.backgroundColor = hexVals[3];
			document.getElementById("circle5").style.backgroundColor = hexVals[4];
			document.getElementById("circle6").style.backgroundColor = hexVals[5];
			document.getElementById("circle7").style.backgroundColor = hexVals[6];
			document.getElementById("circle8").style.backgroundColor = hexVals[7];
			document.getElementById("circle9").style.backgroundColor = hexVals[8];
			
			//Assigns Dominant Color (Skips Hex/RGB array steps) 
			document.getElementById("domCircle").style.backgroundColor = rgbToHex(colorThief.getColor(photoPreview));
			$('#canvas').hide();
			})
	});
    /**
     * Check for the various File API support.
     */
    function checkFileAPI() {
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            reader = new FileReader();
            return true; 
        } else {
            alert('The File APIs are not fully supported by your browser. Fallback required.');
            return false;
        }
    }
    /**
     * read text input
     */
    function readText() {
		var filePath = 'http://localhost:8080/libs/' + Url.get.year + '/' + Url.get.school_year + '/' + Url.get.film + '/' + 'results.txt';
        var output = ""; //placeholder for text output
            try {
				xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",filePath,false);
				xmlhttp.send(null);
				var fileContent = xmlhttp.responseText;
				displayContents(fileContent);
            } catch (e) {
                if (e.number == -2146827859) {
                    alert('Unable to access local files due to browser security settings. ' + 
                     'To overcome this, go to Tools->Internet Options->Security->Custom Level. ' + 
                     'Find the setting for "Initialize and script ActiveX controls not marked as safe" and change it to "Enable" or "Prompt"'); 
                }
            }             
        return true;
    }   
    /**
     * display content using a basic HTML replacement
     */
    function displayContents(txt) {
		var hex_values = txt.split('#');
		makeUL(hex_values);
    }
	function makeUL(array) {
		// Create the list element:
		var list = document.getElementById('spectraVisualizationUL');
		currFilePath = (array[1]);
		maxIndex= array.length;
		for(var i = 3; i < array.length+1; i++) {
			// Create the list item:
			var item = document.createElement('li');
			var divColorBar = document.createElement("div");
			divColorBar.id = (i-2).toString();
			divColorBar.className = "avgColorImg";
			divColorBar.style.height = "1.5vh";
			divColorBar.style.background = "#"+array[i-1];
			 
			// Set its contents:
			item.appendChild(divColorBar);
			// Add it to the list:
			list.appendChild(item);
		}
		
					// Modified a function I found online that sorts colors by Hue 
			// https://era86.github.io/2011/11/15/grouping-html-hex-colors-by-hue-in.html
			var sortColors = function(colors) {
			for (var c = 0; c < 9; c++) {
				/* Gets RGB vales */
				var r = colors[c][0];
				var g = colors[c][1];
				var b = colors[c][2];
				
				/* Getting the Max and Min values for Chroma. */
				var max = Math.max.apply(Math, colors[c]);
				var min = Math.min.apply(Math, colors[c]);
				/* Variables for HSV value of hex color. */
				var chr = max-min;
				var hue = 0;
				var val = max;
				var sat = 0;
				if (val > 0) {
				  /* Calculate Saturation only if Value isn't 0. */
				  sat = chr/val;
				  if (sat > 0) {
					if (r == max) { 
					  hue = 60*(((g-min)-(b-min))/chr);
					  if (hue < 0) {hue += 360;}
					} else if (g == max) { 
					  hue = 120+60*(((b-min)-(r-min))/chr); 
					} else if (b == max) { 
					  hue = 240+60*(((r-min)-(g-min))/chr); 
					}
				  }
				}
				/* Modifies existing objects by adding HSV values. */
				colors[c].hue = hue;
				colors[c].sat = sat;
				colors[c].val = val;
			  }
			  /* Sort by Hue. */
			  return colors.sort(function(a,b){return a.hue - b.hue;});
			}
			
						// These two functions convert an RGB value to HEX
			function componentToHex(c) {
				var hex = c.toString(16);
				return hex.length == 1 ? "0" + hex : hex;
			}
			
			// I changed the parameter/calls of this funciton to take in/reference an array. Aw yea.
			function rgbToHex(arr) {
				return "#" + componentToHex(arr[0]) + componentToHex(arr[1]) + componentToHex(arr[2]);
			}
			
		var classname = document.getElementsByClassName("avgColorImg");
		var myFunction = function() {
			var previewDiv = document.getElementById("photoPreview");
			var previewFrame = document.getElementById("photoFrame");
			currImageIndex = this.id;
			previewDiv.src = "/libs/" + Url.get.year + '/' + Url.get.school_year + '/' + Url.get.film + "/image-" + this.id + ".jpeg";
			previewFrame.style.background = this.style.background;
			$('#myModal').modal('show');
			$("#photoPreview").one('load', function() {
			  //Akash's nifty js
			// Hiding the image that is displayed when I create img variable^^
				//$('#photoPreview').hide();
				
				// Creates a canvas and displays the image assigned to 'GoT' variable
				var canvas = document.getElementById('canvas');
				var context = canvas.getContext('2d');
				canvas.style.width="100%";
				canvas.style.height="100%";
				context.drawImage(photoPreview, 0, 0, canvas.width, canvas.height);
			
			// Contains RGB values for each pixel in giant array
			var myData = context.getImageData(0, 0, canvas.width, canvas.height);
			var data = myData.data;
			
			// Creates new Color Thief object
			var colorThief = new ColorThief();
			
			// Gets the 9 most common colors in the image. Stores the RGB values into an array of dim. 9x3
			var rgbVals = colorThief.getPalette(photoPreview, 10);
			// DONAVIN COMMENT OUT THIS LINE OF TEXT BELOW TO SEE WHAT THE HUE SORTING IS DOING
			rgbVals = sortColors(rgbVals);
			
			// Converts rgbVals to hexVals
			var hexVals = [rgbToHex(rgbVals[0]), rgbToHex(rgbVals[1]), rgbToHex(rgbVals[2]), rgbToHex(rgbVals[3]), rgbToHex(rgbVals[4]), rgbToHex(rgbVals[5]), rgbToHex(rgbVals[6]), rgbToHex(rgbVals[7]), rgbToHex(rgbVals[8])];
			console.log(rgbToHex);
			
			//Changes background color of the circles to the colorPalette
			document.getElementById("circle1").style.backgroundColor = hexVals[0];
			document.getElementById("circle2").style.backgroundColor = hexVals[1];
			document.getElementById("circle3").style.backgroundColor = hexVals[2];
			document.getElementById("circle4").style.backgroundColor = hexVals[3];
			document.getElementById("circle5").style.backgroundColor = hexVals[4];
			document.getElementById("circle6").style.backgroundColor = hexVals[5];
			document.getElementById("circle7").style.backgroundColor = hexVals[6];
			document.getElementById("circle8").style.backgroundColor = hexVals[7];
			document.getElementById("circle9").style.backgroundColor = hexVals[8];
			
			//Assigns Dominant Color (Skips Hex/RGB array steps) 
			document.getElementById("domCircle").style.backgroundColor = rgbToHex(colorThief.getColor(photoPreview));
			$('#canvas').hide();
			})
		};
		for (var i = 0; i < classname.length; i++) {
			classname[i].addEventListener('click', myFunction, false);
		}
		
		//$('#myModal').modal({ show: false});
	}
</script>
</html>