
function addMarker(latitude, longtitude, image, title, photo_id, pos, size_update)
{
	need_id.push(photo_id);
					// if image is in map and must change size recreate the marker
	if(typeof size[photo_id] !== 'undefined')
	{
		if(size[photo_id] !== size_update)
		{
			markersArray[photo_id].setMap(null);

			marker = new google.maps.Marker({
				position: new google.maps.LatLng(latitude, longtitude),
				map: map,
      				icon: image
			});
	
			var link = '<a href="view.php?image_id='+ photo_id +'"> View photo </a>';
			var content = title + link

			google.maps.event.addListener(marker, 'click', (function(marker, pos) {
				return function() {
					  	infowindow.setContent(content);
						infowindow.open(map, marker);
					  }
			})(marker, pos));

			markersArray[photo_id] = marker; 
			size[photo_id] = size_update;
		}
	}
					// if image-marker not in map, make marker and pin it
	if (typeof markersArray[photo_id] == 'undefined')
	{
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(latitude, longtitude),
			map: map,
      			icon: image
		});

		var link = '<a href="view.php?image_id='+ photo_id +'"> View photo </a>';
		var content = 'Title: ' + title + link
	
		google.maps.event.addListener(marker, 'click', (function(marker, pos) {
			return function() {
					  	infowindow.setContent(content);
						infowindow.open(map, marker);
					  }
		})(marker, pos));

		markersArray[photo_id] = marker; 
		size[photo_id] = size_update;
	}
}
		// delete all image-markers that are out of client map view
function clearMarkers()
{
	if (markersArray)
	{
		for (i in markersArray)
		{
			ok = 0;

			for(j in need_id)
			{
				if(i == need_id[j])
				{
					ok = 1;
				}
			}

			if(ok == 0)
			{
				markersArray[i].setMap(null);
				delete markersArray[i];
				delete size[i];
			}		
		}
	}
}
		// called on map change position
function refreshMarkers()
{				// take bounds from this area
	var bounds = map.getBounds();

	var swPoint = bounds.getSouthWest();
	var nePoint = bounds.getNorthEast();
	var swLat = swPoint.lat();
	var swLng = swPoint.lng();
	var neLat = nePoint.lat();
	var neLng = nePoint.lng();
			// clear array with need photos in this area
	need_id.length = 0;
					// get photos from this area
	getPhotos(swLat, swLng, neLat, neLng);
}

