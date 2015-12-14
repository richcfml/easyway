<?php
//include('grouporder.php');
//echo "<pre>"; print_r($loggedinuser); echo "</pre>";
?>
<link rel="stylesheet" href="<?=$js_root?>textext/css/textext.core.css" type="text/css">
<link rel="stylesheet" href="<?=$js_root?>textext/css/textext.plugin.tags.css" type="text/css">
<link rel="stylesheet" href="<?=$js_root?>textext/css/textext.plugin.autocomplete.css" type="text/css">
<link rel="stylesheet" href="<?=$js_root?>textext/css/textext.plugin.focus.css" type="text/css">
<link rel="stylesheet" href="<?=$js_root?>textext/css/textext.plugin.prompt.css" type="text/css">
<link rel="stylesheet" href="<?=$js_root?>textext/css/textext.plugin.arrow.css" type="text/css">
<script src="https://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
<script src="<?=$js_root?>textext/js/textext.core.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$js_root?>textext/js/textext.plugin.tags.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$js_root?>textext/js/textext.plugin.autocomplete.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$js_root?>textext/js/textext.plugin.suggestions.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$js_root?>textext/js/textext.plugin.filter.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$js_root?>textext/js/textext.plugin.focus.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$js_root?>textext/js/textext.plugin.prompt.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$js_root?>textext/js/textext.plugin.ajax.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$js_root?>textext/js/textext.plugin.arrow.js" type="text/javascript" charset="utf-8"></script>
<style>
.text-core .text-wrap .text-tags .text-tag .text-button {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	position: relative;
	float: left;
	border: 1px solid #666666;
	background: #FFFFFF;
	color: #767879;
	padding: 18px 22px 30px 15px;
	margin: 0 2px 2px 0;
	cursor: pointer;
	height: 20px;
	font: 14px "Helvetica Neue", Helvetica, Arial, sans-serif;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
	max-width: 215px;
}
.text-core .text-wrap .text-tags .text-tag .text-button a.text-remove {
    position: absolute;
    right: 10px;
    top: 20px;
    display: block;
    width: 10px;
    height: 11px;
    background: url("../css/new_mobile/images/icons.svg") no-repeat;
    background-position: -217px -12px;
}
.text-core .text-wrap .text-tags .text-tag .text-button a.text-remove:hover{
    background-position: -217px -12px !important;
}
.text-wrap {
	width: 100% !important;
}
</style>
<body class=group_orders>
<div class=notification__overlay></div>
<?php require($mobile_root_path . "includes/header.php"); ?>
<main class=main>
  <div class=main__container>
    <section class=section>
      <header class=section__header>
        <h2 class=section__title>Group Ordering</h2>
      </header>
      
      <div class='section__article center-text'>
        <div class=section__article-content>
          <?php
            if (($loggedinuser->id>0) && (trim($loggedinuser->password)!=""))
            {
                $mSQL = "SELECT GroupID AS GroupName, GroupID, CustomerID, UserID  FROM grouporder WHERE CustomerID=".$loggedinuser->id." AND grp_useremail='".$loggedinuser->cust_email."' AND grp_usertype='user' AND Status=0";
                $mRes = dbAbstract::Execute($mSQL);
                if (dbAbstract::returnRowsCount($mRes)>0)
                {
                    echo '<p>1 Group Ordering invitation from: <br>';
                    
                    while($mRow=dbAbstract::returnObject($mRes))
                    {
                        echo '<b>Group '.$mRow->GroupName.'</b></p>';
                        echo '<input type=button value=Respond class="btnRespond full-width js-respond-group-order" 
								url="'.$SiteUrl.$objRestaurant->url.'/?grp_userid='.$mRow->CustomerID.'&grpid='.$mRow->GroupID.'&uid='.$mRow->UserID.'&grp_keyvalue=group_order#make-order" />';
                    }
                }else echo '<p>No Group Ordering invitation.</p>';
            }else echo '<p>No Group Ordering invitation.</p>';
            ?>
        </div>
      </div>
      
      <div class='section__article center-text'>
        <header class=section__article-header>
          <h3 class='section__article-title group-orders__title'>Group Ordering</h3>
        </header>
        <div class=section__article-content>
          <p>Simplify large orders - start a group order with your friends!</p>
          <input type=button value="Start Order" class="full-width js-new-group-order"/>
        </div>
      </div>
      
      <div class='section__article center-text'>
        <div class=section__article-content> 
          <?php
		  if (($loggedinuser->id > 0))
		  {
			  $mSQL = "SELECT GroupID AS GroupName, GroupID, CustomerID, UserID  FROM grouporder WHERE CustomerID=".$loggedinuser->id." AND grp_useremail='".$loggedinuser->cust_email."' AND grp_usertype='leader' AND Status=0";
			  $mRes = dbAbstract::Execute($mSQL);
			  if (dbAbstract::returnRowsCount($mRes)>0)
			  {
				  echo '<p>Current Group Order: <br>';
				  
				  while($mRow=dbAbstract::returnObject($mRes))
				  {
					  echo '<b>Group '.$mRow->GroupName.'</b></p>';
					  echo '<input type=button value=Respond class="btnRespond full-width js-respond-group-order" 
							  url="'.$SiteUrl.$objRestaurant->url.'/?grp_userid='.$mRow->CustomerID.'&grpid='.$mRow->GroupID.'&uid='.$mRow->UserID.'&grp_keyvalue=group_order#make-order" />';
				  }
			  }else echo '<p>No group orders started.</p> ';
		  }
		  else echo '<p>No group orders started.</p> ';
		  ?>
        </div> 
      </div>
    </section>
    
    <section class=section style='display: none'>
      <header class=section__header>
        <h2 class=section__title>Start a Group Order</h2>
      </header>
      
      <form id="group-orders-form" method="post" action="">
        <div class=js-step data-name='Start group ordering'>
          <div class=section__article>
            <header>
              <p>Start group ordering by entering your email address and phone number.</p>
            </header>
            <div class=section__article-content>
              <fieldset>
                <div class=contact-fields>
                  <div class=contact-fields__name>
                    <label for="first_name">First name: </label>
                    <div class=input>
                      <input name="first_name" id="first_name" value="<?=(isset($loggedinuser->cust_your_name))? $loggedinuser->cust_your_name:''?>" autocomplete="given-name" />
                      <span class=input-error>Invalid first name</span> 
                    </div>
                  </div>
                  
                  <div class='contact-fields__name right'>
                    <label for=last_name>Last name: </label>
                    <div class=input>
                      <input name="last_name" id="last_name" value="<?=(isset($loggedinuser->LastName))? $loggedinuser->LastName:''?>" autocomplete="family-name" />
                      <span class=input-error>Invalid last name</span> 
                    </div>
                  </div>
                </div>
                
                <label for=email>Email: </label>
                <div class=input>
                  <input type="email" name="email" id="email" value="<?=(isset($loggedinuser->cust_email))? $loggedinuser->cust_email:''?>" autocomplete="email" placeholder="e.g. john@doe.com" />
                  <span class=input-error>Please provide a valid email</span> 
                </div>
                
                <label for="phone_number">Phone number: </label>
                <div class=input>
                  <input type="tel" name="phone" id="phone_number" value="<?=(isset($loggedinuser->cust_phone1))? $loggedinuser->cust_phone1:''?>" placeholder="e.g. 202-555-0114" autocomplete="tel" />
                  <span class=input-error>Please provide a valid phone</span> 
                </div>
              </fieldset>
            </div>
          </div>
        </div>
        
        <div class=js-step data-name='Add members'>
          <div class=section__article>
            <header>
              <p>Invite your group members by entering their emails.</p>
              <p class="redtxt" id="error"></p>
            </header>
            
            <div class=section__article-content>
              <fieldset>
                <label for="member_name">Member name: </label>
                <input name="member_name" id="member_name" />
                <label for="email">Email: </label>
                <input type="email" name="email" id="member_email" />
              </fieldset>
              <input type="button" id="addmore" value="Add more"/>
              <img src="../images/loading.gif" id="loading" style="position:relative;left:5px;top:10px; display:none;"> 
            </div>
          </div>
          
          <div class=section__article>
            <header class=section__article-header>
              <h3 class=section__article-title>Group Members:</h3>
            </header>
            <input type="text" name="userNameEmail" id="tag_textarea" class="InputTagStyle" style="margin-left: 0px !important;" readonly/>
          </div>
        </div>
      	</div>
      </form>
    </section>
  </div>
