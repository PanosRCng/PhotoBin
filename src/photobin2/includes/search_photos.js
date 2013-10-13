

function getSearch_text(text, search_mode)
{		
	if( (search_mode == 0) || (search_mode == 2))
	{
		text = trim(text); // remove spaces from start and end 
		search_text = text;
	}

	if (search_text.length==0)
  	{
		return;
	}
	else
	{
		searchPhotos(search_text, search_mode);
	}

}

function trim(str)
{
        return str.replace(/^\s+|\s+$/g,"");
}

function searchPhotos(search_text, search_mode)
{
	var xmlhttp;

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
		{				// save response to xml on memory
			test = xmlhttp.responseText;

			if (window.DOMParser)
	  		{			// make a xml parser 
	  			parser=new DOMParser();
	  			xmlDoc=parser.parseFromString(test,"text/xml");
	  		}
			else // Internet Explorer
	  		{
	  			xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
	  			xmlDoc.async=false;
	  			xmlDoc.loadXML(test);
	  		}

			list = xmlDoc.getElementsByTagName('id');
			count = list.length;

			document.getElementById("search_results").innerHTML = '<table border="0" cellspacing="5" cellpadding="5"><tr>';
	
			var column = 0;
			var counter = 1;
			var x=document.getElementById('search_results').insertRow(-1);

				// for every image in xml load infos
			for(var i=0; i<count; i++)
			{
				picture_id = xmlDoc.getElementsByTagName("id")[i].childNodes[0].nodeValue;
				name = xmlDoc.getElementsByTagName("name")[i].childNodes[0].nodeValue;
				username = xmlDoc.getElementsByTagName("username")[i].childNodes[0].nodeValue;	

				var y=x.insertCell(column++);
				var image = '<a href="view.php?image_id='+picture_id+'"><img src="includes/scale_small_thumbnail.php?id='
												+picture_id+'" width="100%" border="0"></a>'
				y.innerHTML=image;	

				if( counter%5==0 )
				{
					x=document.getElementById('search_results').insertRow(-1);
					column=0;
				}		
				counter++;
			}
			
			if(search_mode == '3')
			{
				pager = new Pager('search_results', 5); 
			}
			else
			{
				pager = new Pager('search_results', 2);
			} 
			pager.init(); 
			pager.showPageNav('pager', 'pageNavPosition'); 
			pager.showPage(1);

	    	}
	}

	xmlhttp.open("GET",'includes/liveSearchPhotos_server.php?search_text='+search_text+'&search_mode='+search_mode,true);
	xmlhttp.send();
}

function get_index_search()
{
	location.href="search.php?search=" + document.getElementById("search_field").value + "&mode=2"; 
}
