<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTL 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Akash & Dono">

    <title>Spectra</title>

    <!-- Bootstrap Core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="/main.css" rel="stylesheet">
	<!-- jquery -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
	
		.dropdown-submenu {
			position: relative;
		}

		.dropdown-submenu .dropdown-menu {
			top: 0;
			left: 100%;
			margin-top: -1px;
		}
		
		body
		{
			background-color: grey;
		}
		.row
		{
			margin-top: 1px;
		}
		.col-md-4
		{
			padding-left: 5px;
			padding-right: 5px;
		}
		.col-padding:hover
		{
			filter: brightness(30%);
		}
		.col-padding
		{
			background-color: white;
			margin: 2.5px;
			margin-top: 5px;
			margin-bottom: 5px;
			padding-left: 40px;
			padding-right: 40px;
		}
		.image-container:after {
			content: '';
			position: absolute;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			box-shadow: inset 0px 0px 150px 60px rgba(0,0,0,0.8);
		}
		.title
		{
			font-size: 20px;
		}
	
	
	
	
	<?php
		for($i = 0;$i < 20;++$i)
		{
			echo ".sideBarElement$i:before
			{
				background-color:";
			echo dechex(mt_rand(0, 16777215));
				
			echo "}";
		}

	?>
	</style>

	<?php	
		
		function createImage($filePath)
		{
			$imageData = file_get_contents($filePath);
			$hexValues = split('#', $imageData);
			$height = 800/count($hexValues).'px';
			echo "<ul class=\"list-unstyled\">";
			for($i = 1;$i < count($hexValues);++$i)
			{
				$curID = (string)($i);
				$curColor = '#' . $hexValues[$i];
				
				//echo $curColor;
				echo "<li>
				
						<div id=\"$curID\" className=\"avgColorImg\" style=\"width:100%;height:$height;background-color:$curColor\">	
						</div>
						
					</li>";
			}
			echo "</ul>";
		}
		function createTable()
		{
			//$dirHandle = opendir('./libs/');
			$columnCount = 0;
			echo "<div class=\"container-fluid\">";
			
			$year = (empty($_GET["year"])) ? "All" : $_GET["year"];
			$schoolYear = (empty($_GET["school_year"])) ? "All" : $_GET["school_year"];
			
			echo "<div class=\"row\">";
			foreach(glob("./libs/$year/$schoolYear/*") as $file)//get the name of every directory in libs as file
			{
				$file = substr($file, 9+strlen($year)+strlen($schoolYear));

				echo "<div class=\"col-md-4 col-sm-6\">
						<div class=\"col-padding\">
						<a href=\"./index.php/?year=$year&school_year=$schoolYear&film=$file\">";//pass the film's directory name to index.php
						
				echo "<br /><br />";
				//create the color spectru image for the film:
				$filePath = ".\\libs\\$year\\$schoolYear\\$file\\results.txt";
				createImage($filePath);
				//echo $file;
				echo "<br /><p class=\"title\" align=\"center\">$file</p>
						<br />";

				echo "</a>
					</div>
					</div>";
				
				
				if($columnCount == 2)
				{
					echo "</div>";
					echo "<div class=\"row\">";
					$columnCount = 0;
				}
				else
				{
					++$columnCount;
				}
			}
			echo "</div>";
			echo "</div>";
		}
		
	?>
  </head>
  
  <body>

	<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Films
    </button>
    <ul class="dropdown-menu" >
      <li class="dropdown-submenu">
        <a class="test" tabindex="-1" href="#">2017</a>
        <ul class="dropdown-menu">
          <li style="opacity: 1"><a tabindex="-1" href="home.php/?year=2016&school_year=Thesis">Thesis</a></li>
          <li style="opacity: 1"><a tabindex="-1" href="#">AP</a></li>
          <li style="opacity: 1"><a tabindex="-1" href="#">IP</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu">
        <a class="test" tabindex="-1" href="#">2016</a>
        <ul class="dropdown-menu">
          <li><a tabindex="-1" href="#">Thesis</a></li>
          <li><a tabindex="-1" href="#">AP</a></li>
          <li><a tabindex="-1" href="#">IP</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu">
        <a class="test" tabindex="-1" href="#">2015</a>
        <ul class="dropdown-menu">
          <li><a tabindex="-1" href="#">Thesis</a></li>
          <li><a tabindex="-1" href="#">AP</a></li>
          <li><a tabindex="-1" href="#">IP</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu">
        <a class="test" tabindex="-1" href="#">2014</a>
        <ul class="dropdown-menu">
          <li><a tabindex="-1" href="#">Thesis</a></li>
          <li><a tabindex="-1" href="#">AP</a></li>
          <li><a tabindex="-1" href="#">IP</a></li>
        </ul>
      </li>
    </ul>
  </div>

	<?php
		createTable();
	?>
	
	
	<script>

	
	$(document).ready(function(){
	  $('.dropdown-submenu a.test').on("click", function(e){
		$(this).next('ul').toggle();
		e.stopPropagation();
		e.preventDefault();
	  });
	});
	</script>
  </body>
</html>
 