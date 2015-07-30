<!--Start script for dependent list-->
<style>

    .analytics{
        padding: 5px 10px 5px 0; float: left; width: 60px;
    }

    .ln-letters a:link {text-decoration:none;color: blue;}
    .ln-letters a:visited {text-decoration:none;color: blue;}
    .ln-letters a:hover {text-decoration:none;}
    .ln-letters a:active {text-decoration:none;}
    .ln-letters{text-align: center;}

    .listNav,.ln-letters{overflow:hidden;}
    .listNav{margin-left: 5px;margin-top: 10px;margin-bottom: 10px;}
    .listNavHide{display:none;}
    .listNavShow{display:list-item;}
    .letterCountShow{display:block;}
    .ln-letters a{display:block;float:left;padding:2px 6px;border:1px solid silver;border-right:none;text-decoration:none;}
    .ln-letters .ln-last{border-right:1px solid silver;}
    .ln-letters a:hover,
    .ln-letters .ln-selected{background-color:#eaeaea;}
    .ln-letter-count{position:absolute;top:0;left:0;width:20px;text-align:center;font-size:0.8em;line-height:1.35;color:#336699;}/*# sourceMappingURL=listnav.css.map */

</style>
<script language="javascript" type="text/javascript">

    $(function() {
        $(".statuslink").attr("href", "javascript:void(0);");

        $(".suspendedstatuslink").attr("href", "javascript:void(0);");

        $(".statuslink").live("click", function() {
            var slink = $(this);

            $.post("admin_contents/resturants/setstatus.php",
                    {rest_id: $(this).attr("data-id"), status: $(this).attr("data-status"), admin_type: '<?= $admin_type ?>'},
            function(data) {


                var response = data.split('-');


                $(slink).attr("data-status", response[0]);
                $(slink).text(response[1]);



            });

        })

        $(".suspendedstatuslink").live("click", function() {
        });

    });


</script>
<!--End script for dependent list-->

<?php
$and_sch = '';
$search_by = '';
$search_field = '';
if (isset($_REQUEST['client_id'])) {
    $Clentid = $_REQUEST['client_id'];
} else {
    $Clentid = 0;
}

if (isset($_POST['sch_button'])) {
    $search_by = $_POST['search_by'];
    $search_field = $_POST['search_field'];
    $Clentid = (isset($_REQUEST['client_opt']) ? $_REQUEST['client_opt'] : 0);
    $and = "";
    if ($search_by == 1) {
        $and = "r.name like '%$search_field%'";
    } else if ($search_by == 2) {
        $and = "r.email like '%$search_field%'";
    } else if ($search_by == 3) {
        $and = "REPLACE( REPLACE( REPLACE( REPLACE( r.phone,  '(',  '' ) ,  ')',  '' ) ,  '-',  '' ) ,  ' ',  '' ) like '%$search_field%'";
    } else if ($search_by == 4) {
        $and = "r.rest_zip like '%$search_field%'";
    } else if ($search_by == 5) {
        $search_field_1 = $_POST['search_field_1'];
        if ($search_field_1 == "red") {
            $and = "r.orders_last_month_count=0 and r.orders_last_but_second_month_count!=0 and r.status!=0";
        } else if ($search_field_1 == "grey") {
            $and = "r.orders_last_month_count=0 and r.orders_last_but_second_month_count=0 and r.status!=0";
        } else if ($search_field_1 == "yellow") {
            $and = "(r.orders_last_month_count!=0 and r.orders_last_but_second_month_count!=0) and (ceil(r.orders_last_month_count/r.orders_last_but_second_month_count * 100) - 100) < 35 and r.status!=0";
        } else if ($search_field_1 == "green") {
            $and = "(ceil(r.orders_last_month_count/r.orders_last_but_second_month_count * 100) - 100) > 34 and r.status!=0";
        } else if ($search_field_1 == "deactivated") {
            $and = "r.status=0";
        }
    } else if ($search_by == 6) {
        $and = "r.chargify_subscription_id like '%$search_field%'";
    }

    if ($Clentid > 0 || $_SESSION['admin_type'] == 'reseller' || $_SESSION['admin_type'] == 'store owner') {

        $and_sch = ' and ' . $and;
    } else {

        if (!($Clentid > 0)) {
            $and_sch = ' and ' . $and;
        } else {
            $and_sch = ' where ' . $and;
        }
    }
} //isset($_REQUEST['sch_button']

if (isset($_REQUEST['rest_activ_id'])) {
    $id = $_REQUEST['rest_activ_id'];
    dbAbstract::Update("UPDATE resturants SET  status='1',status_changed_by='" . $admin_type . "' WHERE id=$id",1);
    dbAbstract::Update("UPDATE analytics SET  status='1' WHERE resturant_id=$id",1);
} else if (isset($_REQUEST['rest_deactive_id'])) {
    $id = $_REQUEST['rest_deactive_id'];
    dbAbstract::Update("UPDATE resturants SET  status='0', status_changed_by='" . $admin_type . "' WHERE id=$id",1);
    dbAbstract::Update("UPDATE analytics SET  status='0' WHERE resturant_id=$id",1);
}

if ($Clentid == 0) {

    $Clentid = @$_REQUEST['clientId'];
}
$filter_url = false;
$analytics_query = "SELECT * FROM analytics";
$first_and = "where";
$first_letter = "first_letter in (1,2,3,4,5,6,7,8,9)";

if (isset($_REQUEST['first']) && !empty($_REQUEST['first'])) {
    $letter = dbAbstract::returnRealEscapedString($_REQUEST['first']);
    if ($letter == "num") {
        $first_letter = "first_letter in (1,2,3,4,5,6,7,8,9)";
    } else if ($letter == "all") {
        $first_letter = "first_letter != ''";
    } else {
        $first_letter = "first_letter = '" . $letter . "'";
    }
}
if ($_SESSION['admin_type'] == 'admin') {
    //GET ALL RESTURANTS WHICH BLONG TO A SPECIFIC RESELLER
    if (isset($_REQUEST['reseller']) && $_REQUEST['reseller'] > 0) {
        // GET ALL CLIENTS OF SPECIFIC RESELLER
        $reseller_client_ids = resellers_client($_REQUEST['reseller']);
        if (!isset($_REQUEST['first']) && empty($_REQUEST['first'])) {
            $letter = "all";
            $first_letter = "first_letter != ''";
        }

        //$qry = "where r.owner_id IN ( $reseller_client_ids ) AND r.status = 1";
        $qry = "where r.owner_id IN ( $reseller_client_ids )";
        $filter_url = "?mod=resturant&reseller=" . $_REQUEST['reseller'];
    } else {

        if ($Clentid > 0) {
            if (!isset($_REQUEST['first']) && empty($_REQUEST['first'])) {
                $letter = "all";
                $first_letter = "first_letter != ''";
            }
            //$qry = "where r.owner_id ='" . $Clentid . "' AND r.status = 1";
            $qry = "where r.owner_id ='" . $Clentid . "'";
            $filter_url = "?mod=resturant&resellerId=" . $_REQUEST['resellerId'] . "&client_id=" . $Clentid;
        } else {
            //$qry = "where r.status = 1";
	     $qry = "where 1=1";
             
        }
    }
} else if ($_SESSION['admin_type'] == 'reseller') {

    $client_ids = resellers_client($_SESSION['owner_id']);

    $_SESSION['RESSELER_CLIENTS'] = $client_ids;

    if ($Clentid > 0) {

        //$qry = "where r.owner_id ='" . $Clentid . "' AND r.status = 1";
        $qry = "where r.owner_id ='" . $Clentid . "'";
        $filter_url = "?mod=resturant&resellerId=" . $_REQUEST['resellerId'] . "&client_id=" . $Clentid;
    } else {

        //$qry = "WHERE r.owner_id IN ( $client_ids ) AND r.status = 1";
        $qry = "WHERE r.owner_id IN ( $client_ids )";
        $filter_url = "?mod=resturant&resellerId=" . $_REQUEST['resellerId'];
    }
} else if ($_SESSION['admin_type'] == 'store owner') {

    //$qry = "where r.owner_id = '" . $_SESSION['owner_id'] . "' AND r.status = 1 ";
    $qry = "where r.owner_id = '" . $_SESSION['owner_id'] . "'";
    if (empty($_REQUEST['first'])) {
        $first_letter = "first_letter != 'all'";
        $letter = 'all';
    }
} 
else if ($_SESSION['admin_type'] == 'bh') 
{
    $qry = " WHERE bh_restaurant = 1 ";
    if (empty($_REQUEST['first'])) {
        $first_letter = "first_letter != 'all'";
        $letter = 'all';
    }
}



$restaurant_query = "";
if ($qry || $and_sch) {
    $restaurant_query = "where resturant_id in (SELECT r.id as resturant_id from resturants r " . $qry . " " . $and_sch . ")";
    $first_and = "and";
}

if ($search_by == 1) {
    $analytics_result = dbAbstract::Execute("$analytics_query $restaurant_query  order by name",1);
} else if ($search_by == 2) {
    $analytics_result = dbAbstract::Execute("$analytics_query $restaurant_query  order by name",1);
} else if ($search_by == 3) {
    $analytics_result = dbAbstract::Execute("$analytics_query $restaurant_query  order by name",1);
} else if ($search_by == 4) {
    $analytics_result = dbAbstract::Execute("$analytics_query $restaurant_query  order by name",1);
} else if ($search_by == 5) {
    $analytics_result = dbAbstract::Execute("$analytics_query $restaurant_query  order by name",1);
} else if ($search_by == 6) {
    $analytics_result = dbAbstract::Execute("$analytics_query $restaurant_query  order by name",1);
} else {
    $analytics_result = dbAbstract::Execute("$analytics_query $restaurant_query $first_and $first_letter order by name",1);
}
   //echo "$analytics_query $restaurant_query  order by name";exit;
//  
$numrows1 = dbAbstract::returnRowsCount($analytics_result,1);
?>
<script language="javascript" type="text/javascript">

    jQuery(function() {
        $cntrl1 = jQuery("input#search_field");
        $cntrl2 = jQuery("select#search_field_1");
        jQuery("select#search_by").change(function() {
            if (jQuery(this).val() != 5) {
                $cntrl1.show();
                $cntrl2.hide();
            } else {
                $cntrl1.hide();
                $cntrl2.show();
            }
        });
        if (jQuery("select#search_by").val() == 5) {
            $cntrl1.hide();
            $cntrl2.show();
        }
    });

    this.tooltip = function() {
        $("a.tooltip").hover(function(e) {
            this.t = this.title;
            this.title = "";
            $("body").append("<div id='aToolTip' class='defaultTheme'>" + this.t + "</div>");
            var obj = $(this);
            $("#aToolTip").css({
                "top": (obj.offset().top - 40) + 'px',
                "left": (obj.offset().left + 30) + 'px'
            })
                    .fadeIn("fast");
        },
                function() {
                    this.title = this.t;
                    $("#aToolTip").remove();
                });
    };

// starting the script on page load
    $(document).ready(function() {
        tooltip();
    });
</script>
<style type="text/css">
    a.tooltip {
        text-decoration: none;
    }
    #aToolTip {
        position: absolute;
        display: none;
        z-index: 50000;
    }

    #aToolTip .aToolTipContent {
        position:relative;
        margin:0;
        padding:0;
    }
    .defaultTheme {
        border:2px solid #444;
        background:#555;
        color:#fff;
        margin:0;
        padding:6px 12px;	

        -moz-border-radius: 12px 12px 12px 0;
        -webkit-border-radius: 12px 12px 12px 0;
        -khtml-border-radius: 12px 12px 12px 0;
        border-radius: 12px 12px 12px 0;

        -moz-box-shadow: 2px 2px 5px #111; /* for Firefox 3.5+ */
        -webkit-box-shadow: 2px 2px 5px #111; /* for Safari and Chrome */
        box-shadow: 2px 2px 5px #111; /* for Safari and Chrome */
    }
