<?php

if(isset($trimmed['title']))
{
	$title_text = $trimmed['title']; 
}
else
{
	$title_text = $image_infos['title'];
}

if(isset($trimmed['description']))
{
	$description_text = $trimmed['description'];
}
else
{
	$description_text = $image_infos['description'];
}

if(isset($trimmed['tags']))
{
	$tags_text = $trimmed['tags']; 
}
else
{
	$tags_text = $image_infos['tags'];
}

if(isset($trimmed['longtitude']))
{
	$longtitude_text = $trimmed['longtitude']; 
}
else
{
	$longtitude_text = $image_infos['longtitude'];
}

if(isset($trimmed['latitude']))
{
	$latitude_text = $trimmed['latitude']; 
}
else
{
	$latitude_text = $image_infos['latitude'];
}

if(!isset($public))
{
	$public = $image_infos['public'];
}

?>


<h3> Edit Infos </h3>

<fieldset>

<div id="wrapper_down">
  <div id="infos">

<form name=infos action="edit_photo.php" method="post">

<p> <b> Title: </b>
<input id="title" type="text" name="title" size="50" max_length="30" onClick="clearField('title')"
	value="<?php echo $title_text ?>"/>
</p>
<p> <b> Description: </b>
<input id="description" type="text" name="description" size="50" max_length="30" onClick="clearField('description')"
	value="<?php echo $description_text ?>"/>
</p>

<p> <b> Tags: </b>

<input id="tags" type="text" name="tags" size="50" max_length="30"
	value="<?php echo $tags_text ?>"/>
</p>

<input id="search_field" type="text" size="30" autocomplete="off" 
		onblur="(function(){ document.getElementById('livesearch').style.display='none'; })();" />
		<button type="button" onClick="addTag()">Add tag</button>
<div id="livesearch"></div>
 

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
		document.getElementById('search_field').value =  (divs[selectedDiv].textContent);

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

<p> <b> Longtitude: </b>
<input id="Long" type="text" name="longtitude" size="50" max_length="30"
	value="<?php echo $longtitude_text ?>"/>
</p>

<p> <b> Latitude: </b>
<input id="Lat" type="text" name="latitude" size="50" max_length="30"
	value="<?php echo $latitude_text ?>"/>
</p>

<p> <b> Set public: </b>
<input type="checkbox" name="public_checkbox" onClick="click_checkbox()" />
</p>
<script>
function click_checkbox()
{
	document.infos.public_checkbox.value = document.infos.public_checkbox.checked;
}
</script>
<?php
echo('<script>
if('.$public.')
{
	document.infos.public_checkbox.checked=true;
}
</script>');
?>
</div>

<div id="map" style="width: 500px; height: 400px;"></div>

</div>

<div align="left" style="margin:10px;">
<input type="submit" name="submit_infos" value="Save Changes" onClick="return check_imageinfo_Fields(this);"/>
<input type="hidden" name="submitted_infos" value="TRUE"/>
<input type="hidden" name="image_id" value="<?php echo $image_id ?>"/>
</div>
</form>

</fieldset>
