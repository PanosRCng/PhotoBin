<h3> Add Infos </h3>

<fieldset>
<form action="upload.php" method="post">

<div id="wrapper_down">
	<div id="infos">

		<p><b> Title: </b>
			<input id="title" type="text" name="title" size="50" max_length="30" onClick="clearField('title')"
	        	           value="<?php if(isset($trimmed['title'])) echo $trimmed['title'] ?>"/>
		</p>
		<p><b> Description: </b>
			<input id="description" type="text" name="description" size="50" max_length="30"
				 	onClick="clearField('description')"
				value="<?php if(isset($trimmed['description'])) echo $trimmed['description'] ?>"/>
		</p>

		<p><b> Tags: </b>
			<input id="tags" type="text" name="tags" size="50" max_length="30"
				value="<?php if(isset($trimmed['tags'])) echo $trimmed['tags'] ?>"/>
		</p>

		<input id="search_field" type="text" size="30" autocomplete="off" />
		<div id="livesearch"></div>
			<button type="button" onClick="addTag()">Add tag</button> 

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

		<p><b> Longtitude: </b>
			<input id="Long" type="text" name="longtitude" size="50" max_length="30"
				value="<?php if(isset($trimmed['longtitude'])) echo $trimmed['longtitude'] ?>"/>
		</p>

		<p><b> Latitude: </b>
			<input id="Lat" type="text" name="latitude" size="50" max_length="30"
				value="<?php if(isset($trimmed['latitude'])) echo $trimmed['latitude'] ?>"/>
		</p>

		<p><b> Set public: </b>
			<input type="checkbox" name="public" value="TRUE"/>
		</p>

	</div>

	<div id="map" style="width: 500px; height: 400px;"></div>
</div>

<div align="left">
	<input type="submit" name="submit_infos" value="Save" onClick="return check_imageinfo_Fields(this);" />
</div>

	<input type="hidden" name="submitted_infos" value="TRUE" />
</form>
</fieldset>
