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
	<link href="/sideBar.css" rel="stylesheet">
	<!-- jquery -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
		
		body
		{
			background-color: black;
		}
		.container-fluid
		{
			padding: 5px;
		}
		.col-md-4
		{
			padding-bottom: 5px;
			padding-left: 5px;
			padding-right: 5px;
		}
		.col-padding:hover
		{
			-webkit-filter: brightness(50%);
   -moz-filter: brightness(50%);
   filter: brightness(50%);
		}
		.col-padding
		{
			background-color: white;
			margin: 2.5px;
			margin-top: 5px;
			margin-bottom: 5px;
			padding-left: 40px;
			padding-right: 40px;	
			-webkit-filter: brightness(100%);
   -moz-filter: brightness(100%);
   filter: brightness(100%);
   transition: all 0.3s ease;
		}
		.title
		{
			font-size: 20px;
		}
		a:link, a:visited 
		{
			text-decoration:none;
			color: black;
		}
	
	<?php
		echo ".sidebar-nav li:first-child a {
			color: #fff;
			background-color: #";
		echo dechex(mt_rand(4337923, 16777215));
		echo "}";
		$i = 2;
		foreach(glob("./libs/*") as $thing)
		{
			echo ".sidebar-nav li:nth-child($i):before {
					background-color: #";
			echo dechex(mt_rand(4337923, 16777215));   
			echo "}";
			++$i;
		}
	?>
	</style>

	<?php	
		
		function createImage($filePath)
		{
			$imageData = file_get_contents($filePath);
			$hexValues = explode('#', $imageData);
			$height = 100/count($hexValues);
			echo "<ul class=\"list-unstyled image-container\">";
			for($i = 1;$i < count($hexValues);++$i)
			{
				$curID = (string)($i);
				$curColor = '#' . $hexValues[$i];
				echo "<li id=\"$curID\" style=\"width:100%;height:$height%;background-color:$curColor\">
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
						<a href=\"./details.php/?year=$year&school_year=$schoolYear&film=$file\"><!--pass the film's directory name to index.php-->
						<div class=\"col-padding\">";
						
				echo "<br /><br />";
				//create the color spectrum image for the film:
				$filePath = ".\\libs\\$year\\$schoolYear\\$file\\results.txt";
				createImage($filePath);
				//echo $file;
				echo "<br /><p class=\"title\" align=\"center\">$file</p>
						<br />";
				echo "</div>
					</a>
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
		function createSideBar()
		{
			echo "<li class=\"sidebar-brand sideBarElement0\">
					<a href=\"index.php\">
					   Home
					</a>
				</li>";
			foreach(glob("./libs/*") as $yearDirect)
			{
				$yearDirect = substr($yearDirect,7);
				if($yearDirect == "All")
				{
					continue;
				}
				echo "<li class=\"dropdown\">
				  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">$yearDirect<span class=\"caret\"></span></a>
				  <ul class=\"dropdown-menu\" role=\"menu\">
					<li class=\"dropdown-header\">Grade:</li>
					<li><a href=\"?year=$yearDirect&school_year=AP\">AP</a></li>
					<li><a href=\"?year=$yearDirect&school_year=IP\">IP</a></li>
					<li><a href=\"?year=$yearDirect&school_year=Thesis\">Thesis</a></li>
				  </ul>
				</li>
				";
			}
		}
	?>
  </head>
  
  <body>

	<div id="wrapper">
		<div class="overlay"></div>
	
		<!-- Sidebar -->
		<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
			<ul class="nav sidebar-nav">
				<?php createSideBar() ?>
			</ul>
		</nav>


		<!-- Page Content -->
		<div id="page-content-wrapper">
			<button type="button" class="hamburger is-closed" data-toggle="offcanvas">
				<span class="hamb-top"></span>
				<span class="hamb-middle"></span>
				<span class="hamb-bottom"></span>
			</button>
			<?php createTable(); ?>
		</div>

	</div>
	<!-- /#wrapper -->
	
	<script>
		$(document).ready(function () {
		  var trigger = $('.hamburger'),
			  overlay = $('.overlay'),
			 isClosed = false;
			trigger.click(function () {
			  hamburger_cross();      
			});
			function hamburger_cross() {
			  if (isClosed == true) {          
				overlay.hide();
				trigger.removeClass('is-open');
				trigger.addClass('is-closed');
				isClosed = false;
			  } else {   
				overlay.show();
				trigger.removeClass('is-closed');
				trigger.addClass('is-open');
				isClosed = true;
			  }
		  }
		  
		  $('[data-toggle="offcanvas"]').click(function () {
				$('#wrapper').toggleClass('toggled');
		  });  
		});
		
		$('.image-container').css('height', $(window).height()*(8/10)  + 'px');
		
		$( window ).resize(function() {
		  $('.image-container').css('height', $(window).height()*(8/10)  + 'px');
		});
	</script>
  </body>
</html>
 