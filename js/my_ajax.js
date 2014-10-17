	function makeRequest(url,component) {
		
	var ran_number = Math.random()*4; 
	url=url+'&ran='+ran_number;	
		
		
		//alert(url);
      		var http_request = false;
       // Mozilla, Safari, ...
	   if (window.XMLHttpRequest) { 
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');
             }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!http_request) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        http_request.onreadystatechange = function() { alertContents(http_request,component); };
 		http_request.open('GET', url, true);
        http_request.send(null);

    }

    function alertContents(http_request,component) {
		var com = component;
		if (http_request.readyState == 1) {

				//document.getElementById(com).innerHTML = "Loading...";
			
		}
		//alert(http_request.responseText);
	   if (http_request.readyState == 4) {
            if (http_request.status == 200) {
                document.getElementById(com).innerHTML = http_request.responseText;
            } else {
               
				alert('There was a problem with the request.');
            }
        }else{
			
			//	document.getElementById(com).innerHTML = "Loading...";
			
			
		}

    }