<?php

session_start();

require_once('/var/www/config.inc.php');
require_once(MYSQL); 

$picture_id=$_GET["picture_id"];

if ( strlen($picture_id) > 0 )
{
	view_comments($picture_id);
}


function view_comments($id)
{
	echo('<p><b>Comments</b></p>');

			// connect to db
	$dbc = db_connect();
							// query to db to fetch photo infos
	$query='select username from picture where picture_id="'. $id .'"';
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );

	if(mysqli_num_rows($result) == 1) // if fetch 1 result
	{
		$row = mysqli_fetch_array($result);	// load picture info
		$image_infos = array( 'user' => $row['username'] );
							// query to db to fetch all comments for this photo
		$query='select * from comment where picture_id="'. $id .'" order by comment_id DESC ';
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );

		if(mysqli_num_rows($result) != 0) // if fetch have results
		{
			echo '<div style=" width : 400px; height : 600px; overflow : auto; ">';

			for($i=1; $i<=mysqli_num_rows($result); $i++) // for every fetched comment entry
			{
				$row = mysqli_fetch_array($result);
										// photo owner view
				if( (isset($_SESSION['username'])) && ($_SESSION['username'] == $image_infos['user']) )
				{
					echo('</br>
					<p width="100px" ><font size="3">'.$row['comment_text'].'</font></br>
					   <font size="2" color="blue"><b>'.$row['username'].'</b></font>
		 		       <a href="" align="right" onclick=" deleteComment('.$id.', '.$row['comment_id'].'); return false;">delete comment</a></p>
						</br>');
	
				}							// comment owner view
				else if( (isset($_SESSION['username'])) && ($_SESSION['username'] == $row['username']) )
				{
					echo('</br>
					<p width="100px" ><font size="3">'.$row['comment_text'].'</font></br>
					   <font size="2" color="blue"><b>'.$row['username'].'</b></font>
		 		       <a href="" align="right" onclick=" deleteComment('.$id.', '.$row['comment_id'].'); return false;">delete comment</a></p>
						</br>');
				}
				else
				{							// simple visitor view
					echo('</br>
						<font size="3">'.$row['comment_text'].'</font></br>
					   <font size="2" color="blue"><b>'.$row['username'].'</b></font>
						</br>');
				}

			}	

			echo '</div>';
		}
	}

	mysqli_close($dbc); // close connection to db	
}
?>
