<?php session_start() ?>

<?php require_once('/var/www/config.inc.php'); ?>
<?php require_once(MYSQL); ?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Photobin, share your experience</title>
<link rel="stylesheet" type="text/css" href="style/index.css" />
<link rel="stylesheet" type="text/css" href="style/tagcloud.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="includes/google_map.js"></script>
<script type="text/javascript" src="includes/liveFetchPhotos.js"></script>
<script type="text/javascript" src="includes/googleMap_markers.js"></script>
<script type="text/javascript" src="includes/live_search.js"></script>
<script type="text/javascript" src="includes/search_photos.js"></script>
</head>

<?php include('includes/header.html'); ?>

<body>

<?php 

if(isset($_SESSION['session_username']))
{
	echo('<div id="wrapper_options">
		<div id="options">
			<p> Hi, <b><font size="3">'. $_SESSION['username'].' </font></b></p>
			<a href="profile.php">View Profile</a>
			</br>
			<a href="logout.php">Logout</a>
		</div>
	      </div>');
}
else
{
		echo('<div id="options">
		      	<a href="login.php">Login</a>
			</br>
			<a href="register.php">Create Account</a> 
  		     </div>');
}

?>

<div id="wrapper_upper"> 
	<div id="browse_div">
	<a href="browse.php"><button style="height: 5%; width: 50%; "> Browse Photos </button></a>
	</div>
</div>

<div id="wrapper_up">
<div id="pics">
<?php 

echo('<p> <b>High ranked </b> </p>');
		// connect to database
$dbc = db_connect();
							// query to db to fetch the first 10 most ranked by like photos
$query='select picture_id from picture where public="1" order by likes DESC limit 0, 10';
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );
			// if have resutls
if(mysqli_num_rows($result) != 0)
{						// built photo table from reuslts
	echo('<table border="0" cellspacing="5" cellpadding="5"><tr>');
	for($i=1; $i<=mysqli_num_rows($result); $i++)
	{
		$row = mysqli_fetch_array($result);

		echo '<td><a href="view.php?image_id='.$row['picture_id'].'">
		<img src="includes/scale_small_thumbnail.php?id='.$row['picture_id'].'" width="100%" border="0"></a></td>';
	
		if($i%5==0)
		{
			echo('  </tr><tr>');
		}
	}
	echo('  </tr></table>');
}
else
{
	echo '<p class="error"> No uploaded photos </p> ';
}

?>
</div>

<div id="searchbox_div">
<input id="search_field" onblur="(function(){ document.getElementById('livesearch').style.display='none'; })();" type="text" name="search" size="20" autocomplete="off"/><button id="search_button" onClick="get_index_search()"> Search </button>

<div id="livesearch" style="width: 70%;"></div>
</div>

<script type="text/javascript">
document.onkeypress = stopRKey;

var divs = document.getElementById('livesearch').getElementsByTagName('div');
var selectedDiv = -1;
var i;

document.getElementById('search_field').onkeyup = function(e){

	var x = 0;
        if(e.keyCode == 38)
             x = -1;
        else if(e.keyCode == 40)
             x = 1;
	else if(e.keyCode == 13)
	{
		old = document.getElementById('search_field').value;

		strArray = old.split(" ");

		new_text ="";
			
		for(i=0; i<strArray.length-1; i++)
		{
			new_text += strArray[i] + ' ';
		}
		
		document.getElementById('livesearch').style.display='none';	
		document.getElementById('search_field').value = new_text + (divs[selectedDiv].textContent);

		showResult("");
	}
        else
	{
             showResult(document.getElementById('search_field').value);

	     selectedDiv = -1;		

	     return;	
	}	

	if(selectedDiv != -1)
	{
        	divs[selectedDiv].style.backgroundColor = '';
	} 
	
        selectedDiv = ((selectedDiv+x)%divs.length);
        selectedDiv = selectedDiv < 0 ?
	divs.length+selectedDiv : selectedDiv;

	divs[selectedDiv].style.backgroundColor = '#E0E0E0';
};
</script>


</div>

<div id="wrapper_down">
<div id="tagcloud">

<h2>Popular Searches</h2>

<?php 

$terms = array();
$maximum = 0;
 					// query to get tags for building tag cloud
$query = "select tag_title, counter from tag order by counter DESC LIMIT 30";
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );

if(mysqli_num_rows($result) != 0)
{
	for($i=1; $i<=mysqli_num_rows($result); $i++)
	{
		$row = mysqli_fetch_array($result);
 
  		$term = $row['tag_title'];
    		$counter = $row['counter'];

		if ($counter > $maximum) $maximum = $counter;
 
    		$terms[] = array('term' => $term, 'counter' => $counter);
	}
}
		// close db connection
mysqli_close($dbc);

shuffle($terms); 

foreach ($terms as $term):

	$percent =  floor(($term['counter'] / $maximum) * 100);

	if ($percent < 20):
        	$class = 'smallest';
        elseif ($percent >= 20 and $percent < 40):
        	$class = 'small';
        elseif ($percent >= 40 and $percent < 60):
        	$class = 'medium';
        elseif ($percent >= 60 and $percent < 80):
        	$class = 'large';
        else:
        	$class = 'largest';
        endif;
	?>

	<span class="<?php echo $class; ?>">
		<a href="search.php?search=<?php echo $term['term']; ?>&mode=0"> <?php echo $term['term']; ?></a>
	</span>

<?php endforeach; ?>
</div>

<div id="map" style="width: 60%; height: 60%;"></div>

</div>

<script type="text/javascript">
var offset = 268435456;
var radius = 85445659.4471;
var last_dist;
var map;	 // our map
var infowindow;  // info window for image markers
var need_id = new Array();
var size = new Array();
var markersArray = [];
initialize();  // init map
google.maps.event.addListener(map, 'idle', refreshMarkers); // add listener to map refresh markers 
								// fired when the map becomes idle after panning or zooming.
get_location();  // try to get location if html5 geolocaiton is supported
</script>

<?php include('includes/footer.html'); ?> 

</body>
</html>
