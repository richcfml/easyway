<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<img src="images/flickr.png" alt="Flickr" title="Flickr" style="cursor: hand; cursor: pointer;" />
&nbsp;&nbsp;
<img src="images/facebook.png" alt="Facebook" title="Facebook" style="cursor: hand; cursor: pointer;"  onclick="FB.login(null, {scope: 'user_photos'});" />
<br /><br />
<div id="fbLogin">
<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() 
{
	FB.init
	({
		appId      : '597714500283054', //569304429756200
		status     : false, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		xfbml      : true  // parse XFBML
	});
	
	FB.Event.subscribe('auth.statusChange', function(response) 
	{
		if (response.status === 'connected') 
		{
			checkAssociation();
		} 
		else if (response.status === 'not_authorized') 
		{
			FB.login(function(response) 
			{
				if (response.authResponse) // connected
				{
					checkAssociation();
				} 
				else 
				{
					// cancelled
				}
			});
		} 
		else 
		{
			FB.login(function(response) 
			{
				if (response.authResponse) // connected
				{
					checkAssociation();
				} 
				else 
				{
					// cancelled
				}
			});
		}
	});
};

// Load the SDK asynchronously
(function(d)
{
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
 }
 (document));

function checkAssociation() 
{
	
	FB.api('/me/albums', function(response) 
	{
		console.log(response);
		mCount = 0;
		mHTML = "<table style='width: 100%; font-family: Arial; size: 14px;' border='0' cellpadding='0' cellspacing='0'><tr style='height: 40px;'><td colspan='3'><span style='font-size: 16px; font-weight: bold; color: maroon;'>Select Album</span><input type='hidden' id='hdnAlbumID' /></td><tr style='height: 40px;'>";
		response.data.forEach(function(pRow) 
		{
			if ((mCount>0) && (mCount%3==0))
			{
				mHTML = mHTML+"</tr><tr style='height: 40px;'>";
			}
			mHTML = mHTML+"<td style='width: 33%;'><input type='radio' onclick='radioClickedAlbum("+pRow.id+");' name='albumlist' id='rbAlbum"+mCount+"' name='rbAlbum"+mCount+"' value='"+pRow.id+"' />"+pRow.name+"</td>";
			mCount++;
		});
		if (mCount==0)
		{
			mHTML = mHTML+"<td colspan='3'>No Album found.</td></tr></table>";
		}
		else
		{
			mHTML = mHTML+"</tr><tr style='height: 40px;'><td colspan='3'><input onclick='goClickAlbum();' type='button' id='btnGo' name='btnGo' value='Go' size='20' /></td></tr></table>";
		}
		$("#dvImages").html(mHTML);
	}, {scope: 'user_photos'});
	
	/* Photos by Album
	FB.api('/276248084549/photos', function(response) 
	{
		console.log(response);
	}, {scope: 'user_photos'});
	*/
}

function radioClickedAlbum(pAlbumID)
{
	$("#hdnAlbumID").val(pAlbumID);
}

function goClickAlbum()
{
	mAlbumID = 	$("#hdnAlbumID").val();
	if ($.trim(mAlbumID)!="")
	{
		FB.api('/'+mAlbumID+'/photos', function(response) 
		{
			mCount = 0;
			mHTML = "<br /><br /><table style='width: 100%; font-family: Arial; size: 14px;' border='0' cellpadding='0' cellspacing='0'><tr style='height: 40px;'><td colspan='6'><span style='font-size: 16px; font-weight: bold; color: maroon;'>Select Photo</span><input type='hidden' id='hdnPhotoID' /><input type='hidden' id='hdnPhotoSource' /></td><tr style='height: 40px;'>";
			response.data.forEach(function(pRow) 
			{
				if ((mCount>0) && (mCount%2==0))
				{
					mHTML = mHTML+"</tr><tr style='height: 15px;'><td colspan='6'></td></tr><tr'>";
				}
				mHTML = mHTML+"<td style='width: 1%;'></td><td style='width: 4%;' valign='top'><input type='radio' onclick='radioClickedPhoto("+pRow.id+", \""+pRow.source+"\");' name='photolist' id='rbPhoto"+mCount+"' name='rbPhoto"+mCount+"' value='"+pRow.id+"' /></td><td style='width: 45%'><img width='288px' height='168px' src='"+pRow.source+"' alt='"+pRow.Name+"' /></td>";
				mCount++;
			});
			if (mCount==0)
			{
				mHTML = mHTML+"<td colspan='6'>No Photo found.</td></tr></table>";
			}
			else
			{
				mHTML = mHTML+"</tr><tr style='height: 40px;'><td colspan='6'><input onclick='goClickPhoto();' type='button' id='btnGoPhoto' name='btnGoPhoto' value='Submit' size='40' /></td></tr></table>";
			}
			$("#dvImages").append(mHTML);
		}, {scope: 'user_photos'});
	}
	else
	{
		alert('No Album selected.');
	}
}

function radioClickedPhoto(pPhotoID, pPhotoSource)
{
	$("#hdnPhotoID").val(pPhotoID);
	$("#hdnPhotoSource").val(pPhotoSource);
}

function goClickPhoto()
{
	mPhotoID = 	$("#hdnPhotoID").val();
	if ($.trim(mAlbumID)!="")
	{
		
	}
	else
	{
		alert('No Photo selected.');
	}
}

</script>
<!--<img src="../images/fb.png" style="cursor: hand; cursor: pointer;" align="Login with Facebook" title="Login with Facebook" onclick="FB.login(null, {scope: 'user_photos'});"/>-->
</div>
<div id="dvImages"></div>