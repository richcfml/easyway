<?
require_once("../includes/config.php");
$ajax=1;

if(isset($_GET['savedata'])){
//include "includes/resturant_header.php" ;
	extract($_POST);
	
	$_SESSION["restaurant_delivery_zones"] = array(
		"zone1" => $zone1
		,"zone1_delivery_charges" => $zone1_delivery_charges
		,"zone1_min_total" => $zone1_min_total
		
		,"zone2" => $zone2
		,"zone2_delivery_charges" => $zone2_delivery_charges
		,"zone2_min_total" => $zone2_min_total
		
		,"zone3" => $zone3
		,"zone3_delivery_charges" => $zone3_delivery_charges
		,"zone3_min_total" => $zone3_min_total
		
		,"zone1_coordinates" => $zone1_coordinates
		,"zone2_coordinates" => $zone2_coordinates
		,"zone3_coordinates" => $zone3_coordinates
		,"delivery_option" => "delivery_zones"
		,"restaurant_location" => $restaurant_location
	);
	die(0);
}

?>
<html>
	<head>
		<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
		<style>
		#map_canvas {
			width:930px;
			height:630px;
		 
		}
		input[type="text"] {
			width: 50px !important;
		}
		.content {
			background-color: #ffffff !important;
		}
		</style>
		<script src="http://maps.google.com/maps?file=api&v=2&key=<?=$google_api_key?>" type="text/javascript"></script>
		<script src="../js/deliveryzones.js" type="text/javascript"></script>
		<script type="text/javascript" language="javascript">
			window.geocoder;
			function initialize() {
				
				window.geocoder = new GClientGeocoder();
			}
		</script>
