 <? if($objRestaurant->useValutec==true) { 
			 $MAX_REWARD=$loggedinuser->valuetec_reward;
			 if($cart->grand_total()<$MAX_REWARD) {
					 $MAX_REWARD=number_format($cart->grand_total(0), 2, '.', ''); 
				 }
					 
		?>
        <br/>
        <div class="vipcard">
          <div class="second_body_heading">
            <div style="float:left; margin-right:10px;"> <img src="<?=$SiteUrl?>images/vip.png" /> </div>
            <div style="padding-top:10px;">VIP reward</div>
          </div>
          <? if($loggedinuser->valuetec_card_number==0) { ?>
          <div class="vipmessage">
            <?=$objRestaurant->VIPMessage ?>
          </div>
          <br/>
          <div class="radio_bttn" style="padding-left:0px;" >
            <input type="radio" name="vipcard" id="vipcard" value="1"  checked  />
            Yes I would like my FREE V.I.P. Card!
            <input type="radio" name="vipcard" id="vipcard" value="0"    />
            No thanks </div>
          <div style="clear:both"></div>
          <?  }  else {?>
          <div class="vipmessage"> You have a rewards balance of <b><u><?=$currency?><?= $loggedinuser->valuetec_reward ?></u></b>
             <div style="clear:both"></div>
            <div class="applyinputs">
            <div class="error_message" id="reward_error">You can apply for max <b><u><?=$currency?><?=$MAX_REWARD?></u></b> vip discount</div>
            <input type="button" name="applyvipreward" id="applyvipreward"  value="Apply to this purchase"/> &nbsp;<input size="3" value="<?=$currency?><?=$cart->vip_discount?>" id="vipredeemreward" name="vipredeemreward" <?=$loggedinuser->valuetec_reward<1 ? "disabled" :"" ?> >
            <input type="button" name="applymaxvipreward" id="applymaxvipreward" onClick="return false;"  value="USE MAX VIP $"/>
            </div><div style="text-align:right;width:200px; font-size:20px; font-weight:bold;"> OR </div>
            <div class="vippoints"><span style="font-size:18px;">Earn</span> <u><b><span id="vippoints"><?=  round( $objRestaurant->rewardPoints * $cart->sub_total, 0);  ?></span></b></u> points for this purchase.</div>
          </div>
          <? }?>
        </div>
        <? }  ?>