</style>
<div id="main_heading">
<? //if($_SESSION['admin_type'] == 'admin') { ?>
    <form id="searchRestByUserFrm" name="searchRestByUserFrm" method="post" action="?mod=resturant">
        <b>Search by: </b>
        <select name="search_by" id="search_by" style="font-size:19px; margin-right:3px" onchange="" >
            <option value="1" <? if ($search_by == 1) { ?> selected="selected"<? } ?>>Name</option>
            <option value="2" <? if ($search_by == 2) { ?> selected="selected"<? } ?>>Email</option>
            <option value="3" <? if ($search_by == 3) { ?> selected="selected"<? } ?>>Phone</option>
            <option value="4" <? if ($search_by == 4) { ?> selected="selected"<? } ?>>Zip</option>
            <option value="5" <? if ($search_by == 5) { ?> selected="selected"<? } ?>>Orders Status</option>
            <option value="6" <? if ($search_by == 6) { ?> selected="selected"<? } ?>>Chargify Subscription ID</option>
        </select>

        <input name="search_field"  id="search_field" style="font-size:18px;" value="<?= $search_field ?>" type="text" />
        <select name="search_field_1" id="search_field_1" style="font-size:18px; display: none;">
            <option value="green" <? echo (isset($search_field_1) ? ($search_field_1 == "green" ? "selected" : "") : ""); ?>>Green</option>
            <option value="yellow" <? echo (isset($search_field_1) ? ($search_field_1 == "yellow" ? "selected" : "") : ""); ?>>Yellow</option>
            <option value="red" <? echo (isset($search_field_1) ? ($search_field_1 == "red" ? "selected" : "") : ""); ?>>Red</option>
            <option value="grey" <? echo (isset($search_field_1) ? ($search_field_1 == "grey" ? "selected" : "") : ""); ?>>Grey</option>
            <option value="deactivated" <? echo (isset($search_field_1) ? ($search_field_1 == "deactivated" ? "selected" : "") : ""); ?>>Deactivated</option>
        </select>
        <label>
            <input type="submit" name="sch_button" id="sch_button" value="Submit" class="btn"/>
        </label>

    </form>

