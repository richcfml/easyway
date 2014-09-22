<?php
ini_set('display_errors',1);
require("includes/config.php");
?>

<style>
    #map_canvas {
        width:930px;
        height:630px;

    }
</style>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

<script type="text/javascript" language="javascript">
	$("#txtRestIDMap").val(window.parent.$("#txtRestaurantID").val());
	function initialize() 
	{
		if ((typeof(geocoder) == "undefined") || (geocoder === null))
		{
			geocoder = new google.maps.Geocoder();
		}
	}
	
	function saveZones() {

        var zone1 = 0;
        var zone1_delivery_charges = $("#zone1_delivery_charges").val();
        var zone1_min_total = $("#zone1_min_total").val();

        var zone2 = 0;
        var zone2_delivery_charges = $("#zone2_delivery_charges").val();
        var zone2_min_total = $("#zone2_min_total").val();

        var zone3 = 0;
        var zone3_delivery_charges = $("#zone3_delivery_charges").val();
        var zone3_min_total = $("#zone3_min_total").val();

		var mIDMp = $("#txtRestIDMap").val();
		
        var zone1_coordinates, zone2_coordinates, zone3_coordinates;
        if ($("#zone1").is(':checked')) {
            zone1 = 1;
            if (isNaN(parseFloat(zone1_delivery_charges))) {
                alert("please enter zone 1 delivery charges");
                return false;
            }
            if (isNaN(parseFloat(zone1_min_total))) {
                alert("please enter zone 1 minimum total ");
                return false;
            }

        }

        if ($("#zone2").is(':checked')) {
            zone2 = 1;
            if (isNaN(parseFloat(zone2_delivery_charges))) {
                alert("please enter zone 2 delivery charges");
                return false;
            }
            if (isNaN(parseFloat(zone2_min_total))) {
                alert("please enter zone 2 minimum total");
                return false;
            }

        }


        if ($("#zone3").is(':checked')) {
            zone3 = 1;

            if (isNaN(parseFloat(zone3_delivery_charges))) {
                alert("please enter zone 3 delivery charges");
                return false;
            }
            if (isNaN(parseFloat(zone3_min_total))) {
                alert("please enter zone 3 minimum total");
                return false;
            }

        }
		
		$("#zone1_coordinates").val(Zone1.Coordinates());
        $("#zone2_coordinates").val(Zone2.Coordinates());
        $("#zone3_coordinates").val(Zone3.Coordinates());
       
	   	var zone1_coordinates = Zone1.Coordinates();
        var zone2_coordinates = Zone2.Coordinates();
        var zone3_coordinates = Zone3.Coordinates();
	   
	   
		   $.ajax({
			url:"ajax.php?call=delivery_zone",
			type:"POST",
			data: {id: mIDMp,zone1: zone1, zone1_delivery_charges: zone1_delivery_charges, zone1_min_total: zone1_min_total, zone2: zone2, zone2_delivery_charges: zone2_delivery_charges, zone2_min_total: zone2_min_total, zone3: zone3, zone3_delivery_charges: zone3_delivery_charges, zone3_min_total: zone3_min_total, zone1_coordinates: zone1_coordinates, zone2_coordinates: zone2_coordinates, zone3_coordinates: zone3_coordinates},
			success:function(data)
			{
				if ($.isNumeric(data))
				{
					if (data>0)
					{
						$("#txtRestIDMap").val(data);
						window.parent.$("#txtRestaurantID").val(data);
						alert('Changes Saved');
					}
					else
					{
						alert("Error occurred.");
					}
				}
				else
				{
					alert("Error occurred.");
				}
			},
			error:function(data)
			{
				alert("Error occurred.");
			}
		});
	   
	   	
	   
      	return true;
    }
</script>
<body  onLoad="initialize();">
<form id="frmMap" name="frMap" method="post" action="">
<script src="js/checkdeliveryzones.js" type="text/javascript"></script>
<div style="float:right">
    <img id="close" src="images/closelabel.gif" />
	<input type="hidden" id="txtRestIDMap" name="txtRestIDMap"/>