</head>
<body onload="initialize()">
		<div style="float:right">
		  <input id="close" type="image" src="../images/closelabel.gif"  >
		   
		</div>
		  <div style="clear:both"></div>
		<div style="border:1px solid;width:100%;margin-bottom:5px;">
		<?
			if(
				!empty($_SESSION["restaurant_delivery_zones"]) 
				&& isset($_SESSION["restaurant_delivery_zones"]["restaurant_location"])
				&& isset($_GET["address"])
				&& isset($_GET["city"])
				&& isset($_GET["state"])
				&& $_SESSION["restaurant_delivery_zones"]["restaurant_location"] == ($_GET["address"] . " " . $_GET["city"] . " " . $_GET["state"])
			) {
				extract($_SESSION["restaurant_delivery_zones"]);
			} else {
				$zone1 = 1;
				$zone1_delivery_charges = 0;
				$zone1_min_total = 0;
				
				$zone2 = 1;
				$zone2_delivery_charges = 0;
				$zone2_min_total = 0;
				
				$zone3 = 1;
				$zone3_delivery_charges = 0;
				$zone3_min_total = 0;
				
				$zone1_coordinates = "";
				$zone2_coordinates = "";
				$zone3_coordinates = "";
			}
		?>
		 <div style="clear:right"></div>
			<div style="background-color:#eed000;padding:10px;"><strong>Delivery Area Information ( Resturant Location: <span id="restlocation"></span> )</strong></div>
			<div style="padding:10px;">
			<div style="float:left;margin-right:15px;font-weight:bold;">Delivery Area Charges  </div>
			<div style="float:left;margin-right:40px;"><input type="checkbox"  id="zone1" name="zone1"  class="zonehaldler" zone="1"  />Zone 1:
			<br/>
			Charges:<input type="text" name="zone1_delivery_charges" id="zone1_delivery_charges"  size="3"  style="background-color:#00CC00;color:#FFF;"/><br/>
			Min Total:<input type="text"  size="3" name="zone1_min_total"  id="zone1_min_total" style="background-color:#00CC00;color:#FFF;"/>
			</div> 
			<div style="float:left;margin-right:40px;">Zone 2:<input type="checkbox"  id="zone2" name="zone2" class="zonehaldler" zone="2" />
			
			 <br/>
			Charges:<input type="text"  size="3"  name="zone2_delivery_charges" id="zone2_delivery_charges" style="background-color:#003595;color:#FFF;"/><br/>
			 Min Total:<input type="text"  size="3"   name="zone2_min_total"  id="zone2_min_total" style="background-color:#003595;color:#FFF;"/>
			
			</div> 
			<div style="float:left;margin-right:40px;">Zone 3:<input type="checkbox"  id="zone3" name="zone3"  class="zonehaldler" zone="3"  />
			<br/>
			Charges:<input type="text" size="3"  name="zone3_delivery_charges" id="zone3_delivery_charges"style="background-color:#f33f00;color:#FFF;"/> <br/>
			 Min Total:<input type="text" size="3"   name="zone3_min_total"  id="zone3_min_total"style="background-color:#f33f00;color:#FFF;"/> </div> 
			
		  
			</div>
			<div style="float:right">Reset<select name="resetzone" id="resetzone"><option value="0">Select Zone</option>
			<option value="1" radius="0.9">Zone 1</option>
			<option value="2" radius="0.6">Zone 2</option>
			<option value="3" radius="0.4">Zone 3</option>
			
			</select>
			<input type="hidden"  id="hzone1"  value="<? echo $zone1; ?>"/>
            <input type="hidden"  id="hzone1_delivery_charges" value="<? echo $zone1_delivery_charges; ?>"/>
            <input type="hidden"  id="hzone1_min_total" value="<? echo $zone1_min_total; ?>"/>
            <input type="hidden"  id="hzone2" value="<? echo $zone2; ?>"/>
            <input type="hidden"  id="hzone2_delivery_charges" value="<? echo $zone2_delivery_charges; ?>"/>
            <input type="hidden"  id="hzone2_min_total" value="<? echo $zone2_min_total; ?>"/>
            <input type="hidden"  id="hzone3" value="<? echo $zone3; ?>"/>
            <input type="hidden"  id="hzone3_delivery_charges" value="<? echo $zone3_delivery_charges; ?>"/>
            <input type="hidden"  id="hzone3_min_total" value="<? echo $zone3_min_total; ?>"/>
            <input type="hidden"  id="hzone1_coordinates" value="<? echo $zone1_coordinates; ?>"/>
            <input type="hidden"  id="hzone2_coordinates" value="<? echo $zone2_coordinates; ?>"/>
            <input type="hidden"  id="hzone3_coordinates" value="<? echo $zone3_coordinates; ?>"/>
			<br/>
			<input type="button" onclick="saveZones()" value="Save Zones" />
			</div>
			 <div style="clear:both"></div>
		</div>
		 
		<div id="map_canvas" name="map_canvas"></div>
		<script type="text/javascript"> 
		var icon = new GIcon();
		var Zone1;
		var Zone2;
		var Zone3;
		var Zone1Coordinates,Zone2Coordinates,Zone3Coordinates;
		icon.image = "images/Bpd.png";
		icon.iconSize = new GSize(12, 12);
		icon.iconAnchor = new GPoint(6, 6);
		var hdnZoomLevel=13;
		var start;
		var restaurantpin = new GIcon(); //Red Pushpin Icon 
		restaurantpin.image = "images/restaurant.png"; 
		restaurantpin.iconSize = new GSize(32, 32); 
		restaurantpin.iconAnchor = new GPoint(10, 32); 
		$(document).ready(function() {
			var geocoder = new GClientGeocoder();
			 var restaurant_location= "<? echo $_GET["address"]; ?> <? echo $_GET["city"]; ?> <? echo $_GET["state"]; ?>";
			 $("#restlocation").html(restaurant_location)
			 $("#map_canvas").html('<div class="msg_warning"><img src="../images/loading.gif">Please wait loading map</div>');
			$("#close").click(function() {
					$("#facebox_overlay").click();
				});
				 
					if($("#hzone1").val()=='1')
					  $("#zone1"). attr('checked','checked');
					$("#zone1_delivery_charges").val($("#hzone1_delivery_charges").val()); 
					$("#zone1_min_total").val($("#hzone1_min_total").val());

					 
					
					if($("#hzone2").val()=='1')
					$("#zone2"). attr('checked','checked');
						$("#zone2_delivery_charges").val($("#hzone2_delivery_charges").val()); 
						$("#zone2_min_total").val($("#hzone2_min_total").val());
					
					if($("#hzone3").val()=='1')
								$("#zone3"). attr('checked','checked');
					$("#zone3_delivery_charges").val($("#hzone3_delivery_charges").val()); 
					$("#zone3_min_total").val($("#hzone3_min_total").val());
							
					Zone1Coordinates=$("#hzone1_coordinates").val();
					Zone2Coordinates=$("#hzone2_coordinates").val();
					Zone3Coordinates=$("#hzone3_coordinates").val();
											
			geocoder.getLocations(restaurant_location, function (response) {
					if (!response || response.Status.code != 200)
					{
						alert("Sorry, we were unable to recognize the resturant address 1");
						return false; 
					}
					
					else
					{
						 location1 = {latitude: response.Placemark[0].Point.coordinates[1], longitude: response.Placemark[0].Point.coordinates[0]};
						  $("#map_canvas").html('');
						restaurantlocation=new GLatLng(location1.latitude, location1.longitude);
						 
						
						if (GBrowserIsCompatible()) { 
							map = new GMap2(document.getElementById("map_canvas")); //New GMap object 
							map.setCenter(restaurantlocation, 13); 
							var ui = new GMapUIOptions(); //Map UI options 
							ui.maptypes = { normal:true, satellite:true, hybrid:true, physical:false } 
							ui.zoom = {scrollwheel:true, doubleclick:true}; 
							ui.controls = { largemapcontrol3d:true, maptypecontrol:true, scalecontrol:true }; 
							map.setUI(ui);
							
							
						 
						 
							drawzones(restaurantlocation);
							 GEvent.addListener(map, "zoomend", function (oldLevel, newLevel) {
											hdnZoomLevel = newLevel;
											map.setCenter(restaurantlocation, hdnZoomLevel);
									 }
							)} 
					
					 }
				});
				
			$(".zonehaldler").click(function(){
				show=($(this).is(':checked'));
				 
						var zone=$(this).attr('zone');
						switch (zone) {
							case '1':
								Zone1.showhide(show);
								break;
						  case '2':
								Zone2.showhide(show);
								break;
							 case '3':
								Zone3.showhide(show);
								break;
						 default:
							break;
						}
				});
				
			$("#resetzone").change(function(){
					 if($(this).val()==0)return false;
						var zone=$(this).val();
						$(this).val(0);
						if(!$("#zone"+zone).is(':checked')) {alert("please select zone "+zone);return false; };
						 if(!confirm('Are you sure you want to reset zone '+zone +'?'))return false;
			
						switch (zone) {
							case '1':
								Zone1.DrawdefaultArea(true);
								break;
						  case '2':
								Zone2.DrawdefaultArea(true);
								break;
							 case '3':
								Zone3.DrawdefaultArea(true);
								break;
						 default:
							break;
						}
						
				});
			
		 });
		 

		 
		function drawzones(restaurantlocation) {
			 
			var marker = new GMarker( restaurantlocation , { icon: restaurantpin, draggable: false }); 
			 map.addOverlay(marker);  
			
			 
			
			
			
			 Zone1=new DeliveryZone('#00CC00','#00A333',restaurantlocation,0.02);
			 Zone2=new DeliveryZone('#003595','#000088',restaurantlocation,0.025);
			 Zone3=new DeliveryZone('#f33f00','#ffaa00',restaurantlocation,0.03);
			
			var	showgreen=($("#zone1").is(':checked'));
			var	showblue=($("#zone2").is(':checked'));
			var	showred=($("#zone3").is(':checked'));
			 
			 if(Zone1Coordinates=='')
				Zone1.DrawdefaultArea(showgreen);
			 else 
			  Zone1.DrawZone(showgreen,Zone1Coordinates); 
				
			 if(Zone2Coordinates=='')
				Zone2.DrawdefaultArea(showblue);
			 else 
			   
				 Zone2.DrawZone(showblue,Zone2Coordinates);
				
			 if(Zone3Coordinates=='')
				Zone3.DrawdefaultArea(showred);
			 else 
				Zone3.DrawZone(showred,Zone3Coordinates);
				
		 }
		</script>
		<script type="text/javascript">
 function saveZones() {
	var restaurant_location= "<? echo $_GET["address"]; ?> <? echo $_GET["city"]; ?> <? echo $_GET["state"]; ?>";
	
	var zone1=0;
	var zone1_delivery_charges=$("#zone1_delivery_charges").val();
	var zone1_min_total=$("#zone1_min_total").val();
	
	var zone2=0;
	var zone2_delivery_charges=$("#zone2_delivery_charges").val();
	var zone2_min_total=$("#zone2_min_total").val();

	var zone3=0;
	var zone3_delivery_charges=$("#zone3_delivery_charges").val();
	var zone3_min_total=$("#zone3_min_total").val();
	
	var zone1_coordinates,zone2_coordinates,zone3_coordinates;
 	 if($("#zone1").is(':checked')){
		 zone1=1;
		   if( isNaN(parseFloat(zone1_delivery_charges)) ){alert("please enter zone 1 delivery charges");return false;}
		     if( isNaN(parseFloat(zone1_min_total)) ){alert("please enter zone 1 minimum total ");return false;}
			 
		 }
		 
		  if($("#zone2").is(':checked')){
			 zone2=1;
		   if(   isNaN(parseFloat(zone2_delivery_charges) )){alert("please enter zone 2 delivery charges");return false;}
		     if(  isNaN(parseFloat(zone2_min_total)) ){alert("please enter zone 2 minimum total");return false;}
			 
		 }
	 
	  
		  if($("#zone3").is(':checked')){
			   zone3=1;
			 
		   if( isNaN(parseFloat(zone3_delivery_charges)) ){alert("please enter zone 3 delivery charges");return false;}
		     if( isNaN(parseFloat(zone3_min_total))){alert("please enter zone 3 minimum total");return false;}
			 
		 }
		var zone1_coordinates=Zone1.Coordinates();
		var zone2_coordinates=Zone2.Coordinates();
		var zone3_coordinates=Zone3.Coordinates();

		$.post("<? echo $_SERVER["PHP_SELF"]; ?>?savedata=1", 
			{
				zone1:zone1
				,zone1_delivery_charges:zone1_delivery_charges
				,zone1_min_total:zone1_min_total
				,zone2:zone2
				,zone2_delivery_charges:zone2_delivery_charges
				,zone2_min_total:zone2_min_total
				,zone3:zone3
				,zone3_delivery_charges:zone3_delivery_charges
				,zone3_min_total:zone3_min_total
				,zone1_coordinates:zone1_coordinates
				,zone2_coordinates:zone2_coordinates
				,zone3_coordinates:zone3_coordinates
				,restaurant_location:restaurant_location
			}
			,function(data){
				$("#hzone1").val(zone1);$("#hzone1_delivery_charges").val(zone1_delivery_charges); $("#hzone1_min_total").val(zone1_min_total); $("#hzone1_coordinates").val(zone1_coordinates);
				$("#hzone2").val(zone2);$("#hzone2_delivery_charges").val(zone2_delivery_charges); $("#hzone2_min_total").val(zone2_min_total); $("#hzone2_coordinates").val(zone2_coordinates);
				$("#hzone3").val(zone3);$("#hzone3_delivery_charges").val(zone3_delivery_charges); $("#hzone3_min_total").val(zone3_min_total); $("#hzone3_coordinates").val(zone3_coordinates);
				alert("zones saved");
			}
		);
	 }
		
