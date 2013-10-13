<?php 

include('includes/remove_dir.php');
				
function make_user_space($user) // make user space (public, private, thumbs folders)
{
	$user_dir = USER_SPACE_PUBLIC.$user;

	if(file_exists($user_dir)) // if for some reason trash user folder already exists (db already checked and there is not user
	{													// with this username )
		if( !remove_dir($user_dir) ) // remove it and everything in it
		{
			return 0; // if fail can't register user
		}
	}
					// same for private space
	$user_dir = USER_SPACE_PRIVATE.$user;

	if(file_exists($user_dir))
	{
		if( !remove_dir($user_dir) )
		{
			return 0;
		}
	}

	$user_dir = USER_SPACE_PUBLIC.$user;  // if all ok, make user space

	if( mkdir($user_dir , 0777, true) )
	{
		if( create_thumbs($user_dir) )
		{
			$user_dir = USER_SPACE_PRIVATE.$user;		

			if( mkdir($user_dir , 0777, true) )
			{
				if( create_thumbs($user_dir) )
				{
					return 1;
				}
				else
				{
					return 0;
				}
			}
			else
			{
				return 0;
			}
		}
	}
	else
	{
		return 0;
	} 
}

function create_thumbs($user_dir)
{
	if(mkdir($user_dir.'/thumbs_small' , 0777, true) )
	{
		if(mkdir($user_dir.'/thumbs_medium' , 0777, true) )
		{
			return 1;
		}
	}
	else
	{
		return 0;
	} 
}

?>
