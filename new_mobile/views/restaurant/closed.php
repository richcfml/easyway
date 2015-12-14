<?php require($mobile_root_path . "includes/header.php"); ?>
<section class='menu_list_wrapper'>
  <div class="alert-error">
    <img src="../images/store_closed.png" />
    <h2>Sorry we are closed</h2>
  </div>
  <h1 class="close_h1">Full Week Business Hours</h1>
  <?
  $arr_days=$objRestaurant->getBusinessHoursByRestaurantID();
  foreach($arr_days as $day){
  ?>
  <div class="hour_row">
    <span class="day_name"><?= $day->dayName ?></span>
    From: <?= $day->open ?> To: <?= $day->close ?>
  </div>
  <? } ?>
 </section>