$(document).ready(function() {
	 	geocoder = new GClientGeocoder();
	var restaurant_location="<? echo $_GET["address"]; ?> <? echo $_GET["city"]; ?> <? echo $_GET["state"]; ?>";
	geocoder.getLocations(restaurant_location, function (response) {
			if (!response || response.Status.code != 200)
			{
				alert("Sorry, we were unable to recognize the resturant address");
				return false; 
			}
			
			else
			{
				//location1 = {lat: response.Placemark[0].Point.coordinates[1], lon: response.Placemark[0].Point.coordinates[0], address: response.Placemark[0].address};
			 	//$("#lnkdelivery_zones").attr('href','ajax.php?mod=resturant&item=delivery_zone&latitude='+location1.lat +'&longitude='+location1.lon)
			}
		});
		
	
	});
		
	 
		
    </script>
    <script type="text/javascript"> 
/* Developed by: Abhinay Rathore [web3o.blogspot.com] */  
//Global variables 
var global = this; 
var map; 

var PolygonMarkers = []; //Array for Map Markers 
var PolygonPoints = []; //Array for Polygon Node Markers 
var bounds = new GLatLngBounds; //Polygon Bounds 
var Polygon; //Polygon overlay object 
var polygon_resizing = false; //To track Polygon Resizing 

