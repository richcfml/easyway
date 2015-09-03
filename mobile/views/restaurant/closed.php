<section class='menu_list_wrapper'>
  <div class="alert-error" style="vertical-align:text-top;background-color:#F99">
    <div class="left" style="width:auto;"><img src="../images/store_closed.png" /></div>
    <h2 class="left" style="width:auto;padding-top:15px;">Sorry we are closed</h2>
    <div class="clear"></div>
  </div>
  <h1>Full Week Business Hours</h1>
  <?
  $arr_days=$objRestaurant->getBusinessHoursByRestaurantID();
  foreach($arr_days as $day){
  ?>
  <div class="hour_row">
    <div class="day_name"> <?= $day->dayName ?> </div>
    <div class="one-fourth bold"> From: </div>
    <div class="one-fourth"> <?= $day->open ?></div>
    <div class="one-fourth bold"> To: </div>
    <div class="one-fourth column-last"><?= $day->close ?> </div>
    <div class="clear"></div>
  </div>
  <? } ?>
 </section>
