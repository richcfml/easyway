<script language="javascript">
var codeArr = new Array();
</script>
<?php 
if (isset($_GET['ssid'])) {
    $ssid = $_GET['ssid'];

    $prd_data = dbAbstract::ExecuteObject("Select * from bh_signature_sandwitch where id = ".$ssid,1);
    $item_name = stripcslashes($prd_data->item_name);

    $start_date = date("m/d",strtotime($prd_data->start_date));
    $s_fulldate = date("m/d/Y",strtotime($prd_data->start_date));
    
    $endtDate = date("m/d",strtotime($prd_data->end_date));
    $e_fulldate = date("m/d/Y",strtotime($prd_data->end_date));
	
    $description = $prd_data->item_desc;
    $description1 = $prd_data->item_desc;
    $imgSource = $prd_data->item_image;
	
    if (strtolower(substr(php_uname('s'), 0, 3))=="win")
    {
        $description1 = str_replace("<br/>", "\r\n", $description1);
        $description1 = str_replace("<br>", "\r\n", $description1);
        $description1 = str_replace("<br />", "\r\n", $description1);
    }
    else if (strtolower(php_uname('s'))=='linux')
    {
        $description1 = str_replace("<br/>", "\n", $description1);
        $description1 = str_replace("<br>", "\n", $description1);
        $description1 = str_replace("<br />", "\n", $description1);
    }
    else if (strtolower(php_uname('s'))=='unix')
    {
        $description1 = str_replace("<br/>", "\n", $description1);
        $description1 = str_replace("<br>", "\n", $description1);
        $description1 = str_replace("<br />", "\n", $description1);
    }
    else if (strtolower(substr(php_uname('s'), 0, 6))=="darwin")
    {
        $description1 = str_replace("<br/>", "\r", $description1);
        $description1 = str_replace("<br>", "\r", $description1);
        $description1 = str_replace("<br />", "\r", $description1);
    }
    else if (strtolower(substr(php_uname('s'), 0, 3))=="mac")
    {
        $description1 = str_replace("<br/>", "\r", $description1);
        $description1 = str_replace("<br>", "\r", $description1);
        $description1 = str_replace("<br />", "\r", $description1);
    }
    else
    {
        $description1=str_replace ("<br/>", "\n", $description1);
        $description1=str_replace ("<br>", "\n", $description1);
        $description1=str_replace ("<br />", "\n", $description1);
    }
	
    if (($_SESSION['admin_type'] == 'admin') || ($_SESSION['admin_type'] == 'bh'))
    {
		$description = preg_replace_callback('|@[0-9]+|', 
		function ($matches) {
			$code = str_replace('@','',$matches[0]);
			$result=dbAbstract::ExecuteObject("SELECT * FROM bh_items where ItemCode='$code' order by id desc limit 1");
			$E = '<a contenteditable="false" href="#" style="color: #0066CC;"><i></i>'.$result->ItemName.'</a>';
			?>
			<script language="javascript">
			codeArr['<?=$E?>']= '<?='@'.$result->ItemCode?>';
			</script>
			<?php
			return $E;
		}, $description);
		/*$mSQLBH = "SELECT * FROM bh_items ORDER BY LENGTH(ItemName) DESC";
        $mResBH = dbAbstract::Execute($mSQLBH);
        
        $mPrevItem = "";
        
        while ($mRowBH = dbAbstract::returnObject($mResBH,1))
        {
	        if (strpos($description, $mRowBH->ItemName)!==FALSE)
            {
                if ($mPrevItem!=$mRowBH->ItemName)
                {
                    $mPrevItem = $mRowBH->ItemName;
                    $description = str_replace($mRowBH->ItemName, "<a contentEditable='false' href='#' style='color: #0066CC;'><i></i>".$mRowBH->ItemName."</a>" ,$description);
                }
            }
        }*/
    }
    $size = getimagesize("./images/signaturesandwich/". $imgSource);
	
	if (($size[0]>=450) || ($size[1]>=450))
	{
		$mWidth = round($size[0]/4.5);
		$mHeight = round($size[1]/4.5);
		$mScale = 4.5;
	}
	else if ((($size[0]<450) && ($size[0]>=400)) || (($size[1]<450) && ($size[1]>=400)))
	{
		$mWidth = round($size[0]/4);
		$mHeight = round($size[1]/4);
		$mScale = 4;
	}
	else if ((($size[0]<400) && ($size[0]>=300)) || (($size[1]<400) && ($size[1]>=300)))
	{
		$mWidth = round($size[0]/3.5);
		$mHeight = round($size[1]/3.5);
		$mScale = 3.5;
	}
	else if ((($size[0]<300) && ($size[0]>=250)) || (($size[1]<300) && ($size[1]>=250)))
	{
		$mWidth = round($size[0]/3);
		$mHeight = round($size[1]/3);
		$mScale = 3;
	}
	else if ((($size[0]<250) && ($size[0]>=220)) || (($size[1]<250) && ($size[1]>=220)))
	{
		$mWidth = round($size[0]/2.5);
		$mHeight = round($size[1]/2.5);
		$mScale = 2.5;
	}																							
	else if ((($size[0]<220) && ($size[0]>=190)) || (($size[1]<220) && ($size[1]>=190)))
	{
		$mWidth = round($size[0]/2);
		$mHeight = round($size[1]/2);
		$mScale = 2;
	}
	else if ((($size[0]<190) && ($size[0]>=120)) || (($size[1]<190) && ($size[1]>=105)))
	{
		$mWidth = round($size[0]/1.5);
		$mHeight = round($size[1]/1.5);
		$mScale = 1.5;
	}
	else
	{
		$mWidth = round($size[0]/1);
		$mHeight = round($size[1]/1);
		$mScale = 1;
	}
}
?>
<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") 
{
?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.6.2.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script>
    <script src= "https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<?php
}
else
{
?>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script>
    <script src= "http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<?php
}
?>
<link rel="stylesheet" type="text/css" href="css/new_menu.css<?php echo $jsParameter;?>">
<script src="../js/mask.js" type="text/javascript"></script>
<script src="js/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="css/jquery.Jcrop.css">
<script src="js/jquery.Jcrop.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/fancybox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/fancy.css">
<script src="js/block.js" type="text/javascript"></script>