</main>
<script type="text/javascript">
    var memberArr = [];
    
    $(document).ready(function() {
      $.noConflict();
      EasyWay.GroupOrders.setup();
      
      $('#tag_textarea').textext({plugins: 'tags'});
      
      $("#addmore").click(function(){
          var reg = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
          if ($('#member_name').val() == "")
          {
              $('#member_name').css('border-color','#F00');
              $("#error").html('Please enter name.');
          }
          else if (reg.test($('#member_email').val()) == false)
          {
              $('#member_email').css('border-color','#F00');
              $("#error").html('Invalid email address.');
          }
          else if($('#member_email').val() in memberArr){
              $("#error").html('Email address already exist.');
          }
          else{
              $("#error").html('');
              if($('#member_names').val()==''){
                  $('#member_names').val($('#member_name').val());
              }else{
                  $('#member_names').val($('#member_names').val()+'~'+$('#member_name').val());
              }
              
              if($('#member_emails').val()==''){
                  $('#member_emails').val($('#member_email').val());
              }else{
                  $('#member_emails').val($('#member_emails').val()+'~'+$('#member_email').val());
              }
              
              memberArr[$('#member_email').val()] = $('#member_name').val();
              
              $('#tag_textarea').textext()[0].tags().addTags([$('#member_name').val() + " - " + $('#member_email').val()]);
              
              $('#member_name').val('');
              $('#member_name').css('border-color','#6b8f9a');
              
              $('#member_email').val('');
              $('#member_email').css('border-color','#6b8f9a');
          }
      });
      
      $(".btnRespond").click(function(){
          window.location = $(this).attr('url');
      });
      
      $('#group-orders-form').submit(function(event){
          $("#imgStart").show();
          $("#CheckoutSubmitOrder").attr("disabled", "disabled");
          event.preventDefault();
          var formData = {
              'first_name'     : $('#first_name').val(),
              'last_name'     : $('#last_name').val(),
              'GrpOrderemail'    : $('#email').val(),
              'GrpOrdercontact'    : $('#phone_number').val(),
              'userNameEmail'    : $('input[name=userNameEmail]').val(),
          };
          $("#loading").show();
          var url = "?item=grouporder_ajax&submitform=1&ajax=1";    
          $.ajax({
              url: url,
              type: "POST",
              data: formData,
              success: function(data) 
              {
                  $("#group_notify").html("Users have been notified of Group order.");
                  var mTmp = data.split("~");
                  window.location = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=menu&grp_userid="+mTmp[1]+"&grpid="+mTmp[0]+"&uid=1&grp_keyvalue=group_order#order-sent";
              },
              error: function(a) 
              {
                  $("#loading").hide();
                  $("#group_notify").html(a.responseText);
                  $("#group_notify").css('color','#F00');
              }
          }); 
          return false;
      });
    });
  </script>