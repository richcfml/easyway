﻿function ietruebody(){return document.compatMode&&document.compatMode!="BackCompat"?document.documentElement:document.body}function ddrivetip(e,t,n){if(ns6||ie){if(typeof n!="undefined")tipobj.style.width=n+"px";if(typeof t!="undefined"&&t!="")tipobj.style.backgroundColor=t;tipobj.innerHTML=e;enabletip=true;return false}}function positiontip(e){if(enabletip){var t=ns6?e.pageX:event.clientX+ietruebody().scrollLeft;var n=ns6?e.pageY:event.clientY+ietruebody().scrollTop;var r=ie&&!window.opera?ietruebody().clientWidth-event.clientX-offsetxpoint:window.innerWidth-e.clientX-offsetxpoint-20;var i=ie&&!window.opera?ietruebody().clientHeight-event.clientY-offsetypoint:window.innerHeight-e.clientY-offsetypoint-20;var s=offsetxpoint<0?offsetxpoint*-1:-1e3;if(r<tipobj.offsetWidth)tipobj.style.left=ie?ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px":window.pageXOffset+e.clientX-tipobj.offsetWidth+"px";else if(t<s)tipobj.style.left="5px";else tipobj.style.left=t+offsetxpoint+"px";if(i<tipobj.offsetHeight)tipobj.style.top=ie?ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px":window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px";else tipobj.style.top=n+offsetypoint+"px";tipobj.style.visibility="visible"}}function hideddrivetip(){if(ns6||ie){enabletip=false;tipobj.style.visibility="hidden";tipobj.style.left="-1000px";tipobj.style.backgroundColor="";tipobj.style.width=""}}var offsetxpoint=-60;var offsetypoint=20;var ie=document.all;var ns6=document.getElementById&&!document.all;var enabletip=false;if(ie||ns6)var tipobj=document.all?document.all["dhtmltooltip"]:document.getElementById?document.getElementById("dhtmltooltip"):"";document.onmousemove=positiontip