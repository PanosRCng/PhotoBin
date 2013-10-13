
<?php session_start(); ?>

<?php require_once('/var/www/config.inc.php'); ?>

<?php

$id = $_GET['id'];

if($id)
{
	require_once(MYSQL); 

	$dbc = db_connect(); // connect to db
							// query to db to fetch photo infos
	$query='select * from picture where picture_id="'. $id .'"';
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );
				// if result is 1
	if(mysqli_num_rows($result) == 1)
	{
		$row = mysqli_fetch_array($result);
							// load photo infos to memory
		$image_infos = array( 'user' => $row['username'], 'name' => $row['name'], 'public' => $row['public'] );

		if( $image_infos['public'] == 0 )
		{
			if( $image_infos['user'] != $_SESSION['username'] )
			{
				die(); // fuck off
			}
			else
			{
				$image_path = USER_SPACE_PRIVATE.$image_infos['user'].'/';
			}
		}
		else
		{
			$image_path = USER_SPACE_PUBLIC.$image_infos['user'].'/';
		}

		$image_src = $image_path.$image_infos['name'];
		
		print $image_src;

		header('Content-Type: image/jpeg');
		include('SimpleImage.php');
	        $image = new SimpleImage();
		$image->load('/var/www/user_space/panosracing/4fa0137256853');
		$image->output();

	}
	else
	{
		echo '<p class="error"> Sorry something goes wrong </p> ';
		die();
	}
}

?>