//Polygon Marker/Node icons 
var redpin = new GIcon(); //Red Pushpin Icon 
redpin.image = "http://maps.google.com/mapfiles/ms/icons/red-pushpin.png"; 
redpin.iconSize = new GSize(32, 32); 
redpin.iconAnchor = new GPoint(10, 32); 
var bluepin = new GIcon(); //Blue Pushpin Icon 
bluepin.image = "http://maps.google.com/mapfiles/ms/icons/blue-pushpin.png"; 
bluepin.iconSize = new GSize(32, 32); 
bluepin.iconAnchor = new GPoint(10, 32); 

function initializeMap(latitude,longitude) { //Initialize Google Map 
    if (GBrowserIsCompatible()) { 
        map = new GMap2(document.getElementById("map_canvas")); //New GMap object 
		console.log(latitude); console.log(longitude);
		var center = new GLatLng(latitude, longitude);
		
        map.setCenter(center, 13); 
	 	var marker = new GMarker(center, {draggable: false});
		map.addOverlay(marker);
        var ui = new GMapUIOptions(); //Map UI options 
        ui.maptypes = { normal:true, satellite:true, hybrid:true, physical:false } 
        ui.zoom = {scrollwheel:true, doubleclick:true}; 
        ui.controls = { largemapcontrol3d:true, maptypecontrol:true, scalecontrol:true }; 
        map.setUI(ui); //Set Map UI options 

        //Add Shift+Click event to add Polygon markers 
        GEvent.addListener(map, "click", function(overlay, point, overlaypoint) { 
            var p = (overlaypoint) ? overlaypoint : point; 
            //Add polygon marker if overlay is not an existing marker and shift key is pressed 
            if (global.shiftKey && !checkPolygonMarkers(overlay)) { addMarker(p); } 
        });
    } 
} 