<style type="text/css">
    .BodyContainer
    {
        height:1050px;
        background-repeat: no-repeat !important;
    }
    #inner_div
    {
        height:700px;
    }
    .add_area_div
    {
        height:500px;
    }
    .sucessMessage{
            color: green;
            font-size: 14px;
            margin-left: 70px;
            margin-top: 20px;
            display:block !important;
    }
    .errorMessage{
            color: red;
            font-size: 14px;
            margin-left: 70px;
            margin-top: 20px;
            display:block !important;
    }
</style>
<script type="text/javascript">
    $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
    $(document).ready(function()
    {  
		$("#item_name").change(function(){
			$("#ss_item_name").html($(this).val());
		});
		
		$("#product_description1").keydown(function(){
			$(".ss_prodDescription").html($(this).text());
		});
		
        $( "#item_name" ).blur(function() {
            $('#item_name').attr('placeholder','Item Name');
        });

        $( "#item_name" ).focus(function() {
            $('#item_name').attr('placeholder','');
        });
        
        $("#start_date").change(function(){
			//ss_startdate ss_enddate
			var date = new Date($(this).val());
			var month = parseInt(date.getMonth())+1;
			month = ((month < 10)? '0'+month:month);
			var day = ((date.getDate() < 10)? '0'+date.getDate():date.getDate());
			$("#ss_startdate").html(month+'/'+day);
		});
		
        $("#end_date").change(function(){
			//ss_startdate ss_enddate
			var date = new Date($(this).val());
			var month = parseInt(date.getMonth())+1;
			month = ((month < 10)? '0'+month:month);
			var day = ((date.getDate() < 10)? '0'+date.getDate():date.getDate());
			$("#ss_enddate").html(month+'/'+day);
		});
		
		$( "#start_date" ).blur(function() {
			$('#start_date').attr('placeholder','Start Date');
        });
        $( "#start_date" ).focus(function() {
            $('#start_date').attr('placeholder','');
        });
        
        $( "#end_date" ).blur(function() {
            $('#end_date').attr('placeholder','End Date');
        });
        $( "#end_date" ).focus(function() {
            $('#end_date').attr('placeholder','');
        });

        $( "#start_date" ).datepicker({minDate: 0});
        $( "#end_date" ).datepicker({minDate: 0});
        $("#add_Signature_sandwitch").unbind("submit").bind('submit', function(e){
                e.preventDefault();
                var start_date = new Date($("#start_date").val());
                var end_date = new Date($("#end_date").val());
                if($("#item_name").val()=="")
                {
                    $("#item_name").css('background-color','#F99');
                    $("#item_name").css('border','1px solid #D92353');
                    return false;
                }
                else
                {
                    $("#item_name").css('background-color','');
                    $("#item_name").css('border','');
                }
                
                if($("#start_date").val()=="")
                {
                    $("#start_date").css('background-color','#F99');
                    $("#start_date").css('border','1px solid #D92353');
                    return false;
                }
                else
                {
                    $("#start_date").css('background-color','');
                    $("#start_date").css('border','');
                }
                
                if($("#end_date").val()=="")
                {
                    $("#end_date").css('background-color','#F99');
                    $("#end_date").css('border','1px solid #D92353');
                    return false;
                }
                else
                {
                    $("#end_date").css('background-color','');
                    $("#end_date").css('border','');
                }
                if(start_date > end_date)
                {   
                    $("#end_date,#start_date").css('background-color','#F99');
                    $("#end_date,#start_date").css('border','1px solid #D92353');
                    $("#sucessMessage").text('Start date must be less than end date').addClass('errorMessage').removeClass('sucessMessage')
                    return false;
                }
                else
                {
                    $("#end_date,#start_date").css('background-color','');
                    $("#end_date,#start_date").css('border','');
                    $("#sucessMessage").text('')
                }
                var ext = $("#item_img").attr('src').split("/").pop(-1);
                ext = ext.split("?")[0];
                $.ajax({
                    type:"POST",
                    url: "admin_contents/signature_sandwitch/ajax.php?add_item=1&ext="+ext,
                    data: $("#add_Signature_sandwitch").serialize(),
                    success: function(data) {
						id = parseInt(<?=((isset($_GET['ssid']) && $_GET['ssid'] > 0)? $_GET['ssid']:0)?>);
						if(isNaN(data))
                        {
                            $("#sucessMessage").text('Date can not be overlap').addClass('errorMessage').removeClass('sucessMessage');
                        }
                        else if(data > 0)
                        {
							if(id > 0){
								$("#sucessMessage").text('Signature sandwitch item update successfully').addClass('sucessMessage').removeClass('errorMessage');
								$.ajax({
									type:"POST",
									url: "admin_contents/signature_sandwitch/ajax.php?ssdata=linkonly",
									data: {id:id},
									success: function(tablerow) {
										$("#ss_"+id).html(tablerow);
									}
								});
							}else{
                            	$("#sucessMessage").text('Signature sandwitch item added successfully').addClass('sucessMessage').removeClass('errorMessage');
								$.ajax({
									type:"POST",
									url: "admin_contents/signature_sandwitch/ajax.php?ssdata=row",
									data: {id:data},
									success: function(tablerow) {
										$("#tblsandwiches").append(tablerow);
									}
								});				
							}
                            $("#item_name").val('');
                            $("#product_description1").text('');
                            $("#start_date").val('');
                            $("#end_date").val('');
                            $("#show_photo").hide();
                            $("#show_photo_before").show();
                            $("#item_img").attr("src","");
                            $('#deleteimg').hide();
                            $("#cropimg").hide();
                            
                            $("#product_description1").text("Description of Item");
                            $("#product_description1").css("color", "#917591");
                                

                            
                        }
                    }
                });
        });
        $(document).ready(function(){
            $('#item_img1').Jcrop({ aspectRatio: 0, onSelect: updateCoords,maxSize: [ 500, 500 ]
            });
        });
		function isJson(str) {
			try {
				JSON.parse(str);
			} catch (e) {
				return false;
			}
			return true;
		}
        function updateCoords(c) {
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        };
        function checkCoords() {
            if (parseInt($('#w').val()))
                return true; alert('Select to crop.');
            return false;
        };
        $("#btnCancelProduct").click(function()
        {
            window.location.href = "?mod=resturant"
        });
        
        $('#deleteimg').click(function(){
            $("#show_photo").hide();
            $("#show_photo_before").show();
            $("#item_img").attr("src","");
            $('#deleteimg').hide();
            $("#cropimg").hide();
        });
        $("#upd_file_btn").die('click').live("click", function(){
            $('input[name=userfile-uploader]').click();
                    $("#userfile-uploader").die('change').live("change", function(){
                var data = new FormData();
                jQuery.each($('#userfile-uploader')[0].files, function(i, file) {
                    data.append('file-'+i, file);
                });
                var imageSize = document.getElementById('userfile-uploader').files[0].size;
                if(imageSize > 1048576)
                {

                    $("#sizeErrorMsg").css('color','red');
                }
                else
                {
                    $.ajax({
                        type:"post",
                        url: "admin_contents/signature_sandwitch/ajax.php?imgupload=1",
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: function(data) {
                            if(data!='')
                            {
                                mTmpArr = data.split("~");
                                mImageSrc = mTmpArr[0];
                                mWidth = mTmpArr[1];
                                mHeight = mTmpArr[2];

                                $("#show_photo").show();
                                $("#show_photo_before").hide();
                                $("#item_img").attr("src","../c_panel/tempimages/"+mImageSrc);
                                $.fancybox.close();
                                $("#deleteimg").show();
                                $("#cropimg").show();
                                $("#cropimg").css('margin-left','10px');
                                $('.form-progress-wrapper').hide();
                                $("#show_photo_before").css('opacity','1.5');
                                $("#show_photo").css('opacity','1.5');
                                $("#imgDiv").css('margin-left','75px');
                                if ($('#item_img').data('Jcrop') != null) {
                                    $('#item_img').data('Jcrop').destroy();
                                }

                                    if ((mWidth>=450) || (mHeight>=450))
                                    {
                                            $('#item_img').css("width",Math.round(mWidth/4.5)+"px");
                                            $('#item_img').css("height",Math.round(mHeight/4.5)+"px");
                                            $('#hdnScale').val('4.5');
                                    }
                                    else if (((mWidth<450) && (mWidth>=400)) || ((mHeight<450) && (mHeight>=400)))
                                    {
                                            $('#item_img').css("width",Math.round(mWidth/4)+"px");
                                            $('#item_img').css("height",Math.round(mHeight/4)+"px");;
                                            $('#hdnScale').val('4');
                                    }
                                    else if (((mWidth<400) && (mWidth>=300)) || ((mHeight<400) && (mHeight>=300)))
                                    {
                                            $('#item_img').css("width",Math.round(mWidth/3.5)+"px");
                                            $('#item_img').css("height",Math.round(mHeight/3.5)+"px");;
                                            $('#hdnScale').val('3.5');
                                    }
                                    else if (((mWidth<300) && (mWidth>=250)) || ((mHeight<300) && (mHeight>=250)))
                                    {
                                            $('#item_img').css("width",Math.round(mWidth/3)+"px");
                                            $('#item_img').css("height",Math.round(mHeight/3)+"px");
                                            $('#hdnScale').val('3');
                                    }
                                    else if (((mWidth<250) && (mWidth>=220)) || ((mHeight<250) && (mHeight>=220)))
                                    {
                                            $('#item_img').css("width",Math.round(mWidth/2.5)+"px");
                                            $('#item_img').css("height",Math.round(mHeight/2.5)+"px");
                                            $('#hdnScale').val('2.5');
                                    }																							
                                    else if (((mWidth<220) && (mWidth>=190)) || ((mHeight<220) && (mHeight>=190)))
                                    {
                                            $('#item_img').css("width",Math.round(mWidth/2)+"px");
                                            $('#item_img').css("height",Math.round(mHeight/2)+"px");
                                            $('#hdnScale').val('2');
                                    }
                                    else if (((mWidth<190) && (mWidth>=120)) || ((mHeight<190) && (mHeight>=105)))
                                    {
                                            $('#item_img').css("width",Math.round(mWidth/1.5)+"px");
                                            $('#item_img').css("height",Math.round(mHeight/1.5)+"px");
                                            $('#hdnScale').val('1.5');
                                    }
                                    else
                                    {
                                            $('#item_img').css("width",Math.round(mWidth)+"px");
                                            $('#item_img').css("height",Math.round(mHeight)+"px");
                                            $('#hdnScale').val('1');
                                    }

                                $('.jcrop-holder img').attr("src","../c_panel/tempimages/"+mImageSrc);
                                $('#item_img').Jcrop({addClass: 'jcrop-centered',aspectRatio: 1, onSelect: updateCoords,maxSize: [ 500, 500 ]});

                                $.fancybox.close();
                                $("#deleteimg").show();


                            }
                        }
                    });
                }
                $("#userfile-uploader").val("");
        });
    });
    
        $('#userfile').click(function(e){
        e.preventDefault();
        $('.uploader_div').css({
            "display":"block"
        });
        $("#sizeErrorMsg").css('color','green');
        $(".fancyTrigger").fancybox({

            }).click();
        });
        
        $("#cropimg").click(function()
        {
            if($("#x").val()!="" && $('#item_img').data('Jcrop')!=null)
            {
                var ext;
                var newDate = new Date();
                ext = $("#item_img").attr('src');
                ext = ext.split("?")[0];

                $.ajax({
                    type:"POST",
                    url: "admin_contents/signature_sandwitch/ajax.php?cropimg=1&ext="+ext+"&x="+$("#x").val()+"&y="+$("#y").val()+"&w="+$("#w").val()+"&h="+$("#h").val()+"&scale="+$("#hdnScale").val()+"&time="+newDate.getTime(),
                    data: $("#update_item_form").serialize(),
                    success: function(data) {

                        if(data!='')
                        {
                            var ext = data.split("/").pop(-1);
                            
                            $("#item_img").attr("src","../c_panel/tempimages/"+ext+"?time="+newDate.getTime());
                            $(".jcrop-holder img").attr("src","../c_panel/tempimages/"+ext+"?time="+newDate.getTime());
                            
                            $("#show_photo_before").css('opacity','1.5');
                            $("#show_photo").css('opacity','1.5');
                            $("#imgDiv").css('margin-left','');
                            $('#item_img').data('Jcrop').destroy();
                            //$('#item_img').css("height","70px");
                            //$('#item_img').css("width","70px");
                            $('#item_img').css("display","inline-block");


                        }
                    }
                });
            }
        });
        $("#userfile").change(function(event){
            $("#show_photo_before").css('opacity','0.5');
            $("#show_photo").css('opacity','0.5');
            $('.form-progress-wrapper').show();

            var data = new FormData();
            jQuery.each($('#userfile')[0].files, function(i, file) {
                data.append('file-'+i, file);
            });

            var imageSize = document.getElementById('userfile').files[0].size;
            if(imageSize > 1048576)
            {

                $("#sizeErrorMsg1").css('color','red');

            }
            else{
                $.ajax({
                    type:"post",
                    url: "admin_contents/menus/menu_ajax.php?imgupload=1",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        if(data!='')
                        {
                                                    mTmpArr = data.split("~");
                                                    mImageSrc = mTmpArr[0];
                                                    mWidth = mTmpArr[1];
                                                    mHeight = mTmpArr[2];

                            $("#show_photo").show();
                            $("#show_photo_before").hide();
                            $("#item_img").attr("src","../c_panel/tempimages/"+mImageSrc);
                            $.fancybox.close();
                            $("#deleteimg").show();
                            $("#cropimg").show();
                            $("#cropimg").css('margin-left','10px');
                            $('.form-progress-wrapper').hide();
                            $("#show_photo_before").css('opacity','1.5');
                            $("#show_photo").css('opacity','1.5');
                            $("#imgDiv").css('margin-left','75px');
                            if ($('#item_img').data('Jcrop') != null) {
                                    $('#item_img').data('Jcrop').destroy();
                                }

                                                    if ((mWidth>=450) || (mHeight>=450))
                                                    {
                                                            $('#item_img').css("width",Math.round(mWidth/4.5)+"px");
                                                            $('#item_img').css("height",Math.round(mHeight/4.5)+"px");
                                                            $('#hdnScale').val('4.5');
                                                    }
                                                    else if (((mWidth<450) && (mWidth>=400)) || ((mHeight<450) && (mHeight>=400)))
                                                    {
                                                            $('#item_img').css("width",Math.round(mWidth/4)+"px");
                                                            $('#item_img').css("height",Math.round(mHeight/4)+"px");;
                                                            $('#hdnScale').val('4');
                                                    }
                                                    else if (((mWidth<400) && (mWidth>=300)) || ((mHeight<400) && (mHeight>=300)))
                                                    {
                                                            $('#item_img').css("width",Math.round(mWidth/3.5)+"px");
                                                            $('#item_img').css("height",Math.round(mHeight/3.5)+"px");;
                                                            $('#hdnScale').val('3.5');
                                                    }
                                                    else if (((mWidth<300) && (mWidth>=250)) || ((mHeight<300) && (mHeight>=250)))
                                                    {
                                                            $('#item_img').css("width",Math.round(mWidth/3)+"px");
                                                            $('#item_img').css("height",Math.round(mHeight/3)+"px");
                                                            $('#hdnScale').val('3');
                                                    }
                                                    else if (((mWidth<250) && (mWidth>=220)) || ((mHeight<250) && (mHeight>=220)))
                                                    {
                                                            $('#item_img').css("width",Math.round(mWidth/2.5)+"px");
                                                            $('#item_img').css("height",Math.round(mHeight/2.5)+"px");
                                                            $('#hdnScale').val('2.5');
                                                    }																							
                                                    else if (((mWidth<220) && (mWidth>=190)) || ((mHeight<220) && (mHeight>=190)))
                                                    {
                                                            $('#item_img').css("width",Math.round(mWidth/2)+"px");
                                                            $('#item_img').css("height",Math.round(mHeight/2)+"px");
                                                            $('#hdnScale').val('2');
                                                    }
                                                    else if (((mWidth<190) && (mWidth>=120)) || ((mHeight<190) && (mHeight>=105)))
                                                    {
                                                            $('#item_img').css("width",Math.round(mWidth/1.5)+"px");
                                                            $('#item_img').css("height",Math.round(mHeight/1.5)+"px");
                                                            $('#hdnScale').val('1.5');
                                                    }
                                                    else
                                                    {
                                                            $('#item_img').css("width",Math.round(mWidth)+"px");
                                                            $('#item_img').css("height",Math.round(mHeight)+"px");
                                                            $('#hdnScale').val('1');
                                                    }

                            $('.jcrop-holder img').attr("src","../c_panel/tempimages/"+mImageSrc);
                            $('#item_img').Jcrop({addClass: 'jcrop-centered',aspectRatio: 1, onSelect: updateCoords,maxSize: [ 500, 500 ]

            });

                        }
                    }
                });
            }
        });
    });
    
