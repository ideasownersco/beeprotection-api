/**
 * Theme: Ubold Admin Template
 * Author: Coderthemes
 * Google Maps
 */

!function($) {
  "use strict";


  var GoogleMap = function() {};

    //creates map with markers
    GoogleMap.prototype.createMarkers = function($container) {

      var mapOptions = {
        zoom: 5,
        // center: myLatlng,
        // mapTypeId: GoogleMap().MapTypeId.ROADMAP,
        backgroundColor: '#FFF',
        disableDefaultUI: true,
        draggable: false,
        scaleControl: false,
        scrollwheel: false,
        styles: [
          {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [
              {"visibility": "off"}
            ]
          }, {
            "featureType": "landscape",
            "stylers": [
              {"visibility": "off"}
            ]
          }, {
            "featureType": "road",
            "stylers": [
              {"visibility": "off"}
            ]
          }, {
            "featureType": "administrative",
            "stylers": [
              {"visibility": "off"}
            ]
          }, {
            "featureType": "poi",
            "stylers": [
              {"visibility": "off"}
            ]
          }, {
            "featureType": "administrative",
            "stylers": [
              {"visibility": "off"}
            ]
          }, {
            "elementType": "labels",
            "stylers": [
              {"visibility": "off"}
            ]
          }
        ]
      };

      // var map = new GMaps(mapOptions);
      var map = new GMaps({
        div: $container,
        lat: -12.043333,
        lng: -77.028333,
      });

      //sample markers, but you can pass actual marker data as function parameter
      map.addMarker({
        lat: -12.043333,
        lng: -77.03,
        title: 'Lima',
        details: {
          database_id: 42,
          author: 'HPNeo'
        },
        click: function(e){
          if(console.log)
            console.log(e);
          alert('You clicked in this marker');
        }
      });
      map.addMarker({
        lat: -12.042,
        lng: -77.028333,
        title: 'Marker with InfoWindow',
        infoWindow: {
          content: '<p>HTML Content</p>'
        }
      });

      return map;
    },
    //init
      GoogleMap.prototype.init = function() {
        var $this = this;
        $(document).ready(function(){
            $this.createMarkers('#gmaps-markers');
        });
      },
    $.GoogleMap = new GoogleMap, $.GoogleMap.Constructor = GoogleMap
}(window.jQuery),

//initializing
  function($) {
    "use strict";
    $.GoogleMap.init()
  }(window.jQuery);