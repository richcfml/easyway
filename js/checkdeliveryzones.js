if ((typeof(geocoder) == "undefined") || (geocoder === null))
{
	geocoder = new google.maps.Geocoder();
}

var Map;
var Id = 1;
function map(canvas, position, zoom, mapTypeId) {
    var mapOptions = {
        center: position,
        zoom: zoom,
        mapTypeId: mapTypeId,
	 useStaticMapImpl: false
    };
    Map = new google.maps.Map(canvas, mapOptions);

    var icon = 'http://www.easywayordering.com/c_panel/images/restaurant.png';
    var RestMarker = setmarker(position, icon, false);
    RestMarker.setMap(Map);
}

function setmarker(position, icon, draggable) {
    var marker = new google.maps.Marker({
        position: position,
        icon: icon,
        draggable: draggable
    });
    return marker;
}

var DeliveryZone = (function() {
    var ZoneMarkers; //Array for Map Markers 
    var ZonePoints; //Array for Map Points 
    var strokeColor;
    var strokeOpacity;
    var strokeWeight;
    var fillColor;
    var fillOpacity;
    var center;
    var radius;
    var showOnMap;
    var polygon;

    function DeliveryZone() {
        this.ZoneMarkers = [];
        this.ZonePoints = [];
        this.id = Id++;
    }
    

    function DeliveryZone(coordinates, strokeColor, strokeOpacity, strokeWeight, fillColor, fillOpacity, radius, center) {
        this.ZoneMarkers = [];
        this.ZonePoints = [];
        this.coordinates = coordinates;
        this.strokeColor = strokeColor;
        this.strokeOpacity = strokeOpacity;
        this.strokeWeight = strokeWeight;
        this.fillColor = fillColor;
        this.fillOpacity = fillOpacity;
        this.center = center;
        this.radius = radius;
        this.polygon;
        this.id = Id++;
    }
    



    DeliveryZone.prototype.getId = function() {
        return this.id ;
    }
    DeliveryZone.prototype.clearZone = function() {
        if(this.ZoneMarkers.length){
            for(var marker in this.ZoneMarkers){
                this.ZoneMarkers[marker].setMap(null);
            }
        }
        this.ZoneMarkers = [];
        this.ZonePoints = [];
        return;
    }
    DeliveryZone.prototype.drawZone = function(show, defaultZone) {
        var Zones = this.coordinates.split('~');
        this.clearZone();
        this.showOnMap = show;
        console.log(this.id);
        console.log(this.coordinates);
        console.log(defaultZone ? 'default' : 'saved');
        if (defaultZone === false) {
            var ind =0;
            for (var i in Zones) {
                //if(ind == 11 && this.id == 9)continue;
                var Points = Zones[i].split(',');
                console.log(Points[0], Points[1]);
                var pointlocation = new google.maps.LatLng(parseFloat(Points[0]), parseFloat(Points[1]));
                this.ZonePoints.push(pointlocation);

                var icon = 'http://www.easywayordering.com/c_panel/images/Bpd.png';
                var PointMarker = this.setmarker(pointlocation, icon, true);
                
                this.ZoneMarkers.push(PointMarker);
                
                
                if (show)
                    this.showMarker(PointMarker);
                else
                    this.hideMarker(PointMarker);
                
                
                var DZ = this;

                google.maps.event.addListener(PointMarker, 'dragend', function(){DZ.markerPositionChanged(DZ.polygon);});
                google.maps.event.addListener(PointMarker, 'drag', function(){DZ.markerPositionChanged(DZ.polygon);});
                ind++;
            }
        } else {
            for (var i = 0; i < 12; i++) {
                var x = (this.center.lat() + this.radius * Math.cos(2 * Math.PI * i / 12));
                var y = (this.center.lng() + this.radius * Math.sin(2 * Math.PI * i / 12));
                console.log(i, x, y);
                var pointlocation = new google.maps.LatLng(x, y);
                this.ZonePoints.push(pointlocation);

                var icon = 'http://www.easywayordering.com/c_panel/images/Bpd.png';
                var PointMarker = this.setmarker(pointlocation, icon, true);
                this.ZoneMarkers.push(PointMarker);
                
                if (show)
                    this.showMarker(PointMarker);
                else
                    this.hideMarker(PointMarker);
                
                var DZ = this;

                google.maps.event.addListener(PointMarker, 'dragend', function(){DZ.markerPositionChanged(DZ.polygon);});
                google.maps.event.addListener(PointMarker, 'drag', function(){DZ.markerPositionChanged(DZ.polygon);});

            }
        }
        
        this.polygon = this.generatePolygon();
        this.showhide(this.showOnMap);
    };

    DeliveryZone.prototype.setmarker = function(position, icon, draggable) {
        var marker = new google.maps.Marker({
            position: position,
			size: new google.maps.Size(14, 14),
            icon: icon,
            draggable: draggable
        });
        return marker;
    };

    

    DeliveryZone.prototype.generatePolygon = function() {
        
        if(this.polygon)
        this.polygon.setMap(null);
        
        var polygon = new google.maps.Polygon({
            paths: this.ZonePoints,
            strokeColor: this.strokeColor,
            strokeOpacity: this.strokeOpacity,
            strokeWeight: this.strokeWeight,
            fillColor: this.fillColor,
            fillOpacity: this.fillOpacity
        });
        if(this.showOnMap)
        polygon.setMap(Map);
        
        return polygon;
    };

    DeliveryZone.prototype.markerPositionChanged = function() {
        
        var Markers = this.ZoneMarkers;
        this.ZonePoints = [];
	console.log('Marker Position Change');

        for (var i in Markers) {

            var MarkerLat = Markers[i].getPosition().lat();
            var MarkerLng = Markers[i].getPosition().lng();
            console.log(MarkerLat, MarkerLng);
            var point = new google.maps.LatLng(MarkerLat, MarkerLng);
            this.ZonePoints.push(point);

        }
        

        this.polygon = this.generatePolygon();
        //this.showhide(this.showOnMap);
    };
    
    DeliveryZone.prototype.hide = function() {
        for (var i in this.ZoneMarkers) {
            this.ZoneMarkers[i].setMap(null);
        }
        if (this.polygon)
                this.polygon.setMap(null);
    };

    DeliveryZone.prototype.show = function() {
        for (var i in this.ZoneMarkers) {
            this.ZoneMarkers[i].setMap(Map);
        }
        if (this.polygon)
                this.polygon.setMap(Map);
    };

    DeliveryZone.prototype.hideMarker = function(PointMarker) {
        
        PointMarker.setMap(null);
//        if (this.Polygon)
//            this.Polygon.setMap(null);
    };

    DeliveryZone.prototype.showMarker = function(PointMarker) {
        
        PointMarker.setMap(Map);
        
//        if (this.Polygon)
//            this.Polygon.setMap(Map);
    };

    DeliveryZone.prototype.showhide = function(show) {
        this.showOnMap = show;
        if (show === true) {
            this.show();
        } else {
            this.hide();
        }
    };
    
    DeliveryZone.prototype.Coordinates = function() {
        var rows = [];
        var len = this.ZonePoints.length || 0;
        for (var i = 0; i < len ; i++) {
            rows.push(this.ZonePoints[i].lat().toFixed(6) + "," + this.ZonePoints[i].lng().toFixed(6));
        }//live pe yehi use hora hai. do not change this way!
       
        

        return	rows.join('~');
    }//Coordinates

    DeliveryZone.prototype.containaddress = function(point) {
        var polygon = new google.maps.Polygon({path: this.ZonePoints});
        if (polygon.Contains(point)) { // point is inside polygon 
           return true;
        }
    }
    return DeliveryZone;
})();