</script>
<script type="text/javascript">
  $(document).ready(function()
  {
	  var start=/@/ig; // @ Match
	  
	  $("#product_description1").blur(function()
	  {
		  if ($.trim($("#product_description1").text())=="")
		  {
			  $("#product_description1").text("Description of Item");
			  $("#product_description1").css("color", "#917591");
		  }
	  });
	  
	  $("#product_description1").focus(function()
	  {
		  if ($.trim($("#product_description1").text())=="Description of Item")
		  {
			  $("#product_description1").text("");
			  $("#product_description1").css("color", "#000000");
		  }
	  });
	  
	  $("#product_description1").live("keydown",function(e)
	  {
		  // trap the return key being pressed
		  if (jQuery.browser.safari)
		  {
			  if (e.keyCode === 13) {
				// insert 2 br tags (if only one br tag is inserted the cursor won't go to the next line)
				document.execCommand('insertHTML', false, '<br><br>');
				// prevent the default behaviour of return key pressed
				return false;
			  }
		  }
		});
	  
	  $("#product_description1").live("keyup",function(e)
	  {
		  var search = "";
		  var content=$(this).text(); //Content Box Data
		  if (content=="")
		  {
			  $("#display").hide();
			  $("#product_description").val("");
			  return;
		  }
		  
		  search = content.substring(content.indexOf("@"));
		  
		  if (search.indexOf(" ")>=0)
		  {   
			  search = search.substring(0, search.indexOf(" "));
		  }
		  
		  if (search.indexOf(",")>=0)
		  {   
			  search = search.substring(0, search.indexOf(","));
		  }
		  
		  search = $.trim(search);
		  
		  var go= content.match(start); //Content Matching @
  
		  var dataString = 'searchword='+ search;
		  
		  if (go)
		  {
			  if(go.length>0)
			  {
				  if (e.keyCode==32)
				  {
					  if ((search!="") && (search!="@"))
					  {
						  $("#hdnSearch").val(search);
						  setTimeout(function()
						  {
							  $.ajax({
								  type: "POST",
								  url: "admin_contents/products/ajax.php?sku1=1", // Database name search
								  data: dataString,
								  cache: false,
								  success: function(data)
								  {
									  if ($.trim(data)!="")
									  {
										  var E='<a contenteditable="false" href="#" style="color: #0066CC;"><i></i>'+data+'</a>';
										  codeArr[E]=search;
										  
										  $("#product_description1").html($("#product_description1").html().replace($("#hdnSearch").val(), E));
										  placeCaretAtEnd(document.getElementById("product_description1"));
										  mTmpHTML = removeAnchors($("#product_description2").val());
										  $("#product_description").val(mTmpHTML.replace("'", "&#39;").replace("®", "&#174;").replace("ä", "&#228;").replace("è", "&#232;").replace("ñ", "&#241;"));
										  $("#bh_item").attr('checked', true);
										  $(".ss_prodDescription").html($("#product_description1").html());
									  }
								  }
							  });
						  }, 100);
					  }
				  }
			  }
			  else
			  {
				  mTmpHTML = removeAnchors($("#product_description2").val());
				  $("#product_description").val(mTmpHTML.replace("'", "&#39;").replace("®", "&#174;").replace("ä", "&#228;").replace("è", "&#232;").replace("ñ", "&#241;"));
			  }
		  }
		  else
		  {
			  mTmpHTML = removeAnchors($("#product_description2").val());
			  $("#product_description").val(mTmpHTML.replace("'", "&#39;").replace("®", "&#174;").replace("ä", "&#228;").replace("è", "&#232;").replace("ñ", "&#241;"));
		  }
		  
		  $("#product_description2").val($("#product_description1").html());
		  for (var key in codeArr) {
			  $("#product_description2").val($("#product_description2").val().replace(key, codeArr[key]));
		  }
			
		  if ($("#product_description1").html().indexOf("<a ")<0)
		  {
			  $("#bh_item").attr('checked', false);
		  }
  
		  return false;
	  });
	  
	  function removeAnchors(pStr)
	  {
		  mTmpHTML = pStr;
		  if (mTmpHTML.indexOf("<a")!=-1)
		  {
			  mStr = mTmpHTML.substring(mTmpHTML.indexOf("<a"), mTmpHTML.indexOf("</i>") + 4);
  
			  mTmpHTML = mTmpHTML.replace(mStr, "").replace("</a>","");
			  if (mTmpHTML.indexOf("</i>")!=-1)
			  {
				  mTmpHTML = removeAnchors(mTmpHTML);
			  }
  
			  //mTmpHTML =  removeSpans(mTmpHTML);
		  }
		  return mTmpHTML;
	  }
	  
	  function removeSpans(pStr)
	  {
		  mTmpHTML = pStr;
		  if (mTmpHTML.indexOf("<span")!=-1)
		  {
			  mStr = mTmpHTML.substring(mTmpHTML.indexOf("<span"), mTmpHTML.indexOf("</span>") + 7);
  
			  mTmpHTML = mTmpHTML.replace(mStr, "");
  
			  if (mTmpHTML.indexOf("</span>")!=-1)
			  {
				  mTmpHTML = removeSpans(mTmpHTML);
			  }
		  }
		  return mTmpHTML;
	  }
	  
	  function placeCaretAtEnd(el) {
		  el.focus();
		  if (typeof window.getSelection != "undefined"
				  && typeof document.createRange != "undefined") {
			  var range = document.createRange();
			  range.selectNodeContents(el);
			  range.collapse(false);
			  var sel = window.getSelection();
			  sel.removeAllRanges();
			  sel.addRange(range);
		  } else if (typeof document.body.createTextRange != "undefined") {
			  var textRange = document.body.createTextRange();
			  textRange.moveToElementText(el);
			  textRange.collapse(false);
			  textRange.select();
		  }
	  }
  });
  </script>
