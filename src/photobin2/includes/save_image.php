<?php

function save_image($title, $description, $tags, $longtitude, $latitude, $public, $name)
{				// if user logged in
	if(isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];	
		$likes = 0;
		$dislikes = 0;

		$ok=0;

		$dbc = db_connect();
								// query to db to insert photo entry
		$query = "insert into picture (username, picture_title, name, description,
	 			longtitude, latitude, likes, dislikes, tags, public) values('$username',
		 			'$title', '$name','$description', '$longtitude', '$latitude',
		 			'$likes', '$dislikes', '$tags', '$public')";
		$result=mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );
						// if photo entry added
		if(mysqli_affected_rows($dbc) == 1)
		{
			$image_path = TMP_SPACE.$_SESSION['tmp_folder']; // load photo to memory and save it to user space
			$image = new SimpleImage();
			$image->load_up($image_path.'/'.$name);

			if($public == 1) // if photo public save to public userspace else to private userspace
			{
				$dest_path = USER_SPACE_PUBLIC.$_SESSION['username'].'/'; 
			}
			else
			{
				$dest_path = USER_SPACE_PRIVATE.$_SESSION['username'].'/'; 
			}
			$image->save($dest_path.$name); // save photo

      			$image->resizeToWidth(100); // make thumbnail medium
			$height = $image->getHeight();
			if( $height > 100 )
			{
				$image->resizeToHeight(100);
			}
  					
      			$image->save($dest_path.'thumbs_medium/'.$name); // save medium thumbnail


      			$image->resizeToWidth(20); // make thumbnail medium
			$height = $image->getHeight();
			if( $height > 20 )
			{
				$image->resizeToHeight(20);
			}
								// save small thumbnail
      			$image->save($dest_path.'thumbs_small/'.$name);			
										
			$_SESSION['photo_up'] = 0; 
			$ok=1;
		}
		else
		{
			return 0;
		}

		if($ok && ($tags != "") ) // if photo added ok and there are tags, add tags to db (tag table)
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

		mysqli_close($dbc);

		return $ok;
	}
	else
	{
		return 0;
	}
}

?>
