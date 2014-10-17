// JavaScript Document
 var DeliveryZone = (function() {
    // "private" variables 
	var _PolygonMarkers ; //Array for Map Markers 
	var _PolygonPoints ; //Array for Map Markers 
	var _Polygon;
	var _Marker;
	var _Options;
	var _map;
	var _showonmap;
	 var _center;
	 var _radius;

    // constructor
    function DeliveryZone(){
		this._PolygonMarkers=[];
		this._PolygonPoints=[];
		
		};

   function DeliveryZone(LineColor,FillColor,center,radius){
		this._PolygonMarkers=[];
		this._PolygonPoints=[];
		 this._LineColor=LineColor;
		 this._FillColor=FillColor;
		 this._center=center;
		 this._radius=radius;
		 
		};
    // add the methods to the prototype so that all of the 
    // Foo instances can access the private static
	
	  DeliveryZone.prototype.getPolygon = function() {
        return this._Polygon;
    };
    DeliveryZone.prototype.setPolygon = function(Polygon) {
        this._Polygon = Polygon;
    };
	
    DeliveryZone.prototype.getMarkers = function() {
        return this._PolygonMarkers;
    };
    DeliveryZone.prototype.setMarkers = function(PolygonMarkers) {
        this._PolygonMarkers = PolygonMarkers;
    };
	
	 DeliveryZone.prototype.getPoints = function() {
        return this._PolygonPoints;
    };
    DeliveryZone.prototype.setPoints = function(PolygonPoints) {
        this._PolygonPoints = PolygonPoints;
    };

 
	
	DeliveryZone.prototype.getOptions = function() {
        return this._Options;
    };
	DeliveryZone.prototype.setOptions = function(Options) {
	           this._Options=Options;
    };
	
 DeliveryZone.prototype.getLineColor = function() {
        return this._LineColor;
    };
	DeliveryZone.prototype.setLineColor = function(color) {
	           this._LineColor=color;
    };
	
	 DeliveryZone.prototype.getFillColor = function() {
        return this._FillColor;
    };
	DeliveryZone.prototype.setFillColor = function(color) {
	           this._FillColor=Options;
    };
	
	DeliveryZone.prototype.addMarker = function(point,show) {
			
				var markerOptions = { icon: icon, draggable: true }; 
				var marker = new GMarker(point, markerOptions); 
				this._PolygonMarkers.push(marker); //Add marker to PolygonMarkers array 
			 	this._showonmap=show;
				if(show)	map.addOverlay(marker); //Add marker on the map 
				
				
				 
				var zone=this;
				GEvent.addListener(marker,'drag',function(){ zone.drawPolygon(); }); //Add drag event 
				GEvent.addListener(marker,'dragend',function(){   //Add drag end event 
				  zone.drawPolygon(); 
				  zone.fitPolygon(); 
				}); 
				   GEvent.addListener(marker,'click',function(point) { //Add Ctrl+Click event to remove marker 
					//	if (global.ctrlKey) { removeMarker(point,Polygon); } 
					 
				}); 
				this.drawPolygon(); 
				
				//If more then 2 nodes then automatically fit the polygon 
				if(this._PolygonMarkers.length > 2) this.fitPolygon(); 
	
    };
	
 
	
			 DeliveryZone.prototype.drawPolygon=function() 
				{ 
				   this._PolygonPoints.length=0; 
					for(var m=0; m<this._PolygonMarkers.length; m++) 
					{ 
						this._PolygonPoints.push(this._PolygonMarkers[m].getPoint()); //Add Markers to PolygonPoints node array 
					} 
					//Add first marker in the end to close the Polygon 
					 this._PolygonPoints.push(this._PolygonMarkers[0].getPoint()); 
					 
					
					if(this._Polygon && this._showonmap)map.removeOverlay(this._Polygon);
				 	this._Polygon = new GPolygon(this._PolygonPoints,this._LineColor, 3, 1, this._FillColor, 0.2); //New GPolygon object 
					if(this._showonmap) map.addOverlay(this._Polygon); //Add Polygon to the Map 
					 
	
				} 
			
			DeliveryZone.prototype.showhide=function(show) 
				{
					this._showonmap=show;
					if(show)
						this.show();
					 else
					 this.hide();
					 
				}
				
			 DeliveryZone.prototype.hide=function() 
				{
					
					for(var m=0; m<this._PolygonMarkers.length; m++) 
					{ 
						map.removeOverlay(this._PolygonMarkers[m]);
					 } 
					
					if(this._Polygon)map.removeOverlay(this._Polygon);
				} 
				DeliveryZone.prototype.show=function() 
				{
					 
					for(var m=0; m<this._PolygonMarkers.length; m++) 
					{ 
						map.addOverlay(this._PolygonMarkers[m]);
					 } 
					if(this._Polygon) map.addOverlay(this._Polygon);
				} 
			//Fits the Map to Polygon bounds 
			 DeliveryZone.prototype.fitPolygon=function(){ 
			 	bounds = this._Polygon.getBounds(); 
				if(this._showonmap) map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds)); 
			} 
 	
		DeliveryZone.prototype.DrawdefaultArea=function(show){ 
			  
			this.showhide(false);
				 this._PolygonMarkers.length=0;
				 this._PolygonPoints.length=0; 
				for (var i = 0; i < 12; i++) {
						x = (this._center.y + this._radius * Math.cos(2 * Math.PI * i / 12));
						y = (this._center.x +  this._radius * Math.sin(2 * Math.PI * i / 12));
						this.addMarker({y:x , x: y},show)
					}
			} 
			
		 DeliveryZone.prototype.DrawZone=function(show,coordinates){ 
			  
				this.showhide(false);
				 this._PolygonMarkers.length=0;
				 this._PolygonPoints.length=0; 
				 var points=  coordinates.split("~");
				for (var i = 0; i < points.length; i++) {
					var point=points[i].split(',');
				 
						this.addMarker({y:point[0] , x: point[1]},show)
					}
			}
			
		DeliveryZone.prototype.Coordinates=function(){ 
				var rows = [];
 
				var len = this._PolygonPoints.length||0;
				for(var i=0; i<len-1; i++){
					 rows.push(this._PolygonPoints[i].y.toFixed(6)+","+this._PolygonPoints[i].x.toFixed(6));
					}
				 
				return	rows.join('~')
			}//Coordinates
	 
		DeliveryZone.prototype.containaddress=function(point){ 
			if(this._Polygon){
				 return this._Polygon.containsLatLng(point);
			}		
		}
		
    return DeliveryZone;
})();

GPolygon.prototype.containsLatLng = function(latLng) {
    // Do simple calculation so we don't do more CPU-intensive calcs for obvious misses
    var bounds = this.getBounds();
 
    if(!bounds.containsLatLng(latLng)) {
        return false;
    }

    // Point in polygon algorithm found at http://msdn.microsoft.com/en-us/library/cc451895.aspx
    var numPoints = this.getVertexCount();
    var inPoly = false;
    var i;
    var j = numPoints-1;

    for(var i=0; i < numPoints; i++) { 
        var vertex1 = this.getVertex(i);
        var vertex2 = this.getVertex(j);

        if (vertex1.lng() < latLng.lng() && vertex2.lng() >= latLng.lng() || vertex2.lng() < latLng.lng() && vertex1.lng() >= latLng.lng())  {
            if (vertex1.lat() + (latLng.lng() - vertex1.lng()) / (vertex2.lng() - vertex1.lng()) * (vertex2.lat() - vertex1.lat()) < latLng.lat()) {
                inPoly = !inPoly;
            }
        }

        j = i;
    }

    return inPoly;
	};