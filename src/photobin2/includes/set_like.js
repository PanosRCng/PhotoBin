
function setLike(id)
{
	var xmlhttp;

	if (id.length==0)
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
			if(xmlhttp.responseText.length != 0)			
			{					
				document.getElementById('ranker').innerHTML = xmlhttp.responseText;
			}
		}
	}

	xmlhttp.open("GET","includes/set_like.php?q="+id,true);
	xmlhttp.send();
}

