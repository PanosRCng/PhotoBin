<?php

function delete_photo($picture_id)
{						// check if user logged in
	if( !isset($_SESSION['session_username']) )
	{
		return 0;
	}
	else			// connect to db
	{       $dbc = db_connect();
						// query to db to load photo infos
		$query='select username, name, public from picture where picture_id="'. $picture_id .'"';
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );

		if(mysqli_num_rows($result) == 1)  // if fetch is 1 result
		{
			$row = mysqli_fetch_array($result);
			$photo_infos = array( 'user' => $row['username'], 'name' => $row['name'], 'public' => $row['public'] );
									// if user photo owner
			if($photo_infos['user'] == $_SESSION['username'] )
			{
											//delete photo entry from db
				$query = 'delete from picture where picture_id="'.$picture_id.'"';
				$result = mysqli_query($dbc, $query) or trigger_error("Query: $query \n </br> 
											MYSQL Error: " . mysqli_error($dbc));		
				if(mysqli_affected_rows($dbc) == 1)  // if delete photo entry ok
				{	
										// delete all photo comments
					$query = 'delete from comment where picture_id="'.$picture_id.'"';
					$result = mysqli_query($dbc, $query) or trigger_error("Query: $query \n </br> 
											MYSQL Error: " . mysqli_error($dbc));		
					if($result)  // if delete comments entries ok
					{
					//	return 1;
					}
					else
					{
					//	return 0;
					}
								// check if photo is private or public and built path
					if($photo_infos['public'] == 1)
					{
						$photo_path = USER_SPACE_PUBLIC.$_SESSION['username'].'/';
					}
					else if($photo_infos['public'] == 0)
					{
						$photo_path = USER_SPACE_PRIVATE.$_SESSION['username'].'/';
					}

					if(file_exists($photo_path.$photo_infos['name'])) // just check if photo exists, you never know
					{
								// delete image from folder (user login username to built path)
						if( unlink($photo_path.$photo_infos['name']) )
						{
							unlink($photo_path.'thumbs_medium/'.$photo_infos['name']);
							unlink($photo_path.'thumbs_small/'.$photo_infos['name']);

							mysqli_close($dbc);

							return 1;				
						}
					}
				}
			}
		}

		mysqli_close($dbc);
	}

	return 0;
}

?>
