<?php

echo '<a href="includes/scale_large_thumbnail.php?id=' . $id . '"> <img src="includes/scale_big_thumbnail.php?id=' . $id . '" width="100%"/> </a>'; 

if( isset($_SESSION['session_username']) && ($_SESSION['session_username'] == $image_infos['user']) )
{
	echo('  <form action="view.php" method="get">
			<input type="submit" name="delete" value="Delete">
			<input type="hidden" name="submitted_delete" value="TRUE">
			<input type="hidden" name="submitted_id" value="'.$id.'">
		</form>
		<form action="view.php" method="get">
			<input type="submit" name="edit" value="Edit">
			<input type="hidden" name="submitted_edit" value="TRUE">
			<input type="hidden" name="image_id" value="'.$id.'">
			<input type="hidden" name="submitted_user" value="'.$image_infos['user'].'">
		</form>');

}
else if(isset($_SESSION['session_username']))
{
	echo('<div id="rank_button_place"> <button align="" onclick="setLike('.$id.')"> Like </button>
					<button onclick="setDislike('.$id.')"> Dislike </button>  </br></br></div>');
}


$sum = $image_infos['likes'] + $image_infos['dislikes'];

if($sum!=0)
{
	$dislikes = 100/$sum * $image_infos['dislikes'];
	$likes = 100/$sum * $image_infos['likes'];
}
else
{
	$likes = 0;
	$dislikes = 0;
}

echo '	<div id="ranker">
		<div id="rank_text">'.$image_infos['likes'].' likes, '.$image_infos['dislikes'].' dislikes </div>
		<div id="rank_container">         
	  		<div id="likes" style="width: '.$likes.'%" ></div>
    			<div id="dislikes" style="width: '.$dislikes.'%"></div>
		</div></br>
	</div>';

?>
