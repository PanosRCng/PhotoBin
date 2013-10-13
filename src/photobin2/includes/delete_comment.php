<?php

session_start();

require_once('/var/www/config.inc.php');
require_once(MYSQL); 

$comment_id=$_GET["comment_id"];

if ( strlen($comment_id) > 0 )
{
	if( delete_comment($comment_id) )
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
function delete_comment($comment_id)
{						// if user logged in 
	if( isset($_SESSION['session_username']) )
	{			// connect to db
		$dbc = db_connect();
							// query to db to load comment infos
		$query='select username, picture_id from comment where comment_id="'. $comment_id .'"';
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ".mysqli_error($dbc) );

		if(mysqli_num_rows($result) == 1)  // if fetch is 1 result
		{
			$row = mysqli_fetch_array($result);	// load comment infos
			$comment_infos = array( 'picture_id' => $row['picture_id'], 'comment_owner' => $row['username'] );
								// if user is the comment owner allow to delete it
			if($_SESSION['username'] == $comment_infos['comment_owner'])
			{						// query to db to delete comment
				$query = 'delete from comment where comment_id="'.$comment_id.'" ';
				$result = mysqli_query($dbc, $query) or trigger_error("Query: $query \n </br> 
										MYSQL Error: " . mysqli_error($dbc));		
				if(mysqli_affected_rows($dbc) == 1) // if comment deleted
				{				
					return 1;
				}
				else
				{
					return 0;
				}
			}
			else
			{						// query to db to load photo infos
				$query='select username from picture where picture_id="'. $comment_infos['picture_id'] .'"';
				$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> 
											MySQL Error: ".mysqli_error($dbc) );

				if(mysqli_num_rows($result) == 1)  // if fetch is 1 result
				{
					$row = mysqli_fetch_array($result);	// load photo infos
					$photo_infos = array( 'photo_owner' => $row['username'] );
									// if user is the photo owner allow to delete it
					if($_SESSION['username'] == $photo_infos['photo_owner'])
					{						// query to db to delete comment
						$query = 'delete from comment where comment_id="'.$comment_id.'" ';
						$result = mysqli_query($dbc, $query) or trigger_error("Query: $query \n </br> 
										MYSQL Error: " . mysqli_error($dbc));		
						if(mysqli_affected_rows($dbc) == 1) // if comment deleted
						{				
							return 1;
						}
						else
						{
							return 0;
						}
					}
					else // user not the photo owner
					{
						return 0;
					}	
				}
				else // fetch photo not ok
				{
					return 0;
				}
			}
		}
		else // fetch comment not ok
		{
			return 0;
		}
	
		mysqli_close($dbc);
	}
	else // user not logged in
	{
		return 0;
	}
}

?>
