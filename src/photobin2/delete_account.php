<?php session_start() ?>

<?php require_once('/var/www/config.inc.php'); ?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> Delete Account </title>
<link rel="stylesheet" type="text/css" href="style/delete_account.css" />
</head>

</body>

<?php include('includes/header.html'); ?>

<?php
			// if user not logged in redirect to index.php -> no reason to be in here
if(!isset($_SESSION['session_username'])) 
{
	$url = BASE_URL . 'index.php';
	ob_end_clean();
	header("Location: $url");
	exit();
}
else
{
	require_once(MYSQL);
	include('includes/remove_dir.php');
	include('includes/remove_user_space.php');

	$dbc = db_connect(); // connect to db
							// query to db to delete user
	$query='delete from user where username="'. $_SESSION['username'] .'"';	
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query \n </br> MYSQL Error: " . mysqli_error($dbc));		
					// if user deleted
	if(mysqli_affected_rows($dbc) == 1)
	{
		echo '<p class="error"> Account deleted successfully </p> ';
							// query to db to delete all photo entries
		$query='delete from picture where username="'. $_SESSION['username'] .'"';	
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query \n </br> 
									MYSQL Error: " . mysqli_error($dbc));	
							// remove user space
		remove_user_space($_SESSION['username']);    	 // (***) if fail, no big deal priority to db consistency

						// query to db to delete all user comment entries
		$query='delete from comment where username="'. $_SESSION['username'] .'"';	
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query \n </br> 
									MYSQL Error: " . mysqli_error($dbc));		

		$_SESSION = array(); // unset global array session
		session_destroy();	// destroy session
		setCookie(session_name(), '', time()-300); // delete session cookie to client side			
	}
	else
	{
		echo '<p class="error"> Sorry something goes wrong </p> ';
	}

	mysqli_close($dbc); // close connection to db
}

?>

<?php include('includes/footer.html'); ?>

</body>
</html>