google.maps.Polygon.prototype.Contains = function(point) {
  var crossings = 0, path = this.getPath();

  // for each edge
  for (var i=0; i < path.getLength(); i++) {
      var a = path.getAt(i),
          j = i + 1;
      if (j >= path.getLength()) {
          j = 0;
      }
      var b = path.getAt(j);
      if (rayCrossesSegment(point, a, b)) {
        crossings++;
      }
  }

  // odd number of crossings?
  return (crossings % 2 == 1);

  function rayCrossesSegment(point, a, b) {
    var px = point.lng(),
        py = point.lat(),
        ax = a.lng(),
        ay = a.lat(),
        bx = b.lng(),
        by = b.lat();
    if (ay > by) {
        ax = b.lng();
        ay = b.lat();
        bx = a.lng();
        by = a.lat();
    }
    // alter longitude to cater for 180 degree crossings
    if (px < 0) { px += 360; };
    if (ax < 0) { ax += 360; };
    if (bx < 0) { bx += 360; };

    if (py == ay || py == by) py += 0.00000001;
    if ((py > by || py < ay) || (px > Math.max(ax, bx))) return false;
    if (px < Math.min(ax, bx)) return true;

    var red = (ax != bx) ? ((by - ay) / (bx - ax)) : Infinity;
    var blue = (ax != px) ? ((py - ay) / (px - ax)) : Infinity;
    return (blue >= red);
  }
};
/**
* @param {google.maps.LatLng} newLatLng
* @returns {number}
*/
google.maps.LatLng.prototype.distanceFrom = function(newLatLng) {
   // setup our variables
   var lat1 = this.lat();
   var radianLat1 = lat1 * ( Math.PI  / 180 );
   var lng1 = this.lng();
   var radianLng1 = lng1 * ( Math.PI  / 180 );
   var lat2 = newLatLng.lat();
   var radianLat2 = lat2 * ( Math.PI  / 180 );
   var lng2 = newLatLng.lng();
   var radianLng2 = lng2 * ( Math.PI  / 180 );
   // sort out the radius, MILES or KM?
   var earth_radius = 3959; // (km = 6378.1) OR (miles = 3959) - radius of the earth
 
   // sort our the differences
   var diffLat =  ( radianLat1 - radianLat2 );
   var diffLng =  ( radianLng1 - radianLng2 );
   // put on a wave (hey the earth is round after all)
   var sinLat = Math.sin( diffLat / 2  );
   var sinLng = Math.sin( diffLng / 2  ); 
 
   // maths - borrowed from http://www.opensourceconnections.com/wp-content/uploads/2009/02/clientsidehaversinecalculation.html
   var a = Math.pow(sinLat, 2.0) + Math.cos(radianLat1) * Math.cos(radianLat2) * Math.pow(sinLng, 2.0);
 
   // work out the distance
   var distance = earth_radius * 2 * Math.asin(Math.min(1, Math.sqrt(a)));
 
   // return the distance
   return distance;
};
