

    <div id="cardentry_holder" style="display:none">

<div class="valutec">
  <center>
    <h2> Register your V.I.P card and earn rewards with every order</h2>
  </center>
  <div class="container">


    <?
if(!isset($loggedinuser->id)) {?>
    <div class="loginmessage">You must be logged in to activate your card, please login <a href="?item=login">here </a> or click <a href="?item=login">here</a> to create an account</div>


    <? } else { ?>

      <div class="msg_warning hidden"></div>

    <div class="inputlabel">Enter your V.I.P card number:<font color="#FF0000">*</font></div>
    <div class="inputtext">
      <input name="InputCardNumber" id="InputCardNumber" type="text" maxlength="20" value="">
    </div>

    <div style="clear:both"></div>

    <div class="bttn"><input type="button" name="registercard" id="registercard" value="Register card" class="button blue"><img id="loader" src="<?=$SiteUrl?>images/loader.gif"></div>


    <? }?>

  <br />
  <br />
  <br />
</div>

<!--[if IE 6]><br class="clearfloat" /><![endif]--><!--[if IE 7]><br class="clearfloat" /><![endif]-->

</div>
</div>
 <div id="mail_subscription_entry_holder" style="display:none;"></div>
<script language="javascript">
	$(function() {
		$(".close").click(function() {
			$(document).trigger('close.facebox');
			location.reload();
		});

		$("#close").live('click',function() {
			$(document).trigger('close.facebox');
			location.reload();
		});


		$("#registercard").click(function() {
				 var vip_card_number= $.trim($("#InputCardNumber").val());
				 if(vip_card_number.length<19) { alert("please enter valid card number ");return false;}
				 $("#loader").show();
				 var button= $(this)
				 $(button).attr("disabled", "disabled");
console.log('herer')
				 $.post("?item=register_card&ajax=1",{InputCardNumber:vip_card_number}  , function (data) {
					 

                                                $("#loader").hide();
						 if(data.success=="0"){
                                                     
							 $(".msg_warning").show().append("<div></div>").html(data.message);
						}else if(data.success=="1"){
                                                     
							$("#mail_subscription_entry_holder").slideDown(1000);
							 $("#cardentry_holder").slideUp(1000);
							 $("#viprewarmessage").show();
							 $("#viprewarmessage").html(data.message);
						}

					 	  $(button).removeAttr("disabled");
					 }, "json");

			});

			$.get("?item=mailinglist&ajax=1" ,function(data) {
					 $("#mail_subscription_entry_holder").html(data);

				});
	});
	</script>

