<?php

session_start();

require_once('/var/www/config.inc.php');
require_once(MYSQL); 

$id=$_GET["q"];

if (strlen($id) > 0)
{
	set_dislike($id);
}

function set_dislike($picture_id)
{					// if user logged in
	if(isset($_SESSION['session_username'])) 
	{
		if( isset($_SESSION['id_rated']) )
		{ 						// if have not rate this photo in this session 
			if($_SESSION['id_rated'] == $picture_id )
			{
				return 0;
			}
		}
			// connect to db
		$dbc = db_connect();
						// query to db to load photo infos
		$query='select username, likes, dislikes from picture where picture_id="'. $picture_id .'"';
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ".mysqli_error($dbc) );

		if(mysqli_num_rows($result) == 1)  // if fetch is 1 result
		{
			$row = mysqli_fetch_array($result);
			$photo_infos = array( 'user' => $row['username'], 'likes' => $row['likes'], 'dislikes' => $row['dislikes'] );
									// if user not the owner
			if($_SESSION['username'] != $photo_infos['user'])
			{					// query db to add dislike
				$query='update picture set dislikes = dislikes + 1 where picture_id="'. $picture_id .'"';
				$result=mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> 
									MySQL Error: ". mysqli_error($dbc) );
				if(mysqli_affected_rows($dbc) == 1) // if dislike added
				{
					$_SESSION['id_rated'] = $picture_id; // do not like again in this session

					$likes = $photo_infos['likes'];
					$dislikes = $photo_infos['dislikes']+ 1;

					$sum = $likes + $dislikes;

					if($sum!=0)
					{
						$dislikes_per = 100/$sum * $dislikes;
						$likes_per = 100/$sum * $likes;
					}
					else
					{
						$likes_per = 0;
						$dislikes_per = 0;
					}

					echo '<div id="rank_text">'.$likes.' likes, '.$dislikes.' dislikes </div>
					      <div id="rank_container">         
  							<div id="likes" style="width: '.$likes_per.'%" ></div>
    							<div id="dislikes" style="width: '.$dislikes_per.'%"></div>
					      </div></br>';
	
				}
				else
				{
					echo '';
				}
			}
		}

		mysqli_close($dbc);
	}

	return 0;
}

?>
