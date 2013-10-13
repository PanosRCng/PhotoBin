<?php session_start();

if( !isset($_SESSION['session_username']))
{
	die();
}
?>

<?php
require_once('/var/www/config.inc.php');
mb_internal_encoding("UTF-8");
include('Reform.inc.php');
?> 

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> edit photos </title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="includes/google_map.js"></script>
<script type="text/javascript" src="includes/check_form_fields.js"></script>
<script type="text/javascript" src="includes/live_search.js"></script>
<link rel="stylesheet" type="text/css" href="style/edit_photo.css" />
</head>
<body>

<?php include("includes/header.html"); ?>
<?php include('includes/update_photo.php'); ?>

<?php
	echo('<div id="wrapper_options">
		<div id="options">
			<p> Hi, '. $_SESSION['username'].'</p>
			<a href="profile.php">View Profile</a>
			</br>
			<a href="logout.php">Logout</a>
		</div>
	</div>');
?>

<?php

require_once(MYSQL);
				// if submit button save changes pressed
if(isset($_POST['submitted_infos']))
{
	$title = $description = $tags = $longtitude = $latitude = $public = FALSE;

	$trimmed = array_map('trim', $_POST); // remove spaces from end and start of infos

		// prevent someone to store javascript to server and mess up client later
		// converts tags to html entitys
		// better from simple escaping -> weak to String.fromCharCode()

	$reform = new Reform; // OWASP anti-xss class
	$title = $reform->HtmlEncode($trimmed['title']); 
	$description = $reform->HtmlEncode($trimmed['description']);
	$tags = $reform->HtmlEncode($trimmed['tags']);

						// escaping special characters for sql use 		
	$title = mysqli_real_escape_string($dbc, $title);
	$description = mysqli_real_escape_string($dbc, $description);
	$tags = mysqli_real_escape_string($dbc, $tags);
	$longtitude = mysqli_real_escape_string($dbc, $trimmed['longtitude']);
	$latitude = mysqli_real_escape_string($dbc, $trimmed['latitude']);

	if(isset($_POST['public_checkbox']))
	{
		$public = 1;
	}
	else
	{
		$public = 0;
	}

	if($title && $description)
	{	
		if( update_photo($_POST['image_id'], $title, $description, $tags, $longtitude, $latitude, $public) )
		{
			$url = 'view.php?image_id='.$_POST['image_id'].'';
			ob_end_clean();
			header("Location: $url");
			
			exit();
		}
		else
		{
			echo '<p class="error"> Changes do not saved </p> ';
		}
		
	}
	else
	{
		echo '<p class="error"> Title and description must not be empty </p> ';
	}
}
?>

<?php

if(isset($_GET['image_id']))
{
	$image_id = $_GET['image_id'];
}
else
{
	$image_id = $_POST['image_id'];
}

$dbc = db_connect();	// connect to db
								// query to db to fetch photo infos
$query='select * from picture where picture_id="'. $image_id .'"';
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );
			// if 1 result 
if(mysqli_num_rows($result) == 1)
{
	$row = mysqli_fetch_array($result);
								// load photo infos
	$image_infos = array( 'user' => $row['username'], 'title' => $row['picture_title'], 'name' => $row['name'],
						 'description' => $row['description'], 'longtitude' => $row['longtitude'], 
						'latitude' => $row['latitude'], 'likes' => $row['likes'],
						 'dislikes' => $row['dislikes'], 'tags' => $row['tags'], 'public' => $row['public'] );
}
else
{
	mysqli_close($dbc);
	echo '<p class="error"> Sorry somthing goes wrong </p> ';
	exit();
}

mysqli_close($dbc); // close connection to db
							// make a photo thumbnail
echo '<div id="wrapper_up"><div id="pic"><img src="includes/scale_small_thumbnail.php?id=' . $image_id . '" width="100%"/></div></div>';

include("edit_info.php"); // show edit fields
				// pin photo to google map
echo '<script type="text/javascript"> 
	var longtitude = '.$longtitude_text.';
	var latitude = '.$latitude_text.'; 
	var map;
	get_location();
	show_upload_map(longtitude, latitude);
       </script>';
?>

<?php include('includes/footer.html'); ?>

</body>
</html>
