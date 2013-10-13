<?php session_start();

if( !isset($_SESSION['session_username']))
{
	die();
}
?>

<?php
require_once('/var/www/config.inc.php'); 
require_once(MYSQL); 
include('includes/SimpleImage.php');
include('includes/free_spacebar.php'); 
include('includes/save_image.php'); 
include('includes/remove_dir.php'); 
include("upload_functions.php");
mb_internal_encoding("UTF-8");
include('Reform.inc.php');
?>
 
<?php $_SESSION['photo_up'] = 0; ?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> upload photos </title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="includes/google_map.js"></script>
<script type="text/javascript" src="includes/check_form_fields.js"></script>
<script type="text/javascript" src="includes/live_search.js"></script>
<link rel="stylesheet" type="text/css" href="style/upload.css" />
</head>
<body>

<?php include("includes/header.html"); ?>

<?php
	echo('<div id="wrapper_options">
		<div id="options">
			<p> Hi, <b><font size="3">'. $_SESSION['username'].'</font></b></p>
			<a href="profile.php">View Profile</a>
			</br>
			<a href="logout.php">Logout</a>
	      	</div>
	      </div>');
?>

<?php

if(isset($_POST['submitted']))
{
	if ($_FILES['upload']['error'] > 0)
  	{
		print('<p class="error"> There was an error, try again </p>');
  	}
	else
	{
		/*
			php.ini
			-> max_file_uploads = 20 -> 1
			-> upload_max_file_size = 200MB -> 25MB
			-> max_execution_time = 30
			-> max_input_time = 60
			-> memory_limit = 128MB
			-> upload_tmp_dir = /tmp
			-> post_max_size = 200MB -> 26MB
		*/
		if(!empty($_FILES['upload']['name']))
		{						// an to arxeio xoraei sto userspace
			if( $_FILES['upload']['size'] < space_left($_SESSION['username']) )
			{
				$allowed_image_type = array('image/pjpeg', 'image/jpeg', // photo types allowed
						 'image/JPG', 'image/X-PNG', 
						 'image/PNG', 'image/png',
						 'image/png', 'image/x-png');

				$allowed_data_type = array('application/zip');	// data types allowed

				$finfo = finfo_open(FILEINFO_MIME_TYPE);	// check filetype
				$uploaded_file_type = finfo_file($finfo, $_FILES['upload']['tmp_name']);
						// generate random name for tmp user space by time in nanoseconds
				$tmp_folder = uniqid();
				$_SESSION['tmp_folder'] = $tmp_folder;
				$user_tmp_dir = TMP_SPACE.$tmp_folder;

							// if trash tmp userspace exists remove it
				if(file_exists($user_tmp_dir))
				{
					if( !remove_dir($user_tmp_dir) )
					{
						return 0;
					}
				}					
									// make tmp user space
				if( mkdir($user_tmp_dir , 0777, true) )
				{						// if file a photo
					if(in_array($uploaded_file_type, $allowed_image_type)) 
					{							
      						$image = new SimpleImage();		// load image to memory
      						$image->load_up($_FILES['upload']['tmp_name']);
								
						$width = $image->getWidth();
						if( $width > 1280 )		//resize image if big
						{
      							$image->resizeToWidth(1280);
						}	

						$height = $image->getHeight();
						if( $height > 1024 )
						{
							$image->resizeToHeight(1024);
						}

						$photo_name = uniqid();
										// move image to tmp userspace
	      		 			$image->save($user_tmp_dir.'/'.$photo_name);
						$_SESSION['photo_name'] = $photo_name;
											
						$_SESSION['photo_up'] = 1;
					}							// if file is a zip
					else if(in_array($uploaded_file_type, $allowed_data_type))
					{			// generate random name by time in nanoseconds
						$file_name = uniqid();
						$zip_path = $user_tmp_dir.'/'.$file_name;
											// move zip to tmp folder
						move_uploaded_file($_FILES['upload']['tmp_name'], $zip_path);
										// unzip file
						if( unzip($zip_path, $user_tmp_dir) )
						{
							print('<p class="error"> File unzipped </p>');
										   
							parse_xml($user_tmp_dir.'/info.xml');

							unlink($user_tmp_dir.'/info.xml');
						}
						else
						{
							print('<p class="error"> Can not unzip this file </p>');
						}

						remove_dir($user_tmp_dir);
					}	
					else
					{
						print('<p class="error"> File not a (jpeg, png) photo and not a zip file </p>');
					}
				}
			}
			else
			{
				print('<p class="error"> You have not enough free space </p>');
			}

			if( (file_exists($_FILES['upload']['tmp_name'])) && (is_file($_FILES['upload']['tmp_name'])) )
			{
				unlink($_FILES['upload']['tmp_name']);
	
			}
		}
	}
}

if(isset($_POST['submitted_infos'])) // if submitted photo infos -> "Save" submit button pressed
{
	$trimmed = array_map('trim', $_POST);	// remove spaces from start and end of infos and load to trimmed

	$title = $description = FALSE;

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

	if(isset($trimmed['public']))
	{
		$public = TRUE;
	}
	else
	{
		$public = FALSE;
	}

	if($title && $description) // insert only if title and description are not empty
	{
		$name = $_SESSION['photo_name'];
						//insert photo entry to db, insert tags entries to db, and save photo to userspace
		if( save_image($title, $description, $tags, $longtitude, $latitude, $public, $name) )
		{
			echo '<p class="error"> Photo uploaded </p> ';
			$user_tmp_dir = TMP_SPACE.$_SESSION['tmp_folder'];

			remove_dir($user_tmp_dir);
		}
		else
		{
			echo '<p class="error"> Can not upload image </p> ';
		}		
	}
	else
	{
		echo '<p class="error"> Title and description must not be empty </p> ';
	}

	mysqli_close($dbc); // close db connection
}

?>

<?php
echo '
<div id="wrapper_up">
	<div id="free_spacebar">';
free_spacebar($_SESSION['username']);

$max_upload = format_bytes(UPLOAD_MAX_FILE_SIZE);
echo('</div>');
echo('
	<div id="upload_field">
		<fieldset><legend> Upload photos, max file size -> '.$max_upload.' MB:</legend>
			<form enctype="multipart/form-data" action="upload.php" method="post">
				<input type="hidden" name="MAX_FILE_SIZE" value="'.UPLOAD_MAX_FILE_SIZE.'">
					<p> File <input type="file" name="upload"></p>
					<div align="left">
						<input type="submit" name="submit" value="Upload" />
					</div>
					<input type="hidden" name="submitted" value="TRUE" />
			</form>
		</fieldset>
	</div>
</div>');
?>

<?php
	if($_SESSION['photo_up'] == 1) // if image uploaded to temp space
	{											// view a small thumbnail
		echo '</br><div id="pic"><img src="includes/scale_small_thumbnail_tmp.php"/>'; 

		echo '</br><b><font size="2" color="red"> add some infos to finish upload... </font></b></div>'; 

		include("photo_infos_form.php"); // view a form to input photo infos 
								// view a map to give location
		echo '<script type="text/javascript">        
			var longtitude = 1.286016195312527;
			var latitude = 29.91697035716784; 
			var map;
			get_location();
			show_upload_map(longtitude, latitude); </script>';
	}
?>

<?php include("includes/footer.html"); ?>


</body>
</html>