// Adds a new Polygon boundary marker 
function addMarker(point) { 
 
    var markerOptions = { icon: bluepin, draggable: true }; 
    var marker = new GMarker(point, markerOptions); 
    PolygonMarkers.push(marker); //Add marker to PolygonMarkers array 
    map.addOverlay(marker); //Add marker on the map 
    GEvent.addListener(marker,'dragstart',function(){ //Add drag start event 
        marker.setImage(redpin.image); 
        polygon_resizing = true; 
    }); 
    GEvent.addListener(marker,'drag',function(){ drawPolygon(); }); //Add drag event 
    GEvent.addListener(marker,'dragend',function(){   //Add drag end event 
        marker.setImage(bluepin.image); 
        polygon_resizing = false; 
        drawPolygon(); 
        fitPolygon(); 
    }); 
    GEvent.addListener(marker,'click',function(point) { //Add Ctrl+Click event to remove marker 
        if (global.ctrlKey) { removeMarker(point); } 
    }); 
    drawPolygon(); 

    //If more then 2 nodes then automatically fit the polygon 
    if(PolygonMarkers.length > 2) fitPolygon(); 
} 

// Removes a Polygon boundary marker 
function removeMarker(point) { 
    if(PolygonMarkers.length == 1){ //Only one marker in the array 
        map.removeOverlay(PolygonMarkers[0]); 
        map.removeOverlay(PolygonMarkers[0]); 
        PolygonMarkers = []; 
        if(Polygon){map.removeOverlay(Polygon)}; 
    } 
      else //More then one marker 
      { 
        var RemoveIndex = -1; 
        var Remove; 
        //Search for clicked Marker in PolygonMarkers Array 
        for(var m=0; m<PolygonMarkers.length; m++) 
        { 
            if(PolygonMarkers[m].getPoint().equals(point)) 
            { 
                RemoveIndex = m; Remove = PolygonMarkers[m] 
                break; 
            } 
        } 
        //Shift Array elemeents to left 
        for(var n=RemoveIndex; n<PolygonMarkers.length-1; n++) 
        { 
            PolygonMarkers[n] = PolygonMarkers[n+1]; 
        } 
        PolygonMarkers.length = PolygonMarkers.length-1 //Decrease Array length by 1 
        map.removeOverlay(Remove); //Remove Marker 
        drawPolygon(); //Redraw Polygon 
      } 
} 

