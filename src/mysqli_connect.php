<?php

DEFINE ('DB_USER', 'xxxxxxxxxx');
DEFINE ('DB_PASSWORD', 'xxxxxxxx');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'photobin_db');

$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if( !mysqli_set_charset ($dbc , "utf8") )
{
	trigger_error('Could not set character set UTF8: ' . mysqli_connect_error());
}

if(!$dbc)
{
	trigger_error('Could not connect to MySQL: ' . mysqli_connect_error());
}

function db_connect()
{
	$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if(!$dbc)
	{
		trigger_error('Could not connect to MySQL: ' . mysqli_connect_error());
	}
	else
	{
		if( mysqli_set_charset ($dbc , "utf8") )
		{
			return $dbc;
		}
	}
}

?>
