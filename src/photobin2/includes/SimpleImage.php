<?php
class SimpleImage
{
	var $image;
	var $image_type;
 
	function load_up($filename)
	{
		$image_info = getimagesize($filename);
	        $this->image_type = $image_info[2];
      		if( $this->image_type == IMAGETYPE_JPEG )
		{ 
         		$this->image = imagecreatefromjpeg($filename);
      		}
		elseif( $this->image_type == IMAGETYPE_PNG )
		{ 
         		$this->image = imagecreatefrompng($filename);
      		}
	}

	function load_from_tmp()
	{
		session_start();
		require_once('/var/www/config.inc.php');

		$filename = TMP_SPACE.'/'.$_SESSION['tmp_folder'].'/'.$_SESSION['photo_name'];

		$image_info = getimagesize($filename);
	        $this->image_type = $image_info[2];
      		if( $this->image_type == IMAGETYPE_JPEG )
		{ 
         		$this->image = imagecreatefromjpeg($filename);
      		}
		elseif( $this->image_type == IMAGETYPE_PNG )
		{ 
         		$this->image = imagecreatefrompng($filename);
      		}
	}

	function load($id)    // fixed with prepare statements because of SQlinjection
	{
		session_start();
		require_once('/var/www/config.inc.php');
		require_once(MYSQL);

		$dbc = db_connect(); // connect to db
								// query to db to fetch photo infos
		$query="select public, name, username from picture where picture_id=?";

						// prepare query
		$stmt = mysqli_prepare($dbc, $query);

   					// Bind Parameters [s for string]
		mysqli_stmt_bind_param($stmt, "i", $id);

		  			// execute statement
		mysqli_stmt_execute($stmt);

				    				// bind result variables 
		mysqli_stmt_bind_result($stmt, $public, $name, $username);

	 			// fetch values 
    		if(mysqli_stmt_fetch($stmt))
		{
			if( $name && $username )
			{
				if( $public == 0 ) // if photo is private, return only if the owner ask for it
				{
					if( $username != $_SESSION['username'] )
					{
						die();
					}
								// path to private space
					$filename = USER_SPACE_PRIVATE.$username.'/'.$name;
				}
				else
				{								// path to public space
					$filename = USER_SPACE_PUBLIC.$username.'/'.$name;
				}
			
				$image_info = getimagesize($filename);
	        		$this->image_type = $image_info[2];
      				if( $this->image_type == IMAGETYPE_JPEG )
				{ 
         				$this->image = imagecreatefromjpeg($filename);
      				}
				elseif( $this->image_type == IMAGETYPE_PNG )
				{ 
         				$this->image = imagecreatefrompng($filename);
      				}
			}
		}
				// close statement
		mysqli_stmt_close($stmt);
				// close db connection
		mysqli_close($dbc);
   	}


/*
	--> valnurable to Sqlinjections

	function load($id)      
	{
		session_start();
		require_once('/var/www/config.inc.php');
		require_once(MYSQL);

		$dbc = db_connect(); // connect to db
								// query to db to fetch photo infos
		$query='select public, name, username from picture where picture_id="'. $id .'"';
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );
						// if 1 result
		if(mysqli_num_rows($result) == 1)
		{				
			$row = mysqli_fetch_array($result);

			if( $row['public'] == 0 ) // if photo is private, return only if the owner ask for it
			{
				if( $row['username'] != $_SESSION['username'] )
				{
					die();
				}
								// path to private space
				$filename = USER_SPACE_PRIVATE.$row['username'].'/'.$row['name'];
			}
			else
			{								// path to public space
				$filename = USER_SPACE_PUBLIC.$row['username'].'/'.$row['name'];
			}
			
			$image_info = getimagesize($filename);
	        	$this->image_type = $image_info[2];
      			if( $this->image_type == IMAGETYPE_JPEG )
			{ 
         			$this->image = imagecreatefromjpeg($filename);
      			}
			elseif( $this->image_type == IMAGETYPE_PNG )
			{ 
         			$this->image = imagecreatefrompng($filename);
      			}
		}

		mysqli_close($dbc);
   	}
*/

   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG )
      {
	        imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }      
 
}
?>
