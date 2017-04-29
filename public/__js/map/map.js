class Map {
  constructor(searchable=true,markable=true,createLngLat=true) {
    this.map;
    this.marker;
    this.searchable = searchable;
    this.markable = markable;
    this.createLngLat = createLngLat;
    this.icon = '/images/map/location-pin.png';
    this.sideBarClosed = false;
    this.latitudeInputName = 'Address[latitude]';
    this.longitudeInputName = 'Address[longitude]';
  }

  load() {}

  setLocations(locations,sidePanel=true) {

    let _this = this;

    let options = {
        zoom: 9,
        center: new google.maps.LatLng(13.78473654934021,100.50567626953125),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    let map = new google.maps.Map(document.getElementById("map"), options);

    let len = Object.keys(locations).length;
    let lat = 0;
    let lng = 0;
    let markers = [];
    let infowindows = [];
    let infoWindowTemp = new google.maps.InfoWindow();
    let markerCounter = 0;

    for (var i = 0; i < len; i++) {

      if(locations[i]){

        if(!locations[i]['latitude'] || !locations[i]['longitude']) {
          this.createSidePanelItem(locations[i],false);
          continue;
        }

        this.createSidePanelItem(locations[i]);

        markerCounter++;

        lat += parseFloat(locations[i]['latitude']);
        lng += parseFloat(locations[i]['longitude']);
        
        let marker = new google.maps.Marker({
          map: map,
          icon: this.icon,
          position: new google.maps.LatLng(locations[i]['latitude'],locations[i]['longitude']),
        });

        let infowindow = new google.maps.InfoWindow({
          content: locations[i]['address']+'<br><a href="'+locations[i]['detailUrl']+'">แสดงรายละเอียดสาขานี้</a>'
        });

        marker.addListener('click', function() {
          infoWindowTemp.close();
          infoWindowTemp = infowindow;
          infowindow.open(map, marker);
        });

        marker.set('lat',locations[i]['latitude']);
        marker.set('lng',locations[i]['longitude']);

        markers[locations[i]['id']] = marker;
        infowindows[locations[i]['id']] = infowindow;

      }

    };

    if(markerCounter > 0) {
      map.setCenter(new google.maps.LatLng(lat/markerCounter,lng/markerCounter));
    }
    
    if(!sidePanel) {
      $('.side-panel').remove();
      return;
    }

    let handle;
    map.addListener('dragstart', function() {
      clearTimeout(handle);
      $('.side-panel').css('opacity','.5');
    });

    map.addListener('dragend', function() {
      handle = setTimeout(function(){
        $('.side-panel').css('opacity','1');
      },500);
    });

    $('.map-locations').on('click',function(){

      let marker = markers[$(this).data('id')];
      map.setCenter(new google.maps.LatLng(marker['lat'],marker['lng']));

      infoWindowTemp.close();
      infoWindowTemp = infowindows[$(this).data('id')];
      infoWindowTemp.open(map, marker);

    });

    $('.side-panel').css('display','block');

    let closeIcon = document.createElement('div');
    $(closeIcon).addClass('side-panel-close');

    $(closeIcon).on('click',function(){
      if(_this.sideBarClosed) {
        $('.side-panel').css('top','0');
        $(this).removeClass('rotate');
        _this.sideBarClosed = false;
      }else{
        $('.side-panel').css('top','100%');
        $(this).addClass('rotate');
        _this.sideBarClosed = true;
      }
      
    });

    $('#map_panel').append(closeIcon);

  }

  setLocation(geographic) {

    if ((typeof geographic['latitude'] != 'undefined') && (typeof geographic['longitude'] != 'undefined')) {

      let position = new google.maps.LatLng(geographic['latitude'],geographic['longitude']);

      this.marker = new google.maps.Marker({
        map: this.map,
        icon: this.icon,
        position: position,
      });

      this.map.setCenter(position);

      this.createHiddenData(geographic['latitude'],geographic['longitude']);

    }

  }

  initialize() {

    let _this = this;

    let options = {
        zoom: 8,
        center: new google.maps.LatLng(13.78473654934021,100.50567626953125),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    this.map = new google.maps.Map(document.getElementById("map"), options);

    this.marker = new google.maps.Marker();

    if(this.markable){
      this.mapMarker();
    }

    if(this.searchable){
      this.searchBox();
    }

  }

  mapMarker() {

    let _this = this;

    google.maps.event.addListener(this.map, 'click', function(event) {

      _this.marker.setMap(null);

      _this.marker = new google.maps.Marker({
        map: _this.map,
        icon: _this.icon,
        position: {lat: event.latLng.lat(), lng: event.latLng.lng()}
      });

      _this.createHiddenData(event.latLng.lat(),event.latLng.lng())

      // let geocoder = new google.maps.Geocoder();
      // geocoder.geocode({
      //   'latLng': event.latLng
      // }, function(results, status) {
      //   if (status == google.maps.GeocoderStatus.OK) {
      //     if (results[0]) {
      //       // results[0].formatted_address // Address
      //     }
      //   }
      // });

    });

  }

  searchBox() {

    let _this = this;

    let map = this.map;
    let marker = this.marker;

    // Create the search box and link it to the UI element.
    let input = document.getElementById('pac-input');
    let searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });


    searchBox.addListener('places_changed', function() {
      let places = searchBox.getPlaces();

      if (places.length == 0) {
        return;
      }

      marker.setMap(null);

      // For each place, get the icon, name and location.
      let bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        if (!place.geometry) {
          console.log("Returned place contains no geometry");
          return;
        }

        _this.createHiddenData(place.geometry.location.lat(),place.geometry.location.lng());

        geocoder.geocode({
          'latLng': place.geometry.location
        }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
              $("#address").text(results[0].formatted_address);
            }
          }
        });

        marker = new google.maps.Marker({
          map: map,
          icon: _this.icon,
          position: place.geometry.location
        });

        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });

      map.fitBounds(bounds);

    });

  }

  createSidePanelItem(item,location = true) {

    let html = '';
    html += '<div class="side-panel-item-row">';
    html += '<div class="side-panel-item with-icon">';
    if(location) {
      html += '<h4 class="title title-with-icon location-pin map-locations" data-id="'+item['id']+'">';
    }else{
      html += '<h4 class="title title-with-icon no-icon" data-id="'+item['id']+'">';
    }
    html += item['address'];
    html += '</h4>';
    html += '<a href="'+item['detailUrl']+'">แสดงรายละเอียด</a>'
    html += '</div>';
    html += '</div>';

    $('#location_items').append(html);
  }

  setInputName(latitudeName,longitudeName) {
    this.latitudeInputName = latitudeName;
    this.longitudeInputName = longitudeName;
  }

  createHiddenData(latitude,longitude) {

    if(this.createLngLat) {

      this.removeHiddenData();

      let lat = document.createElement('input');
      lat.setAttribute('type','hidden');
      lat.setAttribute('name',this.latitudeInputName);
      lat.setAttribute('id','lat');
      lat.value = latitude;

      let lng = document.createElement('input');
      lng.setAttribute('type','hidden');
      lng.setAttribute('name',this.longitudeInputName);
      lng.setAttribute('id','lng');
      lng.value = longitude;

      $('form').append(lat);
      $('form').append(lng);

    }
    
  }

  removeHiddenData() {
    $("#lat").remove();
    $("#lng").remove();
  }

}