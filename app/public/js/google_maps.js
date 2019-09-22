// Initlialize Google Map
 function initMap() {
    var startCoords = {lat: 52.114, lng: 19.449};
    var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6, 
            center: startCoords,
      });
      new AutocompleteDirectionsHandler(map);
  }
  
// Autocomplite Directions 
  function AutocompleteDirectionsHandler(map) {
    this.map = map;
    this.originPlaceId = null;
    this.destinationPlaceId = null;
    this.travelMode = 'DRIVING';
    this.directionsService = new google.maps.DirectionsService;
    this.directionsRenderer = new google.maps.DirectionsRenderer({draggable: true});
    this.directionsRenderer.setMap(map);
  
    var originInput = document.getElementById('origin-input');
    var destinationInput = document.getElementById('destination-input');
  
  
    var originAutocomplete = new google.maps.places.Autocomplete(originInput);
    originAutocomplete.setFields(['place_id']['formatted_address']);
  
    var destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);
    destinationAutocomplete.setFields(['place_id']['formatted_address']);
  
  
    this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
    this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');
  
    this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
    this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(
        destinationInput);
  }
  
// Origin - Destination autocomplete
  AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(
      autocomplete, mode) {
    var me = this;
    autocomplete.bindTo('bounds', this.map);
  
    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();
      console.log(place);
  
      if (!place.place_id) {
        window.alert('Please select an option from the dropdown list.');
        return;
      }
      if (mode === 'ORIG') {
        me.originPlaceId = place.place_id;
        orig = place.place_id;
      } else {
        me.destinationPlaceId = place.place_id;
        dest = place.place_id;
      }
      me.route();
    });
  };
  
// Display route 
  AutocompleteDirectionsHandler.prototype.route = function() {
    if (!this.originPlaceId || !this.destinationPlaceId) {
      return;
    }
    var me = this;
  
    this.directionsService.route(
        {
          origin: {'placeId': this.originPlaceId},
          destination: {'placeId': this.destinationPlaceId},
          travelMode: this.travelMode
        },
        function(response, status) {
          if (status === 'OK') {
            me.directionsRenderer.setDirections(response);
            displayRoute();
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
  };
  
// Formatted adress of orig-dest
  var orig;
  var dest;

// Display menu for manage load from map
  function displayRoute(){
    if(orig == undefined || dest == undefined){
        return;
    }

    console.log('elo')
}  