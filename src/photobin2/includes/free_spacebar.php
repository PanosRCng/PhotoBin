<?php
			// make spacebar
function free_spacebar($user)
{
	$path = USER_SPACE_PUBLIC.$user;
	$io = popen('/usr/bin/du -sb '.$path, 'r');
	$size_public = intval(fgets($io,80));
	pclose($io);

	$path = USER_SPACE_PRIVATE.$user;
	$io = popen('/usr/bin/du -sb '.$path, 'r');
	$size_private = intval(fgets($io,80));
	pclose($io);
	
	$size = $size_public + $size_private;
	$size = format_bytes($size);

	$max = format_bytes(MAX_FREE_SPACE);

	$bar = (200/$max) * $size;

	print('<p> Used space: '. $size .' MB / '.$max.' MB </p>');

	print('<p class="progressBar">
		<span><em style="left:'. $bar .'px">25%</em></span>
		</p>');
}

function space_left($user)
{
	$path = USER_SPACE_PUBLIC.$user;
	$io = popen('/usr/bin/du -sb '.$path, 'r');
	$size_public = intval(fgets($io,80));
	pclose($io);

	$path = USER_SPACE_PRIVATE.$user;
	$io = popen('/usr/bin/du -sb '.$path, 'r');
	$size_private = intval(fgets($io,80));
	pclose($io);
	
	$size = $size_public + $size_private;
	$size = format_bytes($size);

	$max = MAX_FREE_SPACE;

	$free = $max - $size;

	return $free;
}
			// Bytes2MBytes
function format_bytes($a_bytes)
{
        return round($a_bytes / 1048576, 2);
}


?>
