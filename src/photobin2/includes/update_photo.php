<?php

function update_photo($photo_id, $title, $description, $tags, $longtitude, $latitude, $public_new)
{
	$dbc = db_connect();
								// query to db to fetch photo infos	
	$query='select * from picture where picture_id="'. $photo_id .'"';
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
							// if photo privacy changed
		$ok = 1;

		if($image_infos['public'] != $public_new)
		{			
			if($image_infos['public'] == 0)
			{				
				if( change_photo_privacy(1, $image_infos['name']) )
				{
					$ok = 1;
				}
				else
				{
					$ok = 0;
				}
			}
			else if($image_infos['public'] == 1)
			{
				if( change_photo_privacy(0, $image_infos['name']) )
				{
					$ok = 1;
				}
				else
				{
					$ok = 0;
				}
			}
		}

		if($ok)
		{								// query to db to update photo infos
			$query='update picture set picture_title="'.$title.'", description="'.$description.'", tags="'.$tags.'",
			 longtitude="'.$longtitude.'", latitude="'.$latitude.'", public="'.$public_new.'" 
										where picture_id="'. $_POST['image_id'] .'"';
			$result=mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );
							// if 1 result, save only new tags
			if(mysqli_affected_rows($dbc) == 1)
			{
				$token = strtok($tags, ","); // tokenize tags 

				while ($token != false)
				{					// query to db to find if tag already exists
					$query = "select * from tag where tag_title = '$token'";
					$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> 
											MySQL Error: ". mysqli_error($dbc) );
								// if tag already exists update its counter
					if (mysqli_num_rows($result) == 1)
					{					// query to db to update tag counter
						$query = "update tag set counter = counter+1 where tag_title = '$token'";
						$result=mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> 
												MySQL Error: ". mysqli_error($dbc) );
									// if tag updated
						if(mysqli_affected_rows($dbc) == 1)
						{
						//		echo '<p class="error"> tag updated </p>'; 
						}
						else
						{
						//	echo '<p class="error"> Can not save tag </p> ';
						} 
					} 
					else
					{							// if tag does not exist add it
						$query = "insert into tag (tag_title, counter) VALUES ('$token', '1')";
						$result=mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> 
												MySQL Error: ". mysqli_error($dbc) );
										// if tag added
						if(mysqli_affected_rows($dbc) == 1)
						{
						//	echo '<p class="error"> tag added </p>';
						}
						else
						{
						//	echo '<p class="error"> Can not save tag </p> ';
						}    		
					}

					$token = strtok(",");
				}
			}
		}

	mysqli_close($dbc);
	return 1;
	}

	return 0;
}

function change_photo_privacy($mode, $name)
{
	if($mode == 1)
	{
		$image_src_path = USER_SPACE_PRIVATE.$_SESSION['username'].'/';
		$image_dest_path = USER_SPACE_PUBLIC.$_SESSION['username'].'/';	

		if( rename($image_src_path.$name, $image_dest_path.$name) )
		{
			rename($image_src_path.'thumbs_medium/'.$name, $image_dest_path.'thumbs_medium/'.$name);
			rename($image_src_path.'thumbs_small/'.$name, $image_dest_path.'thumbs_small/'.$name);
		
			return 1;
		}
	}
	else if($mode == 0)
	{
		$image_dest_path = USER_SPACE_PRIVATE.$_SESSION['username'].'/';
		$image_src_path = USER_SPACE_PUBLIC.$_SESSION['username'].'/';	

		if( rename($image_src_path.$name, $image_dest_path.$name) )
		{
			rename($image_src_path.'thumbs_medium/'.$name, $image_dest_path.'thumbs_medium/'.$name);
			rename($image_src_path.'thumbs_small/'.$name, $image_dest_path.'thumbs_small/'.$name);
		
			return 1;
		}
	}

	return 0;
}

?>
