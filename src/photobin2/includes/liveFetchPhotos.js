
function getPhotos(swLat, swLng, neLat, neLng)
{
	if ( (swLat=="") || (swLng=="") || (neLat=="") || (neLng=="") )
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
							// tyxaio shmeio anaforas gia ton ypologismo mias aktinas apostasis 
			var c_latitude = 38.8900700061649; 					// gia kathe eikona 
			var c_longtitude = 19.82544490625;
			var zoom = map.getZoom();
			last_dist = -1000000;

					// for every image in xml load infos
			for(var i=0; i<count; i++)
			{
				picture_id = xmlDoc.getElementsByTagName("id")[i].childNodes[0].nodeValue;
				name = xmlDoc.getElementsByTagName("name")[i].childNodes[0].nodeValue;
				username = xmlDoc.getElementsByTagName("username")[i].childNodes[0].nodeValue;
				title = xmlDoc.getElementsByTagName("title")[i].childNodes[0].nodeValue;
				longtitude = xmlDoc.getElementsByTagName("longtitude")[i].childNodes[0].nodeValue;
				latitude = xmlDoc.getElementsByTagName("latitude")[i].childNodes[0].nodeValue;			
						// ypologismos tis aktinas apo to tyxaio kentro se pixels
				var dist = pixelDistance(c_latitude, c_longtitude, latitude, longtitude, zoom);
							// an i nea eikona einai konta stin proigoumeni poy fortothike
				if( (dist - last_dist) < 10)	// fere tin se mikro megethos, allios se megalo
				{
					var size = 0;
					var path = 'user_space/'+username+"/thumbs_small/"+name;	
				}
				else
				{
					var size = 1;		// build image path
					var path = 'user_space/'+username+"/thumbs_medium/"+name;
				}
				
				last_dist = dist;
								
						// create icon for marker
				var image=new google.maps.MarkerImage(path, new google.maps.Size(100, 100), 
						new google.maps.Point(0, 0), new google.maps.Point(0, 0) );

			    addMarker(latitude, longtitude, image, title, picture_id, i, size);					
			}
				// clear markers out of this area
			clearMarkers();	
	    	}
	}
						// open asynchronous GET request to liveMap ajax server
	xmlhttp.open("GET",'includes/liveMap_server.php?swLat='+swLat+'&swLng='+swLng+'&neLat='+neLat+'&neLng='+neLng,true);
	xmlhttp.send(); // send request
}

			// convert longtitude to X pixels
function lonToX(longtitude)
{
	var test = 0;
	test = (offset + radius * longtitude * Math.PI / 180);
	test = Math.round(test);
	return test;   
}
			// convert latitde to Y pixels
function latToY(latitude)
{
	var test= 0;
	test = Math.round( offset - radius * Math.log( (1 + 
				Math.sin(latitude * Math.PI / 180)) / (1 - Math.sin(latitude * Math.PI / 180)) ) /2 );

	return test;
}
			// calculate the distance of 2 points of a google map (return pixels)
function pixelDistance(lat1, lon1, lat2, lon2, zoom)
{
    x1 = lonToX(lon1);
    y1 = latToY(lat1);

    x2 = lonToX(lon2);
    y2 = latToY(lat2);
        
	var test =0;

	test = Math.sqrt( Math.pow((x1-x2),2) + Math.pow((y1-y2),2)) >> (21 - zoom);

	return test;
}

