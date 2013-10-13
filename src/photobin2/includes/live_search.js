function showResult(str)
{			// remove spaces from start and end
	str = trim(str);

			// if search text empty
	if (str.length==0)
	{
		document.getElementById("livesearch").innerHTML="";
		return;
	}
				// split by spaces if more than one args exists
	strArray = str.split(" ");
					// search always by the last arg
	text = strArray[strArray.length-1];

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
			if(xmlhttp.responseText != -1)
			{									// fill with search results
				document.getElementById("livesearch").innerHTML= xmlhttp.responseText;
	     			document.getElementById('livesearch').style.display='block';  // fix display 
				document.getElementById("livesearch").style.border="1px solid #A5ACB2"; // and border
			}		
		}
	}

	xmlhttp.open("GET","includes/liveSearch_server.php?q="+text,true);
	xmlhttp.send();
}

function addTag()
{
	if(document.getElementById("search_field").value != "")
	{
		document.getElementById("tags").value += document.getElementById("search_field").value + "," ;
	
		document.getElementById("search_field").value = "";
	}
}

function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
}

function trim(str)
{
        return str.replace(/^\s+|\s+$/g,"");
}

