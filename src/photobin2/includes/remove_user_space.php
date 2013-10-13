<?php

function remove_user_space($user)
{
	$user_dir = USER_SPACE_PUBLIC.$user;

	if(file_exists($user_dir))
	{
		if( !remove_dir($user_dir) )
		{
			return 0;
		}

		$user_dir = USER_SPACE_PRIVATE.$user;

		if(file_exists($user_dir))
		{
			if( !remove_dir($user_dir) )
			{
				return 0;
			}
		}
	}

	return 1;
}

?>