</div>
<div style="clear:both"></div>
<div style="border:1px solid;width:100%;margin-bottom:5px;">

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
        <br/>
        <input type="button" onClick="saveZones()" value="Save Zones" id="btnSaveZ" name="btnSaveZ" />
    </div>
    <div style="clear:both"></div>
</div>

<div id="map_canvas"></div>

<script type="text/javascript">

    $(function() {
	   var restaurant_location = '<?=$_GET["address"]." ".$_GET["city"]." ".$_GET["state"]?>';
		
        $("#restlocation").html(restaurant_location)
        $("#map_canvas").html('<div class="msg_warning"><img src="images/loading.gif">Please wait loading map</div>');
        $("#close").click(function() {
			window.parent.$("#txtRestaurantID").val($("#txtRestIDMap").val());
            $("#facebox_overlay").click();
        });

		
        $("#zone1_delivery_charges").val("0");
        $("#zone1_min_total").val("0");

        $("#zone2_delivery_charges").val("0");
        $("#zone2_min_total").val("0");

        $("#zone3_delivery_charges").val("0");
        $("#zone3_min_total").val("0");
		
		
        var zones = [];
        var restaurantlocation = "";
        var showgreen = ($("#zone1").is(':checked'));
        var showblue = ($("#zone2").is(':checked'));
        var showred = ($("#zone3").is(':checked'));

        geocoder.geocode({'address': restaurant_location}, function(results, status) {

            if (status !== google.maps.GeocoderStatus.OK) {
                alert("Sorry, we were unable to recognize the resturant address");
                return false;
            } else {
                var position = results[0].geometry.location;
                var restaurantlocation = new google.maps.LatLng(parseFloat(position.lat()), parseFloat(position.lng()));

                var Zone1Coordinates = "";
                var Zone2Coordinates = "";
                var Zone3Coordinates = "";

                var canvas = document.getElementById("map_canvas");

                var defaultZone1;
                var defaultZone2;
                var defaultZone3;
                if (Zone1Coordinates == '')
                    defaultZone1 = true;
                else
                    defaultZone1 = false;

                if (Zone2Coordinates == '')
                    defaultZone2 = true;
                else
                    defaultZone2 = false;

                if (Zone3Coordinates == '')
                    defaultZone3 = true;
                else
                    defaultZone3 = false;

                var position = restaurantlocation;
                var zoom = 13;
                var mapTypeId = google.maps.MapTypeId.ROADMAP;
                map(canvas, position, zoom, mapTypeId);

                Zone1 = new DeliveryZone(Zone1Coordinates, '#00CC00', 3, 1, '#00A333', 0.2, 0.02, position);
                Zone2 = new DeliveryZone(Zone2Coordinates, '#003595', 3, 1, '#000088', 0.2, 0.025, position);
                Zone3 = new DeliveryZone(Zone3Coordinates, '#f33f00', 3, 1, '#ffaa00', 0.2, 0.03, position);
				console.log(Zone1);
                Zone1.drawZone(showgreen, defaultZone1);
                Zone2.drawZone(showblue, defaultZone2);
                Zone3.drawZone(showred, defaultZone3);
            }
        });

        $(".zonehaldler").click(function() {
            show = ($(this).is(':checked'));
            var zone = $(this).attr('zone');
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

        $("#resetzone").change(function() {
            if ($(this).val() == 0)
                return false;
            var zone = $(this).val();
            $(this).val(0);
            if (!$("#zone" + zone).is(':checked')) {
                alert("please select zone " + zone);
                return false;
            }
            ;
            if (!confirm('Are you sure you want to reset zone ' + zone + '?'))
                return false;

            switch (zone) {
                case '1':
                    Zone1.drawZone(showgreen, true);
                    break;
                case '2':
                    Zone2.drawZone(showgreen, true);
                    break;
                case '3':
                    Zone3.drawZone(showgreen, true);
                    break;
                default:
                    break;
            }

        });

    });
</script>
</form>
</body>