<?php 
if($_POST['cropimg'])
{
    $targ_w = $targ_h = 150; $jpeg_quality = 90;
    $src = '../c_panel/tempimages/download.jpg';
    $img_r = imagecreatefromjpeg($src);
    $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
    imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
    $targ_w,$targ_h,$_POST['w'],$_POST['h']);
    header('Content-type: image/jpeg');
    imagejpeg($dst_r,null,$jpeg_quality);
    exit;
}
?>
<form method ="post" id="add_Signature_sandwitch"  enctype="multipart/form-data">
<input type="hidden" name="id" value="<?=((isset($_GET['ssid']))? $_GET['ssid']:0)?>">
<input type="hidden" name="product_description2" id="product_description2">
<div id ="main_div" class="main_div" ng-app="">
    <div id ="inner_div"> 
               
                <div class="Submenu_Heading">Add Signature Sandwitch</div>
                <div class="add_area_div">
                    <table style="width: 85%; margin: 0px;margin-left: 21px;" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="text" id="item_name" name="item_name" style="margin-left: 13%;margin-top: 30px;width:85%;padding:8px" value="<?=((isset($_GET['ssid']))? $item_name:'')?>" class="textAreaClass" placeholder="Item Name"  maxlength="40" />
                            </td>
                            <td>
                                <input type="text" name="start_date" id="start_date"  placeholder="Start Date" class="textAreaClass" style="margin-left: 18%;margin-top: 25px;padding: 5px;width:36%;cursor:pointer;" value="<?=((isset($_GET['ssid']))? $s_fulldate:'')?>"/>
                                <input type="text" name="end_date" id="end_date" readonly placeholder="End Date" class="textAreaClass" style="width:35%;margin-top: 25px;padding: 5px;cursor:pointer;" value="<?=((isset($_GET['ssid']))? $e_fulldate:'')?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                
                                    <textarea id="product_description" name="product_description" style="display: none;"><?=((isset($_GET['ssid']))? trim($description1):'')?></textarea>
                                    <input type="hidden" id="hdnSearch" />
                                    <div id="container">
                                    <div id="product_description1" name="product_description1" contenteditable="true" class="textAreaClass" 
                                    style="overflow: auto; color:<?=((isset($_GET['ssid']))? '#000':'#917591')?>; font-size: 15px; 
                                    font-family: Arial; background-color: white; border: 1px solid #A9A9A9; margin-left: 13%;resize: none;margin-top: 30px;width: 85%;height: 133px;padding: 8px;">
                                    	<?=((isset($_GET['ssid']))? trim($description):'Description of Item')?>
                                    </div>
                                    <div id='display' style="background-color: #FFF8DC; margin-left: 4%; margin-top: 1px; position: absolute; width: 25%; z-index: 2;">
                                    </div>
                                    </div>
                                
                            </td>
                            <td>
                              <div class="photo_div_before" id="show_photo_before" <?=((isset($_GET['ssid']) && $imgSource != '')? 'style="display:none;"':'')?>>
                                <div style="text-align: center;font-size: 16px;"> Photo of item? </div>
                                <div style="height: 75px;width: 28%;">
                                    <img src ="img/camera.png" id="item_img_camera" style="height: 79%;margin-left: 131%;margin-top: 8px;"/>
                                </div>
                                <div style="text-align: center;text-align: center;width: 150px;margin-left: 15%;margin-top: 0px"> 
                                (Click to upload or drag and drop file here) </div>
    
                              </div>
                              
                              <div class="photo_div" id="show_photo" <?=((isset($_GET['ssid']) && $imgSource != '')? 'style="display:block;"':'')?>>
                                <div style="text-align: center;font-size: 16px;"> Photo of item </div>
                                <div style="text-align:center;height: 160px;" id="imgDiv">
                                     <img src ="<?=((isset($_GET['ssid']) && $imgSource != '')? './images/signaturesandwich/'.$imgSource.'?time='.time():'')?>" id="item_img" style="margin-top: 9px; <?=((isset($_GET['ssid']) && $imgSource != '')? 'width:'.$mWidth.'px; height:'.$mHeight.'px;':'')?>"/>
                                </div>
                              </div>
                              <input name="userfile" type="file" id="userfile" size="30" style="margin-left: 4%;margin-top: -160px;opacity: 0;position: absolute;height: 165px;cursor: pointer" title=" ">
                                
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="width:290px">
                                <div id="sizeErrorMsg1" style="color: green;font-size: 13px;margin-left: 52px;">File Size must under 1MB</div>
                                
                                
                                 <span class="deleteimg" style="display:none;" id="deleteimg">Delete Photo</span>
                                 <input type="hidden" id="x" name="x" />
                                 <input type="hidden" id="y" name="y" />
                                 <input type="hidden" id="w" name="w" />
                                 <input type="hidden" id="h" name="h" />
                                 <span id="cropimg" name="cropimg" style="display: none;margin-left: 10px;" class="deleteimg" />Crop Image</span>
                                <input type="hidden" id="hdnScale" name="hdnScale" />
                            </td>
    
                        </tr>
                    </table>
                    <div id="sucessMessage"></div>
                    <div>
                        <input type="submit" value="Save" name="btnSaveAndAddMore" id="btnSaveAndAddMore"  class="btnadd" style="width: 16%;margin-top: 89px;margin-left: 240px; ">
                        <input type="button" class="btnCancelSM" id="btnCancelProduct" value="Cancel" style="margin-left: 25px;height: 38px;width: 96px;cursor:pointer">
                    </div>
                </div>
            </div>
	
    <!--	Right Bar Start	-->
    <div style="width:25%; float:left; padding:10px">
          
      <div class="ss_content" style="padding: 20px 10px">
        <div style="float:left; width:5%">
          <img src="img/enable.png" width="16" height="16" border="0" style="cursor: pointer;">
        </div>
        
        <div style="width:90%; float:right;">
          <div class="ss_prodTitle" id="ss_item_name"><?=((isset($_GET['ssid']))? $item_name:'')?></div>
          <div class="ss_prodDates">
            Featured Sandwich 
            <span id="ss_startdate"><?=((isset($_GET['ssid']))?  $startDate:'')?></span> - 
            <span id="ss_enddate"><?=((isset($_GET['ssid']))?  $endtDate:'')?></span>
          </div>
          <div class="ss_prodDescription"><?=((isset($_GET['ssid']))? $description:'')?></div>
        </div>
        
        <img src="img/bh_item1.png<?php echo $jsParameter;?>" style="margin-left: 10px; cursor: pointer;" data-tooltip="BH Item" class="spicy1">
      </div>
	  
      <div style="clear:both"></div>
      
      <h1>Signature Sandwiches</h1>
      <table id="tblsandwiches" width="100%" cellpadding="3" cellspacing="0">
      <?php
      $query = dbAbstract::Execute("Select * from bh_signature_sandwitch where end_date >= '".date('Y-m-d')."'");
	  while($row = dbAbstract::returnObject($query)){
              
            $start_date = date("m/d",strtotime($row->start_date));
            $end_date = date("m/d",strtotime($row->end_date));
	  ?>
	    <tr>
        	<td>
			<a href="<?=$SiteUrl.'c_panel/?mod=signaturesandwitch&ssid='.$row->id?>" id="ss_<?=$row->id?>">
				<?=$row->item_name.' ('.$start_date.' - '.$end_date.')'?></td>
            </a>
        </tr>
	  <?php
	  }
	  ?>
      </table>
    </div>
    <!--	Right Bar End	-->
</div>

    <a class="fancyTrigger" href="#TheFancybox"></a>
    <div id="TheFancybox" class="handle_fancy">
        <div style="background-image: url('img/fupload.png');width:558px;height: 390px;display: none;" class="uploader_div">
            <div style="text-align: center">
                <input type="submit" value ="Browse Files" name="upd_file_btn" id="upd_file_btn" class="updfile"/>
                <div id="sizeErrorMsg" style="margin-top: 8px;font-size: 13px;">File Size must under 1MB</div>
                <input name="userfile-uploader" type="file" id="userfile-uploader" size="30" style="visibility: hidden" title=" ">
            </div>
        </div>
    </div>
    
</form>