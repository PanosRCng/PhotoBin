
function fetchComments(picture_id)
{
	var xmlhttp;
			// if no id 
	if ( picture_id.length==0 )
  	{
		return;
	}

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{			
			document.getElementById("comments_view").innerHTML = xmlhttp.responseText;
		}
	}

	xmlhttp.open("GET","includes/view_comments.php?picture_id="+picture_id,true);
	xmlhttp.send();
	
}