</div>
<?
if ($_SESSION['admin_type'] == 'admin') {

    include("left_nav_admin_resturant_listing.php");
} else if ($_SESSION['admin_type'] == 'reseller') {

    include("left_nav_reseller_resturant_listing.php");
}
if ($filter_url) {
    $filter_url = $filter_url . '&first=';
} else {
    $filter_url = '?mod=resturant&first=';
}
?>
<div id="contents_area" style="float:left; margin-left:15px; width:78%;">
<?
$i = 0;
if ($Clentid) {
    $name_str = get_reseller_client_names($Clentid);
    ?>
        <strong  style="font-size:18px;">&nbsp;<?= $name_str ?></strong><br />
        <?
    } else {
        ?>
        <strong  style="font-size:18px;">&nbsp;All Resturants</strong><br />
        <?
    }
    ?>
    <div id="restaurant_first_letter_filter" class="listNav">
        <div class="ln-letters">
            <a href="<?= $filter_url ?>num" class="<?php echo isset($letter) ? ($letter == 'num' ? 'ln-selected' : '') : 'ln-selected'; ?>" >0-9</a>
            <a href="<?= $filter_url ?>A"  class="<?php echo isset($letter) ? ($letter == 'A' ? 'ln-selected' : '') : ''; ?>">A</a>
            <a href="<?= $filter_url ?>B"  class="<?php echo isset($letter) ? ($letter == 'B' ? 'ln-selected' : '') : ''; ?>">B</a>
            <a href="<?= $filter_url ?>C"  class="<?php echo isset($letter) ? ($letter == 'C' ? 'ln-selected' : '') : ''; ?>">C</a>
            <a href="<?= $filter_url ?>D"  class="<?php echo isset($letter) ? ($letter == 'D' ? 'ln-selected' : '') : ''; ?>">D</a>
            <a href="<?= $filter_url ?>E"  class="<?php echo isset($letter) ? ($letter == 'E' ? 'ln-selected' : '') : ''; ?>">E</a>
            <a href="<?= $filter_url ?>F"  class="<?php echo isset($letter) ? ($letter == 'F' ? 'ln-selected' : '') : ''; ?>">F</a>
            <a href="<?= $filter_url ?>G"  class="<?php echo isset($letter) ? ($letter == 'G' ? 'ln-selected' : '') : ''; ?>">G</a>
            <a href="<?= $filter_url ?>H"  class="<?php echo isset($letter) ? ($letter == 'H' ? 'ln-selected' : '') : ''; ?>">H</a>
            <a href="<?= $filter_url ?>I"  class="<?php echo isset($letter) ? ($letter == 'I' ? 'ln-selected' : '') : ''; ?>">I</a>
            <a href="<?= $filter_url ?>J"  class="<?php echo isset($letter) ? ($letter == 'J' ? 'ln-selected' : '') : ''; ?>">J</a>
            <a href="<?= $filter_url ?>K"  class="<?php echo isset($letter) ? ($letter == 'K' ? 'ln-selected' : '') : ''; ?>">K</a>
            <a href="<?= $filter_url ?>L"  class="<?php echo isset($letter) ? ($letter == 'L' ? 'ln-selected' : '') : ''; ?>">L</a>
            <a href="<?= $filter_url ?>M"  class="<?php echo isset($letter) ? ($letter == 'M' ? 'ln-selected' : '') : ''; ?>">M</a>
            <a href="<?= $filter_url ?>N"  class="<?php echo isset($letter) ? ($letter == 'N' ? 'ln-selected' : '') : ''; ?>">N</a>
            <a href="<?= $filter_url ?>O"  class="<?php echo isset($letter) ? ($letter == 'O' ? 'ln-selected' : '') : ''; ?>">O</a>
            <a href="<?= $filter_url ?>P"  class="<?php echo isset($letter) ? ($letter == 'P' ? 'ln-selected' : '') : ''; ?>">P</a>
            <a href="<?= $filter_url ?>Q"  class="<?php echo isset($letter) ? ($letter == 'Q' ? 'ln-selected' : '') : ''; ?>">Q</a>
            <a href="<?= $filter_url ?>R"  class="<?php echo isset($letter) ? ($letter == 'R' ? 'ln-selected' : '') : ''; ?>">R</a>
            <a href="<?= $filter_url ?>S"  class="<?php echo isset($letter) ? ($letter == 'S' ? 'ln-selected' : '') : ''; ?>">S</a>
            <a href="<?= $filter_url ?>T"  class="<?php echo isset($letter) ? ($letter == 'T' ? 'ln-selected' : '') : ''; ?>">T</a>
            <a href="<?= $filter_url ?>U"  class="<?php echo isset($letter) ? ($letter == 'U' ? 'ln-selected' : '') : ''; ?>">U</a>
            <a href="<?= $filter_url ?>V"  class="<?php echo isset($letter) ? ($letter == 'V' ? 'ln-selected' : '') : ''; ?>">V</a>
            <a href="<?= $filter_url ?>W"  class="<?php echo isset($letter) ? ($letter == 'W' ? 'ln-selected' : '') : ''; ?>">W</a>
            <a href="<?= $filter_url ?>X"  class="<?php echo isset($letter) ? ($letter == 'X' ? 'ln-selected' : '') : ''; ?>">X</a>
            <a href="<?= $filter_url ?>Y"  class="<?php echo isset($letter) ? ($letter == 'Y' ? 'ln-selected' : '') : ''; ?>">Y</a>
            <a href="<?= $filter_url ?>Z"  class="<?php echo isset($letter) ? ($letter == 'Z' ? 'ln-selected' : '') : ''; ?>">Z</a>
            <a href="<?= $filter_url ?>all"  class="<?php echo isset($letter) ? ($letter == 'all' ? 'ln-selected' : '') : ''; ?> ln-last">All</a> 
        </div>
    </div>
