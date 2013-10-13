<?php session_start(); ?>	

<?php require_once('/var/www/config.inc.php'); ?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> View photo </title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="includes/set_like.js"></script>
<script type="text/javascript" src="includes/set_dislike.js"></script>
<script type="text/javascript" src="includes/set_comment.js"></script>
<script type="text/javascript" src="includes/delete_comment.js"></script>
<script type="text/javascript" src="includes/fetch_comments.js"></script>
<script type="text/javascript" src="includes/google_map.js"></script>
<link rel="stylesheet" type="text/css" href="style/view.css" />

</head>
<body>

<?php include('includes/delete_photo.php'); ?>
<?php include("includes/header.html"); ?>

<?php
if(isset($_SESSION['username']))
{
	echo('<div id="wrapper_options">
		<div id="options">
			<p> Hi, <b><font size="3">'. $_SESSION['username'].'</font></b></p>
			<a href="profile.php">View Profile</a>
			</br>
			<a href="logout.php">Logout</a>
	     	</div>
	     </div>');
}
?>

<?php

require_once(MYSQL); 

if(isset($_GET['submitted_delete'])) // delete picture -> submit button delete pressed
{		
	if(delete_photo($_GET['submitted_id']) ) // if photo deleted redirect to profile
	{
		$url = 'profile.php';
		ob_end_clean();
		header("Location: $url");	
		exit();
	}
	else
	{
		$id = $_GET['submitted_id'];
	}
}
else if(isset($_GET['submitted_edit']))
{
	if( isset($_SESSION['session_username']) && ($_SESSION['session_username'] == $_GET['submitted_user']) )
	{
		mysqli_close($dbc);
		$url = 'edit_photo.php?image_id='.$_GET['image_id'];
		ob_end_clean();
		header("Location: $url");
		exit();	
	}
	else
	{
		$id = $_GET['submitted_id'];
		echo '<p class="error"> Sorry you have to login first </p> ';
	}
}
else
{
	$id = $_GET['image_id'];
}


$dbc = db_connect(); // connect to db
							// query to db to fetch photo infos
$query="select username,picture_title,name,description,longtitude,latitude,likes,dislikes,tags,public from picture where picture_id=?";
				// prepare query
$stmt = mysqli_prepare($dbc, $query);
  				// Bind Parameters [s for string]
mysqli_stmt_bind_param($stmt, "i", $id);
  			// execute statement
mysqli_stmt_execute($stmt);
		    				// bind result variables 
mysqli_stmt_bind_result($stmt, $username,$picture_title,$name,$description,$longtitude,$latitude,$likes,$dislikes,$tags,$public);

 			// fetch values 
if( !(mysqli_stmt_fetch($stmt)) )
{
	echo '<p class="error"> Sorry somthing goes wrong </p> ';
	die();
}

$image_infos = array( 'user' => $username, 'title' => $picture_title, 'name' => $name,
					'description' => $description, 'longtitude' => $longtitude, 
					'latitude' => $latitude, 'likes' => $likes,
					 'dislikes' => $dislikes, 'tags' => $tags, 'public' => $public );
		// close statement
mysqli_stmt_close($stmt);


/*     
	valnurable to sqlinjections

$dbc = db_connect(); // connect to db
						// query to db to fetch photo infos
$query='select * from picture where picture_id="'. $id .'"';
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );
			// if result is 1
if(mysqli_num_rows($result) == 1)
{
	$row = mysqli_fetch_array($result);
						// load photo infos to memory
	$image_infos = array( 'user' => $row['username'], 'title' => $row['picture_title'], 'name' => $row['name'],
						 'description' => $row['description'], 'longtitude' => $row['longtitude'], 
						'latitude' => $row['latitude'], 'likes' => $row['likes'],
						 'dislikes' => $row['dislikes'], 'tags' => $row['tags'], 'public' => $row['public'] );
}
else
{
	echo '<p class="error"> Sorry somthing goes wrong </p> ';
	die();
}

*/
	
?>

<?php
echo('<div id="wrapper_up">
	<div id="image_view">');
include('includes/show_photo.php');
echo('</div>');
?>

<?php
echo('<div id="image_infos">');
if(isset($image_infos))
{
	echo('<p><b><font size="2"/> Title: </b></font> <font size="3">' .$image_infos['title']. '</font></p>
	<p><b><font size="2">user:  </b></font><font size="3">'.$image_infos['user'].'</font></p>
	<p><b><font size="2"> description: </b></font><font size="3">'.$image_infos['description'].'</font></p>
	<p><b><font size="2"> longtitude: </b></font><font size="3">'.$image_infos['longtitude'].'</font></p>
	<p><b><font size="2"> latitude: </b></font><font size="3">'.$image_infos['latitude'].'</font></p>
	<p><b><font size="2"> tags: </b></font><font size="3">'.$image_infos['tags'].'</font></p>');

	if( isset($_SESSION['session_username']) && ($_SESSION['session_username'] == $image_infos['user']) )
	{
		if($image_infos['public'] == 1)
		{
			echo ('	<p> public: Yes</p>');
		}
		else if($image_infos['public'] == 0)
		{
			echo ('	<p> public: No</p>');			
		}
	}
}
echo('	</div>');
?>

<?php

if(isset($_SESSION['username']))
{
	echo '<div id="rank_place">';
	echo('	<textarea id="comment" rows="5" cols="50" maxlength="256" wrap="hard" name="comment_text" 
		onKeyDown="textCounter();"
  			onKeyUp="textCounter();" >
	</textarea></br>
 	<input id="count" readonly type="text" size="28"/>
	 <button onclick="setComment('.$id.')"> Add comment </button> ');
	echo '</div>';
}

mysqli_close($dbc);

?>

<script>
function textCounter()
{
	textareaid = document.getElementById("comment");
        if (textareaid.value.length > 256)
	{
        	textareaid.value = textareaid.value.substring(0, 256);
	}
        else
	{
        	document.getElementById("count").value = '('+(256-textareaid.value.length)+')';
	}
}


document.getElementById("comment").value = "";
textCounter();
</script>
</div>
</div>


<div id="wrapper_down">
	<div id="comments_view"></div>
	<?php echo('<script> fetchComments('.$id.'); </script>'); ?>
    
	<div id="map" style="width: 40%; height: 50%;"></div>
</div>

<?php
echo('<script>');
echo('var longtitude = '. $image_infos['longtitude'] .';');
echo('var latitude = '. $image_infos['latitude'] .';'); 
echo('var image_path = "user_space/'.$image_infos['user'].'/thumbs_medium/'.$image_infos['name'].'"'. ';');
echo('var image = new google.maps.MarkerImage(image_path, new google.maps.Size(100, 100),new google.maps.Point(0, 0), new google.maps.Point(0, 0) );');
echo('var map;');
echo('show_map(longtitude, latitude, image_path);');
echo('</script>');
?>
  
<?php include('includes/footer.html'); ?> 

</body>
</html>
