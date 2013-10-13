<?php session_start() ?>

<?php require_once('/var/www/config.inc.php'); ?>
<?php require_once(MYSQL); ?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Search photos</title>
<link rel="stylesheet" type="text/css" href="style/search.css" />
<script type="text/javascript" src="includes/search_photos.js"></script>
<script type="text/javascript" src="includes/paging.js"> </script>
<script type="text/javascript" src="includes/live_search.js"></script>
</head>

<?php include('includes/header.html'); ?>

<?php 

echo '<script> var search_text="" </script>';
if( ( isset($_GET['search']) ) && ( isset($_GET['mode']) ) )
{
	echo '<script> var search_text="'.$_GET['search'].'" </script>';
	echo '<body onload="getSearch_text(search_text, '.$_GET['mode'].');">' ;
}
?>

<?php 

if(isset($_SESSION['session_username']))
{
	echo('<div id="options">
		<p> Hi, <b><font size="3">'. $_SESSION['username'].'</font></b></p>
		<a href="profile.php">View Profile</a>
		</br>
		<a href="logout.php">Logout</a>
	      </div>');
}

?>

<div id="wrapper_up">
	<p><b>Search photos: </b></p>

	<div id="searchbox_div">
		<input id="search_field" onblur="(function(){ document.getElementById('livesearch').style.display='none'; })();" 						type="text" name="search" size="20"
				 autocomplete="off"/><button id="search_button" onClick="get_index_search()"> Search </button>

	<div id="livesearch" style="width: 220px;"></div>
</div>


<div id="wrapper_down">
	<div id="pics">
		<table id="search_results" border="0" cellspacing="5" cellpadding="5">
		</table>
	<div id="pageNavPosition"></div>
<div>

<script type="text/javascript">
var pager;
</script>

<script type="text/javascript">
document.onkeypress = stopRKey;

var divs = document.getElementById('livesearch').getElementsByTagName('div');
var selectedDiv = -1;
var i;

document.getElementById('search_field').onkeyup = function(e){

	var x = 0;
        if(e.keyCode == 38)
             x = -1;
        else if(e.keyCode == 40)
             x = 1;
	else if(e.keyCode == 13)
	{
		old = document.getElementById('search_field').value;

		strArray = old.split(" ");

		new_text ="";
			
		for(i=0; i<strArray.length-1; i++)
		{
			new_text += strArray[i] + ' ';
		}
		
		document.getElementById('livesearch').style.display='none';	
		document.getElementById('search_field').value = new_text + (divs[selectedDiv].textContent);

		showResult("");
	}
        else
	{
             showResult(document.getElementById('search_field').value);

	     selectedDiv = -1;		

	     return;	
	}	

	if(selectedDiv != -1)
	{
        	divs[selectedDiv].style.backgroundColor = '';
	} 
	
        selectedDiv = ((selectedDiv+x)%divs.length);
        selectedDiv = selectedDiv < 0 ?
	divs.length+selectedDiv : selectedDiv;

	divs[selectedDiv].style.backgroundColor = '#E0E0E0';
};
</script>

<?php include('includes/footer.html'); ?> 

</body>
</html>
