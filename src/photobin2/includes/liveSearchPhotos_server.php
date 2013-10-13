<?php

$search_text = $_GET['search_text'];
$search_mode = $_GET['search_mode'];

require_once('/var/www/config.inc.php');
require_once(MYSQL);

		// connect to database
$dbc = db_connect();

$search_text = mysqli_real_escape_string($dbc, $search_text);

if($search_mode == 2)
{								
	$query1='select  picture_id, username, name from picture where '; 

	$tok = strtok($search_text, " \n\t");
	$temp=' ( picture_title LIKE "%'.$tok.'%" or description LIKE "%'.$tok.'%" or tags LIKE "%'.$tok.'%" ) ';
	$temp2 = $temp;

	while ($tok !== false)
	{
 		$temp=' ( picture_title LIKE "%'.$tok.'%" or description LIKE "%'.$tok.'%" or tags LIKE "%'.$tok.'%" ) ';

		$temp2 = $temp2.' and '.$temp;

	    	$tok = strtok(" \n\t");	
	}

	$query2 = $temp2 .' and public="1" ';

	$query = $query1.$query2;

}
else if($search_mode == 3)
{								
	$query='select  picture_id, username, name from picture where public="1" order by likes DESC'; 
}
else if($search_mode == 0)
{								// query to db to fetch matching photos by tag
	$query='select  picture_id, username, name from picture where  tags LIKE "%'.$search_text.'%" and public="1"';
}

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
