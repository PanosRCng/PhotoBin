
<?php require_once('/var/www/config.inc.php'); ?>
<?php require_once(MYSQL); ?>

<?php

$a=array();

//get the q parameter from URL
$q=$_GET["q"];

//lookup all hints from array if length of q>0
if (strlen($q) > 0)
{
	$dbc = db_connect();

	$query='select tag_title from tag where tag_title LIKE "%'.$q.'%"';
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br/> MySQL Error: ". mysqli_error($dbc) );

	if(mysqli_num_rows($result) != 0)
	{
		for($i=1; $i<=mysqli_num_rows($result); $i++)
		{
			$row = mysqli_fetch_array($result);

			$a[$i] = $row['tag_title']; 
		}
	}

	$hint="";

	for($i=1; $i<=count($a); $i++)
	{
		if (strtolower($q)==strtolower(substr($a[$i],0,strlen($q))))
		{
			if ($hint=="")
		        {
			        $hint="<div>".$a[$i]."</div>";
		        }
			else
		        {
			        $hint=$hint."<div>".$a[$i]."</div>";
		        }
		}
	}

	mysqli_close($dbc);
}

// Set output to "no suggestion" if no hint were found
// or to the correct values
if ($hint=="")
  {
  $response=-1;
  }
else
  {
  $response=$hint;
  }

//output the response
echo $response;
?> 