//Draw Polygon from the PolygonMarkers Array 
function drawPolygon() 
{ 
    PolygonPoints.length=0; 
    for(var m=0; m<PolygonMarkers.length; m++) 
    { 
        PolygonPoints.push(PolygonMarkers[m].getPoint()); //Add Markers to PolygonPoints node array 
    } 
    //Add first marker in the end to close the Polygon 
    PolygonPoints.push(PolygonMarkers[0].getPoint()); 
    if(Polygon){ map.removeOverlay(Polygon); } //Remove existing Polygon from Map 
    var fillColor = (polygon_resizing) ? 'red' : 'blue'; //Set Polygon Fill Color 
    Polygon = new GPolygon(PolygonPoints, '#FF0000', 2, 1, fillColor, 0.2); //New GPolygon object 
    map.addOverlay(Polygon); //Add Polygon to the Map 
	var rows = [];
	$("cords").value = "";
	var len = PolygonPoints.length||0;
	for(var i=0; i<len; i++){
		 rows.push(PolygonPoints[i].y.toFixed(6)+", "+PolygonPoints[i].x.toFixed(6));
		}
	 $("#cords").html(rows.join('\n'));
	 
	 if(Polygon.containsLatLng(new GLatLng(location1.lat, location1.lon))){
		 $("#userVerification").html("Restaurant is inside the polygon region");
		$("#userVerification").addClass("msg_done");
		 $("#userVerification").removeClass("msg_error");
	 }else{
		 $("#userVerification").html("Restaurant is out inside the polygon region");
		 $("#userVerification").addClass("msg_error");
		 $("#userVerification").removeClass("msg_done");
		 
		 }

	  
 
	  
    //TO DO: Function Call triggered after Polygon is drawn 
} 

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
//Fits the Map to Polygon bounds 
function fitPolygon(){ 
    bounds = Polygon.getBounds(); 
    map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds)); 
} 
//check is the marker is a polygon boundary marker 
function checkPolygonMarkers(marker) { 
    var flag = false; 
    for (var m = 0; m < PolygonMarkers.length; m++) { 
        if (marker == PolygonMarkers[m]) 
        { flag = true; break; } 
    } 
    return flag; 
} 

//////////////////[ Key down event handler ]///////////////////// 
//Event handler class to attach events 
var EventUtil = { 
      addHandler: function(element, type, handler){ 
            if (element.addEventListener){ 
                    element.addEventListener(type, handler, false); 
            } else if (element.attachEvent){ 
                    element.attachEvent("on" + type, handler); 
            } else { 
                    element["on" + type] = handler; 
            } 
      } 
}; 

// Attach Key down/up events to document 
EventUtil.addHandler(document, "keydown", function(event){keyDownHandler(event)}); 
EventUtil.addHandler(document, "keyup", function(event){keyUpHandler(event)}); 

//Checks for shift and Ctrl key press 
function keyDownHandler(e) 
{ 
      if (!e) var e = window.event; 
      var target = (!e.target) ? e.srcElement : e.target; 
      if (e.keyCode == 16 && !global.shiftKey) { //Shift Key 
            global.shiftKey = true; 
      } 
      if (e.keyCode == 17 && !global.ctrlKey) { //Ctrl Key 
            global.ctrlKey = true; 
      } 
} 
//Checks for shift and Ctrl key release 
function keyUpHandler(e){ 
      if (!e) var e = window.event; 
      if (e.keyCode == 16 && global.shiftKey) { //Shift Key 
            global.shiftKey = false; 
      } 
      if (e.keyCode == 17 && global.ctrlKey) { //Ctrl Key 
            global.ctrlKey = false; 
      } 
} 
</script>
	</body>
</html>
