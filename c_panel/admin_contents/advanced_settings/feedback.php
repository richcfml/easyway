<?php 
$likeQry = mysql_query("SELECT
     bh.rest_id,
    SUM(bh.Rating = '0') AS `dislike`,
    SUM(bh.Rating = '1') AS `like`,
    r.url_name
FROM bh_rest_rating bh inner join resturants r on r.id = bh.rest_id where r.bh_restaurant = 1
GROUP BY rest_id");

$dislikeQry = mysql_query("SELECT bh.rest_id,dl.*,r.url_name FROM bh_rest_rating bh inner join resturants r on r.id = bh.rest_id inner join bh_dislike dl on dl.bh_rest_rating_id = bh.id where r.bh_restaurant = 1");

?>
<div>
   
    <div id="main_heading">
    <span>Customer Feedback</span>
    </div>
    <div><a href="<?=$AdminSiteUrl?>admin_contents/advanced_settings/import.php?count" target="_blank">Download CSV</a></div>
      <table class="listig_table" width="100%" border="0" align="center" cellspacing="1">
        <tr bgcolor="#729338">
          <th width="320"><strong>Restaurant Slug</strong></th>
          <th width="100"><strong>Like</strong></th>
          <th width="100"><strong>Dislike</strong></th>
          <th width="100"><strong>Satisfaction Percentage</strong></th>
        </tr>
        <?php while($likeArray	= mysql_fetch_array($likeQry)){
              $mLikePercentage = 0;
              if (($likeArray['like']==0) && ($likeArray['dislike']==0))
              {
                    $mLikePercentage = 0;
              }
              else
              {
                    $mLikePercentage = round(($likeArray['like']/($likeArray['like'] + $likeArray['dislike']))*100);
              }
              if( $counter++ % 2 == 0)  
              {
                $bgcolor = '#F8F8F8';
              }
              else 
              {   
                $bgcolor = '';
              }
       ?> 
        <tr bgcolor="<?=$bgcolor ?>" >
          <td><?=$likeArray['url_name']?></td>
          <td><?=$likeArray['like']?></td>
          <td><?=$likeArray['dislike']?></td>
          <td><?=$mLikePercentage?></td>
        </tr>
        <?php }?>
        <tr>
          <td colspan="9">&nbsp;</td>
        </tr>
      </table> 
</div>

<div style="margin-top: 20px;"><a href="<?=$AdminSiteUrl?>admin_contents/advanced_settings/import.php?import" target="_blank">Download CSV</a></div>
      <table class="listig_table" width="100%" border="0" align="center" cellspacing="1">
        <tr bgcolor="#729338">
          <th width="100"><strong>Restaurant Slug</strong></th>
          <th width="100"><strong>Dislike Reason</strong></th>
          <th width="300"><strong>Comments</strong></th>
          <th width="100"><strong>Date</strong></th>
        </tr>
        <?php while($dislikeArray	= mysql_fetch_array($dislikeQry)){
              if( $counter++ % 2 == 0)  
              {
                $bgcolor = '#F8F8F8';
              }
              else 
              {   
                $bgcolor = '';
              }
       ?> 
        <tr bgcolor="<?=$bgcolor ?>" >
          <td><?=$dislikeArray['url_name']?></td>
          <td><?=$dislikeArray['Reason']?></td>
          <td><?=$dislikeArray['Comments']?></td>
          <td><?=date('m-d-y',strtotime($dislikeArray['CreateDate']))?></td>
        </tr>
        <?php }?>
        <tr>
          <td colspan="9">&nbsp;</td>
        </tr>
      </table> 
</div>