<?php
if ($numrows1 > 0) {

    while ($cat_qryRs = dbAbstract::returnObject($analytics_result,1)) {

        $cat_id = $cat_qryRs->resturant_id;
        $cat_name = $cat_qryRs->name;
        $url_name = $cat_qryRs->url_name;
        $cat_image = $cat_qryRs->optionl_logo;
        $cat_status = $cat_qryRs->status;
        $last_month_count = $cat_qryRs->orders_last_month_count;
        $last_but_second_month_count = $cat_qryRs->orders_last_but_second_month_count;

        $last_month_traffic = $cat_qryRs->total_visits;
        $last_but_second_month_trrfic = $cat_qryRs->total_visits_second_last_month;

        $abandoned_carts_count_last_month = $cat_qryRs->abandoned_carts_count_last_month;
        $abandoned_carts_count_second_last_month = $cat_qryRs->abandoned_carts_count_second_last_month;

        if ($cat_image == '') {
            $cat_image = 'default_200_by_200.jpg';
        }
        ($i % 2 == 0) ? $row_bg = '' : $row_bg = 'bgcolor="#F8F8F8"';
        ?>
	  <div class="listbox" onMouseOver="this.style.backgroundColor='#FFC';" onMouseOut="this.style.backgroundColor='';">
		<div id="imagebox" ><img <?php echo 'src="../images/logos_thumbnail/'.$cat_image.'"' ?> border="0" width="80" height="50" /> </div>
        <div id="URL_Links">
		<div id="title">
		  <?php
			$percentage = 0;
			if($_SESSION['admin_type'] == "admin" || $_SESSION['admin_type'] == "reseller" || $_SESSION['admin_type'] == "store owner") {
				$img = "";
				if($cat_status == 0) {
					$img = "prohibited.gif";
				} else {
					if($last_month_count == 0) {
						$img = "red-light.gif";
						if($last_but_second_month_count == 0) {
							$img = "grey-light.gif";
						}
						
					} else {
						$last_but_second_month_count = ($last_but_second_month_count == 0 ? 1 : $last_but_second_month_count);
						$percentage = (ceil($last_month_count / $last_but_second_month_count * 100) - 100);
						if($percentage < 35) {
							$img = "yellow-light.gif";
						} else {
							$img = "green-light.gif";
						}
					}
				}
		  ?>
			
			<img src="images/<? echo $img; ?>" /><? //var_dump($cat_status); ?>
		  <? } ?>
		  <a href="./?mod=resturant&item=restedit&cid=<?=$cat_id?>">
		  <?=stripslashes(stripcslashes($cat_name))?>
		  </a> </div>
          
		<br style="clear:both" >
		<?php
                if($_SESSION['admin_type'] == 'admin' || $_SESSION['admin_type'] == 'reseller') 
                { 
                ?>
		<div id="actions" <?= $cat_status == 2 ?"style='width:88px';":"" ?>>
		  <div id="icons"><a href="./?mod=resturant&item=restedit&cid=<?=$cat_id?>">Edit</a></div>
		 
          <?php 
            if(  $cat_status == 0 )  
            { 
            ?>
		  <div id="icons"><a href="./?mod=<?=$mod?>&item=reststatus&rest_activ_id=<?=$cat_id?>" data-id="<?=$cat_id?>" data-status="1" class="statuslink" onClick="return confirm('Are you sure you want to change the status of this resturant?')">Activate</a></div>
		  <br style="clear:right;" />
		 
          <?php 
            } 
            else if($cat_status == 1) 
            { 
            ?>
		  <div id="icons" ><a href="./?mod=<?=$mod?>&item=reststatus&rest_deactive_id=<?=$cat_id?>" data-id="<?=$cat_id?>" data-status="0" class="statuslink" onClick="return confirm('Are you sure you want to change the status of this resturant?')">Deactivate</a></div>
		  <br style="clear:right;" />
            <?php
            } 
            else if($cat_status == 2) 
            { 
            ?>
		  <div id="icons" ><a href="javascript:void(0);" data-id="<?=$cat_id?>" class="suspendedstatuslink">SUSPENDED</a></div>
		  <br style="clear:right;" />
            <?php 
            } 
            ?>
		</div>
            <?php 
            }
            else
            {
                if ($_SESSION['admin_type'] == 'bh')
                {
            ?>
                <div id="actions" style="width: auto;">
                    <div id="icons"><a href="./?mod=resturant&item=restedit&cid=<?=$cat_id?>">Edit</a></div>
                </div>
            <?php
                }
            }
            ?>
		<div id="Site_URL">
			Site URL:&nbsp;<a href="<?php echo $SiteUrl.$url_name;?>/" target="_blank"><?php echo $SiteUrl.$url_name;?>/</a>
		</div>
            <?php
			if($_SESSION['admin_type'] == "admin" || $_SESSION['admin_type'] == "reseller" || $_SESSION['admin_type'] == "store owner") 
                        {
            ?>
				<div class='analytics'>
					<a href="?mod=analytics&item=orders&cid=<?=$cat_id?>" class="tooltip" title="You had <? echo $last_month_count; ?> orders over the last 30 days. That's <? echo abs($last_but_second_month_count - $last_month_count); ?> <? if($last_month_count > $last_but_second_month_count) { ?> more <? } else { ?> fewer <? } ?> compared to the previous 30 day period.">
						<span class="orders_count_container">
							<img src="images/orders_icon.gif" /> 
							<? echo $last_month_count; ?> 
							<? if($last_month_count > $last_but_second_month_count) { ?>
								<img src="images/green_arrow_up.gif" />
							<? } else { ?>
								<img src="images/red_arrow_down.gif" />
							<? } ?>
						</span>
					</a>
				</div>
				
				<div class='analytics'>
					<a href="?mod=analytics&item=traffic&cid=<?=$cat_id?>" class="tooltip" title="You had <? echo $last_month_traffic; ?> visitors on your menu page over the last 30 days. That's <? echo abs($last_but_second_month_trrfic - $last_month_traffic); ?> <? if($last_month_traffic > $last_but_second_month_trrfic) { ?> more <? } else { ?> fewer <? } ?> compared to the previous 30 day period.">
						<span class="orders_count_container">
							<img src="images/web_traffic_icon.gif" /> 
							<? echo $last_month_traffic; ?>
							<? if($last_month_traffic > $last_but_second_month_trrfic) { ?>
								<img src="images/green_arrow_up.gif" />
							<? } else { ?>
								<img src="images/red_arrow_down.gif" />
							<? } ?>
						</span>
					</a>
				</div>
				
				<div class='analytics'>
					<a href="?mod=analytics&item=abandoned_carts&cid=<?=$cat_id?>" class="tooltip" title="You had <? echo $abandoned_carts_count_last_month; ?> abandoned carts over the last 30 days. That's <? echo abs($abandoned_carts_count_second_last_month - $abandoned_carts_count_last_month); ?> <? if($abandoned_carts_count_last_month > $abandoned_carts_count_second_last_month) { ?> more <? } else { ?> fewer <? } ?> compared to the previous 30 day period.">
						<span class="orders_count_container">
							<img src="images/abandoned_cart_icon.png" /> 
							<? echo $abandoned_carts_count_last_month; ?>
							<? if($abandoned_carts_count_last_month > $abandoned_carts_count_second_last_month) { ?>
								<img src="images/red_arrow_up.png" />
							<? } else { ?>
								<img src="images/green_arrow_down.png" />
							<? } ?>
						</span>
					</a>
				</div>
		<?php
                } 
                ?>
        </div>
	  </div>
	  <?php 
		$i++;
	} 
        ?>
   
<?php
} 
else 
{ 
?>
        <strong>There is no restaurant under this Client.</strong>
<?php
}
?>
    </div>
	 <br style="clear:both;" />

 