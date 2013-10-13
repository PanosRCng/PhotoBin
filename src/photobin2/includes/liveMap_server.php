<?php

$swLat=$_GET['swLat'];
$swLng=$_GET['swLng'];
$neLat=$_GET['neLat'];
$neLng=$_GET['neLng'];

require_once('/var/www/config.inc.php');
require_once(MYSQL);

		// connect to database
$dbc = db_connect();
							// query to db to fetch all public photos in map view area
$query='select * from picture where latitude>'.$swLat. ' and longtitude>'.$swLng. ' and latitude<'.$neLat. ' and longtitude<'.$neLng.'  and public="1"' ;

$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );
			// if have resutls
if(mysqli_num_rows($result) != 0)
{						// built xml with photos and echo it to client
	echo "<photoset>\n";

	for($i=1; $i<=mysqli_num_rows($result); $i++)
	{
		$row = mysqli_fetch_array($result);

		echo "<photo>\n";
			echo "<id>".$row['picture_id']."</id>\n";
			echo "<name>".$row['name']."</name>\n";
			echo "<username>".$row['username']."</username>\n";
			echo "<title>".$row['picture_title']."</title>\n";
			echo "<longtitude>".$row['longtitude']."</longtitude>\n";
			echo "<latitude>".$row['latitude']."</latitude>\n";
		echo "</photo>\n";
		echo "\n";					      
	}

	echo "</photoset>\n";
}
else
{
	echo '<p class="error"> No photo entries found </p> ';
}

mysqli_close($dbc);

?> 
