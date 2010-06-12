<?

$userzip = $_GET['z'];


// ------------------- 
// INCLUDES
// -------------------
include("class.xml.parser.php");
include("class.weather.php");

// ------------------- 
// LOGIC
// -------------------
// Create the new weather object!
// CIXX0020 = Location Code from weather.yahoo.com
// 3600     = seconds of cache lifetime (expires after that)
// C        = Units in Celsius! (Option: F = Fahrenheit)

$timeout=3*60*60;  // 3 hours
if (isset($_ENV["TEMP"]))
  $cachedir=$_ENV["TEMP"];
else if (isset($_ENV["TMP"]))
  $cachedir=$_ENV["TMP"];
else if (isset($_ENV["TMPDIR"]))
  $cachedir=$_ENV["TMPDIR"];
else
// Default Cache Directory  
  $cachedir="/tmp";
  
$cachedir=str_replace('\\\\','/',$cachedir);
if (substr($cachedir,-1)!='/') $cachedir.='/';

$weather_current = new weather($userzip, 600, "C", $cachedir);

// Parse the weather object via cached
// This checks if there's an valid cache object allready. if yes
// it takes the local object data, what's much FASTER!!! if it
// is expired, it refreshes automatically from rss online!
$weather_current->parsecached(); // => RECOMMENDED!

// allway refreshes from rss online. NOT SO FAST. 
//$weather_current->parse(); // => NOT recommended!


// ------------------- 
// OUTPUT
// -------------------

// VARIOUS

$day = 0;

$city_current = $weather_current->forecast['CITY'];       // Santiago
$temperature_current = $weather_current->forecast['CURRENT']['TEMP'];       // 16
$lowtemp_current = $weather_current->forecast[$day]['LOW'];   // 8
$hightemp_current = $weather_current->forecast[$day]['HIGH']; // 19

function getWeatherColor($temperature) {

//Now I make a variable called "$today" that equals the current temperature - "$temp" divided by the Max temp - "$max" times 100 to give me a whole number

$today = ($temperature/100);

$percent = ($today*100);

//1500 is the width in px of the base image that we use
$color = $today*1500-1;

$im = imagecreatefrompng("colors.png");
$rgb = imagecolorat($im, $color, 0);
$r = ($rgb >> 16) & 0xFF;
$g = ($rgb >> 8) & 0xFF;
$b = $rgb & 0xFF;

//Result of the function echoes an rgb value in CSS format.
echo "rgb($r,$g,$b)";

}

function getWeatherPercent($temperature) {

//Now I make a variable called "$today" that equals the current temperature - "$temp" divided by the Max temp - "$max" times 100 to give me a whole number

$today = ($temperature/100);

$percent = ($today*100);

//Result of the function echoes a number
echo "$percent";

}

?>

<html>
	<head>
		<style type="text/css">
		
		/*CSS Reset*/
		body, div, dl, dt, dd, li, pre,
		form, fieldset, input, textarea, p, blockquote, th, td, button { margin: 0; padding: 0; }
		h1, h2, h3, h4, h5, h6 { margin: 0; padding: 0; font-size: 100%; font-weight: normal; }
		address, caption, cite, code, dfn, em, strong, var { font-style: normal; font-weight: normal; }
		ol, ul { list-style: none; margin: 0; padding: 0; }
		table { border-collapse: collapse; border-spacing: 0; } 
		caption, th { text-align: left; font-weight: normal; font-style: normal; }
		acronym, abbr, fieldset, img { border: 0;}
		:focus { outline: 0; }
		
	
		body {
			 background-color: <?php getWeatherColor($temperature_current);?>;
	
		}
	
		img {
			width: 100%;
			font-size: 50px;
			height: 30px;
		}
		
		#scale {
			font-family: Helvetica, Verdana, Arial, sans-serif;
			overflow: hidden;
			display: none;
	}
	
		#poop0 {
			margin-top: -23px;
			left: <?php getWeatherPercent($temperature_current);?>%;
			margin-left: -5%;
			position: relative;
			float: left;
		}				
	
		.btn-slide {
			text-align: center;
			width: 144px;
			height: 31px;
			padding: 10px 10px 0 0;
			margin: 0 auto;
			display: block;
			font: bold 120%/100% Arial, Helvetica, sans-serif;
			color: #fff;
			text-decoration: none;
		}
		
		.temperature {
			font-size: 72pt;
			text-shadow: #000 1px -1px 2px;
			}
					
		
		
		.high {
			color: <?php getWeatherColor($hightemp_current);?>;
			}
			
		.low {
			color: <?php getWeatherColor($lowtemp_current);?>;
			}			
	
		</style>

		<title>Weather in <?=$city_current?></title>
	
		<script src="http://code.jquery.com/jquery-latest.js"><!--mce:3--></script>
			
		<script type="text/javascript">
			$(document).ready(function(){
			
				$(".btn-slide").click(function(){
					$("#scale")
					.slideDown("fast")
					.animate({opacity: 1.0}, 3000)
					.slideUp("fast");
					$(this).toggleClass("active"); return false;
				});
				
				 
			});
		</script>
		
	</head>

	<body>
	
		<div id="scale">
		
			<img id="colorscale" src="colorsbig.png">
			
			<div id="poop0">
			
				<?php echo $temperature_current;?>
		
			</div>
							
		</div>
	
	<a href="#" class="btn-slide">info</a>
	
	<p>High</p>
	
	<p class="temperature high"><?php echo $hightemp_current;?></p>
	
	<p>Low</p>
	
	<p class="temperature low"><?php echo $lowtemp_current;?></p>
	
	</body>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4063957-3");
pageTracker._trackPageview();
</script>

</html>
