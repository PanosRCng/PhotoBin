<?php

session_start();

require_once('/var/www/config.inc.php');
require_once(MYSQL); 
mb_internal_encoding("UTF-8");
include('../Reform.inc.php');

$picture_id=$_GET["picture_id"];
$comment_text=$_GET['comment_text'];

if ( strlen($picture_id) > 0 )
{
		// prevent someone to store javascript to server and mess up client later
		// converts tags to html entitys
		// better from simple escaping -> weak to String.fromCharCode()

	$reform = new Reform; // OWASP anti-xss class
	$comment_text = $reform->HtmlEncode($comment_text); 

	if( set_comment($picture_id, $comment_text) )
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}

function set_comment($picture_id, $comment_text)
{				// if user logged in
	if( isset($_SESSION['session_username']) )
	{	
					// if user have commented this photo in this session
		if( isset($_SESSION['id_commented']) )
		{ 	
			if($_SESSION['id_commented'] == $picture_id )
			{
				return 0;
			}
		}
				// connect to db
		$dbc = db_connect();
								// query to db to fetch photo infos
		$query="insert into comment (picture_id, username, comment_text) values(?,?,?)";
						// prepare query
		$stmt = mysqli_prepare($dbc, $query);
										// Bind Parameters
		mysqli_stmt_bind_param($stmt, 'iss', $picture_id, $_SESSION['username'], $comment_text);
		  			// execute statement
		mysqli_stmt_execute($stmt);
	 					// if one affected ok  
    		if(mysqli_stmt_affected_rows($stmt) == 1)
		{
			$_SESSION['id_commented'] = $picture_id;	
			return 1;
		}

		mysqli_close($dbc); // close db connection
	}

	return 0;
}

?>
