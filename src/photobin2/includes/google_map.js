		// initialize map 	
function initialize() {
	var mapOptions = {
	      zoom: 5,    // zoom to level 5 
	      center: new google.maps.LatLng(38.8900700061649, 19.82544490625), // default location somewhere around patra	
	      mapTypeId: google.maps.MapTypeId.ROADMAP	// Road map type
	};

	map =  new google.maps.Map(document.getElementById('map'), mapOptions); // create map instance 

	infowindow = new google.maps.InfoWindow();	// create infoWindow instance
}


function supports_geolocation()
{
    return !!navigator.geolocation;
}
     		// try to use html5 geolocation
function get_location() 
{
	if ( supports_geolocation() )
	{		
		navigator.geolocation.getCurrentPosition(get_position, handle_error); 	
	} 
}
     			// callback to get location if supports geolocation
function get_position(position)
{					// get client's public ip's position
	latitude = position.coords.latitude; 
	longtitude = position.coords.longitude;
							// center map to client public ip
	map.setCenter(new google.maps.LatLng(latitude, longtitude)); 
}


function show_map(longtitude, latitude, image_path)
{     
	var latlng = new google.maps.LatLng(latitude, longtitude);
	var myOptions = {
				zoom: 3,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
    	map = new google.maps.Map(document.getElementById("map"), myOptions);

	var marker = new google.maps.Marker({
      		position: latlng,
      		map: map,
      		icon: image
  	});
   
	marker.setMap(map);
}

function show_upload_map(longtitude, latitude)
{          
	var latlng = new google.maps.LatLng(latitude, longtitude);
	var myOptions = {
				zoom: 6,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
    	map = new google.maps.Map(document.getElementById("map"), myOptions);

	google.maps.event.addListener(map, 'click', function(event){
		take_long_lat(event.latLng, map);
	});
}

function take_long_lat(location, map)
{
	map.setCenter(location);

	var latitude = location.lat();
	var longtitude = location.lng();

        document.getElementById("Lat").value = latitude;
        document.getElementById("Long").value = longtitude;
}
     			// callback for errors, if error on geolocaion
function handle_error(err)
{
	if(err.PERMISSION_DENIED)
	{
	//	alert("User denied access!");
	}
	else if(err.POSITION_UNAVAILABLE)
	{
	//	alert("You must be hiding in Area 51!");
	}
	else if(err.TIMEOUT)
	{
	//	alert("hmmm we timed out trying to find where you are hiding!");
	}
}
