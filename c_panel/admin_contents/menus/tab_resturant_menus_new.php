<?php

$catid = $Objrestaurant->id;

$menu_id = '';
$menu_name = '';
$mRes = '';
$udpSql = '';

$mRes = getdata($Objrestaurant->id);

function getdata($catid) {
    global $menu_name;
    global $menu_id;
    global $udpSql;
    if (isset($_GET['menuid'])) {
        $menu_id = $_GET['menuid'];
        $menu_name = $_GET['menu_name'];
        $mSQL = "SELECT * FROM categories WHERE menu_id = " . $_GET['menuid'] . " ORDER BY cat_ordering";
    } else {
        $menyqry = dbAbstract::ExecuteArray("SELECT  id, menu_name FROM menus where rest_id = $catid AND status = 1 ORDER BY menu_ordering ASC limit 1", 1);
        $menu_id = $menyqry['id'];
        $menu_name = $menyqry['menu_name'];
        $mSQL = "select * from categories where menu_id=" . $menu_id . " ORDER BY cat_ordering";
    }
    if(dbAbstract::returnRowsCount(dbAbstract::Execute($mSQL, 1), 1) == 0)
    {?>
        <script type="text/javascript">
            $(function()
            {


                $( "[id='btnSubmit1']" ).hide();
                $( "[id='btnSubmit2']" ).hide();

            });
        </script>
    <?}
    else
        {?>
        <script type="text/javascript">
            $(function()
            {
                $("#maintbltr").hide();

            });
        </script>
<?php
    }
    return dbAbstract::Execute($mSQL, 1);
}

if (isset($_POST["btnSubmit"])) {
	if (isset($_POST["lblColumn1"])) 
	{
		if (trim($_POST["lblColumn1"])!="") 
		{
			if (trim($_POST["lblColumn1"])>=0) 
			{
				$mRestIDSO = $Objrestaurant->id;
				$mSQL = "UPDATE menus SET Column1Count=".trim($_POST["lblColumn1"])." WHERE id=".$menu_id;
				Log::write("Update menus - tab_resturant_menus_new.php", "QUERY --".$mSQL, 'menu', 1 , 'cpanel');
				dbAbstract::Update($mSQL, 1);
			}
		}
	}
	
    if (isset($_POST["lblHidden"])) {
        if (strpos($_POST["lblHidden"], "|") !== false) {
            $mCatSplit = explode("|", $_POST["lblHidden"]);
            for ($loopCount = 0; $loopCount < count($mCatSplit); $loopCount++) {
                $mProdSplit = explode(",", $mCatSplit[$loopCount]);
                for ($innerLoopCount = 0; $innerLoopCount < count($mProdSplit); $innerLoopCount++) {
                    if ($innerLoopCount == 0) { //CatID
                        $mCatID = $mProdSplit[$innerLoopCount];
                        $mSQL = "UPDATE categories SET cat_ordering=" . $loopCount . " WHERE cat_id=" . $mCatID;
                        Log::write("Update category - tab_resturant_menus_new.php", "QUERY --".$mSQL, 'menu', 1 , 'cpanel');
                        dbAbstract::Update($mSQL, 1);
                    } else {//Product/Item
                        $mProductID = $mProdSplit[$innerLoopCount];
                        $mSQL = "UPDATE product SET SortOrder=" . $innerLoopCount . ", sub_cat_id =" . $mCatID . " WHERE prd_id=" . $mProductID; //sub_cat_id is cat_id of Categories Table
                        Log::write("Update product - tab_resturant_menus_new.php", "QUERY --".$mSQL, 'menu', 1 , 'cpanel');
                        dbAbstract::Update($mSQL, 1);
                    }
                }
            }
        }
       Log::write("Update menu name,desc", "QUERY -- UPDATE menus SET menu_name= '" . addslashes($_POST["menuname"]) . "', menu_desc = '" . prepareStringForMySQL($_POST['description_menu']) . "' WHERE id =" . $menu_id, 'menu', 1 , 'cpanel');
       $udpSql  = dbAbstract::Update("UPDATE menus SET menu_name= '" . prepareStringForMySQL($_POST["menuname"]) . "', menu_desc = '" . prepareStringForMySQL($_POST['description_menu']) . "',menu_ordering= '".$_POST['menuordering']."' WHERE id =" . $menu_id, 1);

        $link =  $AdminSiteUrl.'?mod=new_menu&catid='.$Objrestaurant->id.'&menuid='.$menu_id.'&menu_name='.$_POST['menuname'];
        $escaped_link = htmlentities($link, ENT_QUOTES, 'UTF-8');
        header("Location: ".$link);       
       }
    $mRes = getdata($Objrestaurant->id);
}

if(isset($_POST['btnDeleteMenu']) && $_POST['allowDelete']==1)
{
    $getProductdIds = 0;
    $getcategoriesIds =0;
    $catQry = dbAbstract::Execute("Select cat_id from categories where menu_id =".$menu_id."", 1);
    while($categoryID = dbAbstract::returnAssoc($catQry, 1))
    {
        $getcategoriesIds .= $categoryID['cat_id'].",";
    }
    $getcategoriesIds = substr($getcategoriesIds,0,-1);
    
    $prdQry = dbAbstract::Execute("select prd_id from product where sub_cat_id in(".$getcategoriesIds." )", 1);
    while($prd = dbAbstract::returnAssoc($prdQry, 1))
    {
        $getProductdIds .= $prd['prd_id'].",";
    }
    $getProductdIds = substr($getProductdIds,0,-1);
    
    $mQuery = "Delete from attribute where ProductID in( ".$getProductdIds.")";
    dbAbstract::Delete($mQuery, 1);
    Log::write("Delete attribute - tab_resturant_menus_new.php - LINE 128", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');

    dbAbstract::Delete("Delete from product_association where product_id in( ".$getProductdIds.") or association_id in( ".$getProductdIds.")", 1);
    Log::write("Delete product association - menu_ajax.php", "QUERY -- Delete from product_association where product_id in( ".$getProductdIds.") or association_id in( ".$getProductdIds.")", 'menu', 1 , 'cpanel');
    
    dbAbstract::Delete("Delete from product where sub_cat_id in(".$getcategoriesIds.")", 1);
    Log::write("Delete product - menu_ajax.php", "QUERY -- Delete from product where sub_cat_id in( ".$getcategoriesIds.")", 'menu', 1 , 'cpanel');

    dbAbstract::Delete("Delete from categories where menu_id = ".$menu_id."", 1);
    Log::write("Delete category - menu_ajax.php", "QUERY -- Delete from categories where menu_id = ".$menu_id."", 'menu', 1 , 'cpanel');

    dbAbstract::Delete("Delete from menu_hours where menu_id = ".$menu_id."", 1);
    Log::write("Delete menu_hours - menu_ajax.php", "QUERY -- Delete from menu_hours where menu_id = ".$menu_id."", 'menu', 1 , 'cpanel');

    $result = dbAbstract::Delete("Delete from menus where id = ".$menu_id."", 1);
    Log::write("Delete Menu - menu_ajax.php", "QUERY -- Delete from menus where id = ".$menu_id."", 'menu', 1 , 'cpanel');

    $menyqry = dbAbstract::ExecuteArray("SELECT  id, menu_name FROM menus where rest_id = $catid ORDER BY menu_ordering ASC limit 1", 1);
    $menu_id = $menyqry['id'];
    $menu_name = $menyqry['menu_name'];
    if(!empty($menu_id))
    {
        $newMenuUrl = "&catid=".$Objrestaurant->id."&menuid=".$menu_id."&menu_name=".$menu_name;
    }
    else
    {
        $newMenuUrl = "&catid=".$Objrestaurant->id;
    }
    redirect($AdminSiteUrl.'?mod=new_menu'.$newMenuUrl);
}
?>

<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>jQuery Test</title>
        <link rel="stylesheet" type="text/css" href="css/tab.css">
        
        <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.js"></script>-->
        
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/normalize.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script>
        
        <script type="text/javascript" src="js/jquery.dnd_page_scroll.js"></script>
        <script type="text/javascript" src="js/new-menu.js<?php echo $jsParameter;?>"></script>
        <link rel="stylesheet" type="text/css" href="/css/result-light.css">
        <link rel="stylesheet" type="text/css" href="css/new_menu.css<?php echo $jsParameter;?>">
        <script src="../js/jquery.validate.js" type="text/javascript"></script>
        <script src="js/fancybox.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/fancy.css">
        <script src="js/block.js" type="text/javascript"></script>
        <link rel="stylesheet" href="css/darktooltip.min.css">
        <link rel="stylesheet" href="css/font-awesome.css">
        <script src="js/darktooltip.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/component.css" />
        <script src="js/modernizr.custom.js"></script>
        <script src="js/classie.js"></script>
        <script src="js/jquery.noty.packaged.min.js" type="text/javascript"></script>
        
        <script type="text/javascript" src="js/main_menu.js<?php echo $jsParameter;?>"></script>
        
        
        <link rel="stylesheet" href="onoff/jquery.onoff.css" media="screen" />
	<script src="onoff/jquery.onoff.js"></script>
        
<!-----------------------------------Start NK(2-10-2014)----------------------------------------------------->
        <script type="text/javascript">
        $.blockUI();

        $(window).load(function()
        {
        setTimeout( "leave()", 1000 );    
        });
        
        function leave()
        {
        $.unblockUI();
        }
        </script> 
<!-----------------------------------End NK(2-10-2014)----------------------------------------------------->                
	<script type="text/javascript">
        var SlideWidth = 500;
        var SlideSpeed = 500;

        $(document).ready(function () {
			$().dndPageScroll();
            // set the prev and next buttons display
            SetNavigationDisplay();
			$("#nextbtn").click(function(){
				// get the current margin and subtract the slide width
				var newMargin = CurrentMargin() - SlideWidth;
				//alert(newMargin);
				
				modulus = newMargin % 500;
				if(modulus != 0){
					newMargin = newMargin - modulus;
				}
				// slide the wrapper to the left to show the next panel at the set speed. Then set the nav display on completion of animation.
				$("#ss_slider").animate({ marginLeft: newMargin }, SlideSpeed, function () { SetNavigationDisplay() });
			});
			
			$("#prevbtn").click(function(){
				$(this).hide();
				// get the current margin and subtract the slide width
				var newMargin = CurrentMargin() + SlideWidth;
				
				modulus = newMargin % 500;
				if(modulus != 0){
					newMargin = newMargin - modulus;
				}
				
				// slide the wrapper to the right to show the previous panel at the set speed. Then set the nav display on completion of animation.
				$("#ss_slider").animate({ marginLeft: newMargin }, SlideSpeed, function () { SetNavigationDisplay() });
				$(this).show();
			});
			
        });

        function CurrentMargin() {
            // get current margin of slider
            var currentMargin = $("#ss_slider").css("margin-left");

            // first page load, margin will be auto, we need to change this to 0
            if (currentMargin == "auto") {
                currentMargin = 0;
            }

            // return the current margin to the function as an integer
            return parseInt(currentMargin);
        }

        function SetNavigationDisplay() {
            // get current margin
            var currentMargin = CurrentMargin();
            // if current margin is at 0, then we are at the beginning, hide previous
            if (currentMargin == 0) {
                $("#prevbtn").hide();
            }
            else {
                $("#prevbtn").show();
            }

            // get wrapper width
            var wrapperWidth = $("#ss_slider").width();

            // turn current margin into postive number and calculate if we are at last slide, if so, hide next button
            if ((currentMargin * -1) == (wrapperWidth - SlideWidth)) {
                $("#nextbtn").hide();
            }
            else {
                $("#nextbtn").show();
            }
        } 
    </script>
    </head>

    <body style="cursor: auto; font-family: Arial;">
        <input type="hidden" value="<?=$Objrestaurant->id?>" id="restaurantid"/>
        <?php include('../c_panel/admin_contents/products/attr_assoc_popup.php'); ?>
               <div style="position:relative;top: -25px;">
            <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left cbp-spmenu-open" id="cbp-spmenu-s1" style="position: absolute;">
                 <div class="leftDivRest"><span class="leftrestimg" ><img src="img/restaurant.png" id="leftimgRest" style="width: 30px;"/></span><a href="<?=$AdminSiteUrl?>?mod=resturant&item=restedit&catid=<?= $Objrestaurant->id; ?>" class="leftheadingSpan">Restaurants</a><i class="fa leftMenuArrow"></i></div>
                 <div class="leftDivOrder"><span class="leftorderimg" ><img src="img/orders.png" id="leftimgOrder" style="width: 30px;"/></span><span class="leftheadingSpan">Orders</span><i class="fa leftMenuArrow"></i></div>
                 <div style="clear:both"></div>
                 <div class="nestedMenu" style="display:none;margin-top: 20px;">
                     <a href ="<?=$AdminSiteUrl?>?mod=order&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Approved orders</a>
                     <a href ="<?=$AdminSiteUrl?>?mod=order&item=approve&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >New Orders</a>
                     <a href ="<?=$AdminSiteUrl?>?mod=order&item=refund&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Refund Orders</a>
                 </div>
                 <div class="leftDivMenus"><span class="leftmenusimg" ><img src="img/Menus.png" id="leftimgMenus" style="width: 30px;"/></span><span class="leftheadingSpan" >Menus</span><i class="fa leftMenuArrow"></i></div>
                 <div style="clear:both"></div>
                 <div class="nestedMenu" style="display:none;margin-top: 20px;">
                    <?php
                    $menu_qry = dbAbstract::Execute("select * from menus where rest_id = " . $Objrestaurant->id . " order by status, menu_name", 1);
                    $menu_i = 0;
                    while ($menuRs = dbAbstract::returnArray($menu_qry, 1)) {
                    ?>

                        <a <?php if ($menuRs['id'] == $menu_id || ($menu_i == 0 && $menu_id == "")) {
                                ?> class="selected"  <?php } ?>  href="?mod=new_menu&catid=<?= $Objrestaurant->id; ?>&menuid=<?= $menuRs['id'] ?>&menu_name=<?= $menuRs['menu_name'] ?>" id="<?= $menuRs['id']?>" class="menu_links draggable" <?php if ($menuRs['status']==0) { echo(" style='color: #CCCCCC !important;' "); } ?>  ><?= $menuRs['menu_name'] ?></a>
                        <?php $menu_i++;
                    } ?>

                    <a href ="#" id="add_mainmenu"  class="menu_links" >+</a>
                    <a href ="<?php echo $SiteUrl.$Objrestaurant->url; ?>/" target="_blank" class="menu_links" >View Live</a>
                 </div>
                 <div class="leftDivCustomers"><span class="leftcustomersimg" ><img src="img/ew_verticalnav_32-03.png" id="leftimgCustomers" style="width: 30px;"/></span><span class="leftheadingSpan">Customers</span><i class="fa leftMenuArrow"></i></div>
                 <div style="clear:both"></div>
                 <div class="nestedMenu" style="display:none;margin-top: 20px;">
                     <a href ="<?=$AdminSiteUrl?>?mod=customer&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >View/Edit Customer </a>
                     <a href ="<?=$AdminSiteUrl?>?mod=customer&item=search&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Search Existing</a>
                     
                 </div>
                 <div class="leftDivCoupans"><span class="leftcoupansimg" ><img src="img/ew_verticalnav_32-05.png" id="leftimgCoupans" style="width: 30px;"/></span><span class="leftheadingSpan">Coupons</span><i class="fa leftMenuArrow"></i></div>
                 <div style="clear:both"></div>
                 <div class="nestedMenu" style="display:none;margin-top: 20px;">
                     <a href ="<?=$AdminSiteUrl?>?mod=coupon&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Edit Existing Coupons </a>
                     <a href ="<?=$AdminSiteUrl?>?mod=coupon&item=add&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" style="height: 19px;"><span style="float: left;width: 28px;font-size: 25px;margin-left: 20px;">+</span><span style="float: left;margin-top: 5px;width: 90px;margin-left: -15px;">Add New </span></a>
                    
                 </div>
                 <div class="leftDivMailing"><span class="leftmailingimg" ><img src="img/mailing.png" id="leftimgMailing" style="width: 30px;"/></span><span class="leftheadingSpan">Mailing List</span><i class="fa leftMenuArrow"></i></div>
                 <div style="clear:both"></div>
                 <div class="nestedMenu" style="display:none;margin-top: 20px;">
                     <a href ="<?=$AdminSiteUrl?>?mod=mailing_list&catid=<?= $Objrestaurant->id; ?>"  class="menu_links">View/Edit list </a>
                     <a href ="<?=$AdminSiteUrl?>?mod=mailing_list&item=mailadd&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" style="height: 19px;"><span style="float: left;width: 28px;font-size: 25px;margin-left: 20px;">+</span><span style="float: left;margin-top: 5px;width: 90px;margin-left: -15px;">Add to list </span></a>

                 </div>
                 <div class="leftDivAnalytics"><span class="leftanalyticsimg" ><img src="img/ew_verticalnav_32-07.png" id="leftimgAnalytics" style="width: 30px;"/></span><span class="leftheadingSpan">Analytics</span><i class="fa leftMenuArrow"></i></div>
                 <div style="clear:both"></div>
                 <div class="nestedMenu" style="display:none;margin-top: 20px;">
                     <a href ="<?=$AdminSiteUrl?>?mod=analytics&cid=<?=$Objrestaurant->id?>"  class="menu_links" >Restaurant Report  </a>
                     <a href ="<?=$AdminSiteUrl?>?mod=analytics&item=abandoned_carts&cid=<?=$Objrestaurant->id?>"  class="menu_links" >Abandoned Carts</a>
                     <a href ="<?=$AdminSiteUrl?>?mod=analytics&item=traffic&cid=<?=$Objrestaurant->id?>"  class="menu_links" >Traffic</a>
                 </div>
                 <div class="leftDivReputation"><span class="leftreputationimg" ><img src="img/reputation_1.png" id="leftimgReputation" style="width: 30px;"/></span><span class="leftheadingSpan">Reputation</span><i class="fa leftMenuArrow"></i></div>
                 <div style="clear:both"></div>
                 <div class="nestedMenu" style="display:none;margin-top: 20px;">
                     <a href ="<?=$AdminSiteUrl?>?mod=overview&cid=<?=$Objrestaurant->id?>&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Overview  </a>
                     <a href ="<?=$AdminSiteUrl?>?mod=visibility&cid=<?=$Objrestaurant->id?>&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Visibility</a>
                     <a href ="<?=$AdminSiteUrl?>?mod=reviews&cid=<?=$Objrestaurant->id?>&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Reviews</a>
                     <a href ="<?=$AdminSiteUrl?>?mod=mentions&cid=<?=$Objrestaurant->id?>&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Mentions</a>
                     <a href ="<?=$AdminSiteUrl?>?mod=social&cid=<?=$Objrestaurant->id?>&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Social</a>
                     <a href ="<?=$AdminSiteUrl?>?mod=competition&cid=<?=$Objrestaurant->id?>&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Competition</a>
                     <a href ="<?=$AdminSiteUrl?>?mod=account&cid=<?=$Objrestaurant->id?>&catid=<?= $Objrestaurant->id; ?>"  class="menu_links" >Account</a>
                 </div>
            </nav>
             </div>
         <a class="fancymainmenu_form" href="#mainmenu_form"></a>
         <form method="post" id="mainmenu_form" style="display:none;">
            <div id ="add_new_menu" class ="add_menu" style="width:500px;height:400px">
                <div id="menu_Heading">Add New Menu</div>
                    <input type="text" id ="txt_menuname" name ="txt_menuname" style="margin-left: 120px;margin-top: 30px;width:250px;padding:8px" class="textAreaClass" placeholder="Menu Name"/>
                    <input type="text" id ="menu_ordering" name ="menu_ordering" style="display: none;margin-left: 120px;margin-top: 30px;width:250px;padding:8px" class="textAreaClass" placeholder="Order No" maxlength="3"/>
                    <textarea rows="5" cols="50" id="txt_menudescription" name="txt_menudescription" style="margin-left: 120px;margin-top: 16px;display:block;width:250px;padding:8px;resize:none" class="textAreaClass" placeholder="Menu Description" >
                    </textarea>
                <div id="btn_center"><input type ="submit" value="Add" name ="btnAdd_menu" id="btnAdd_menu"/>
                    <input type ="button" value="Cancel" name ="cancel_click_menu" id="cancel_click_menu" class="cancel"/>
                    </div>
            </div>
        </form>
    <div style="background-color: #FFFFFF;width: 763px;margin-left: 187px;margin-bottom: 10px;margin-top: -25px;">
      
      <div style="width:30%; float:left">
        <div style="font-family: 'Droid Sans',Arial,Geneva,Helvetica,sans-serif; font-size: 26px; color: #5B5B5B; font-weight: bold; margin-left: 20px; margin-bottom: 15px;">
		  <?=$menu_name?>
        </div>
        <div id ="add_new_submenu_link" class="submenu_btn" style="width:80%">
          <span id="plus_span">+</span>
          <span style="margin-left: 5px;float:left;font-size:14px">Add a New Submenu</span>
        </div>
      </div>
      
      <div style="width:70%; float:left">
      	<?php
		if($Objrestaurant->bh_restaurant == 1){
		  $ss_qry  = dbAbstract::Execute("select * from bh_signature_sandwitch where start_date >= '".strtotime(date('Y-m-d'))."' or end_date >= '".strtotime(date('Y-m-d'))."' order by start_date");
		  $ss_rows = dbAbstract::returnRowsCount($ss_qry);
		  if($ss_rows > 0 ){
		  ?>
		  <script>
		  function allowDrop(ev) {
			  ev.preventDefault();
		  }
		  
		  function drag(ev) {
			  ev.dataTransfer.setData("text", ev.target.id);
		  }
		  
		  function drop(ev) {
			  ev.preventDefault();
			  var sandwichId = ev.dataTransfer.getData("text");
			  
			  var subcatId = ev.target.getAttribute('sub_cat');
						  console.log(subcatId);
						  if(subcatId != null)
						  {
							  window.location.assign("<?=$SiteUrl?>c_panel/?mod=new_menu&item=addproduct_new&sub_cat="+subcatId+"&sandwichId="+sandwichId)
						  }
		  }
		  </script>
		  <div style="position:relative; top:45px; left:5px; width:500px; float:left; height:130px; overflow:hidden">
			<div class="ss_container">
			  
			  <div id="ss_slider" style="width:<?=$ss_rows * 500?>px;">
				<?php while($row = dbAbstract::returnObject($ss_qry)){ ?>
				<div id="ss_slider_img1" class="ss_div">
				  <div class="ss_img">
					<?php
					$ssp_qry=dbAbstract::Execute("select status from product where cat_id='$Objrestaurant->id' AND signature_sandwitch_id='$row->id'");
					$ssp_rows=dbAbstract::returnRowsCount($ssp_qry);
					if($ssp_rows > 0){
						$boarsHeadBtm = '34px';
						$boarsHeadlft = '55px';
						$draggable = 'true';
						$imgStyle='';
						
						$prow = dbAbstract::returnObject($ssp_qry);
						if($prow->status==0){
							$draggable = 'false';
							$boarsHeadBtm = '55px';
							$boarsHeadlft = '90px';
							$imgStyle='position:relative; bottom: 35px; left: 0px;';
					?>
							<img src="./images/signaturesandwich/BH_Pause.svg" draggable="false" style="position:relative; top:45px; left:88px; z-index:10">
					<?php	
						}
						elseif($row->start_date <= strtotime(date("Y-m-d")) && $row->end_date >= strtotime(date("Y-m-d"))){
							$draggable = 'false';
							$boarsHeadBtm = '55px';
							$boarsHeadlft = '90px';
							$imgStyle='position:relative; bottom: 35px; left: 0px;';
					?>
							<img src="./images/signaturesandwich/BH_Check.svg" draggable="false" style="position:relative; top:45px; left:88px; z-index:10">
					<?php
						}elseif($row->start_date > strtotime(date("Y-m-d"))){
							$draggable = 'false';
							$boarsHeadBtm = '60px';
							$boarsHeadlft = '90px';
							$imgStyle='position:relative; bottom: 40px; left: 0px;';
					?>
							<img src="./images/signaturesandwich/BH_Circle.svg" draggable="false" style="position:relative; top:45px; left:88px; z-index:10">
					<?php	
						}
					}else{
						$boarsHeadBtm = '20px';
						$boarsHeadlft = '88px';
						$imgStyle='cursor:move;';
						$draggable = 'true';
					?>
					  <!--<img src="img/move.png" alt="Move" draggable="true" ondragstart="drag(event)" id="<?=$row->id?>" style="position:relative; bottom:130px; left:5px;"/>-->
					<?php
					}
					?>
					<img src="./images/signaturesandwich/<?=(($row->item_image !='')? $row->item_image:'no image.png')?>" alt="Photo <?=$row->item_name?>" 
					draggable="<?=$draggable?>" ondragstart="drag(event)" id="<?=$row->id?>" style="width:100%; height:130px !important; <?=$imgStyle?>">
					
					<img src="./images/signaturesandwich/boarsHead.png" draggable="false" style="position:relative; bottom:<?=$boarsHeadBtm?>; left:<?=$boarsHeadlft?>">
				  </div>
				  <div class="ss_wrap">
					<div class="ss_content">
					  <div class="ss_prodTitle"><?=$row->item_name?></div>
					  <div class="ss_prodDates">
						Featured Sandwich <?=date('m/d',$row->start_date).' - '.date('m/d',$row->end_date)?>
					  </div>
					  <div class="ss_prodDescription"><?=$row->item_desc?></div>
					</div>
				  </div>
				</div>
				<?php } ?>
			  </div>
			  
			  <div id="nextbtn"> <i class="fa fa-chevron-circle-left"></i> </div>
			  <div id="prevbtn"> <i class="fa fa-chevron-circle-right"></i> </div>
			</div>
		  </div>
		<?
		  }
		}
		?>
      </div>
	  <div style="clear:both"></div>
            
            
            
            <a class="fancyadd_submenu" href="#menu_form"></a>
            <form method="post" id="menu_form" action="" style="display:none">
            <div id ="add_new_submenu" class ="add_submenu" style="width:500px;height:400px;">
                <div id="Submenu_Heading">Add New Sub Menu</div>
                <input type="text" id ="submenu_name" name ="submenu_name" style="margin-left: 120px;margin-top: 30px;width:250px;padding:8px" class="textAreaClass" placeholder="Name"/>
                <textarea rows="5" cols="50" id="description" name="description" style="font-family: Arial;margin-left: 120px;margin-top: 16px;display:block;width:250px;padding:8px;resize:none" class="textAreaClass" placeholder="Description of Category" >

                            </textarea>
                <a class="hover_gray" style="padding: 2px 6px 2px 5px;border-radius: 50%;color: black;background-color: #b9b9b9;cursor: pointer;margin-right: 85px;float:right;color: white;">?<i style="width:165px;margin-left:-85px">Attributes help your customers customize their order even more. Here is where they can pick toppings, dressings and the like</i></a>
                <div class ="attr_desc" id="attr_desc">Any Attribut? Click on Attributes in your textbox at
                    right, to add. "Click New" to add new attribute.
                </div>

                <div id="btn_center"><input rid="<?=$Objrestaurant->id?>" type ="submit" value="Add" name ="btnAdd" id="btnAdd"/>
                    <input type ="submit" value="Update" name ="btnUpdate" id="btnUpdate"/>
                    <input type ="button" value="Cancel" name ="cancel_click" id="cancel_click" class="cancel"/>
                    </div>
                <input type="hidden" id="hdnCatid" name="hdnCatid"/>
            </div>
            </form>
        </div>
        <form method="post" id="menu_shuffle_form" style="margin-top: -20px;">
            
            <input type="hidden" id="lblHidden" name="lblHidden"/>
            <input type="hidden" id="lblColumn1" name="lblColumn1" value="-1"/>
           
            <table style="width: 62.2%; margin: 0px;margin-left: 187px;min-height: 503px;" cellpadding="0" cellspacing="0" border="0" id ="main_tbl">
                <div id="right_panelforMenu" style="margin-top: 20px;margin-right: 52px;">
                <ul  class="tabs" style="margin-top: -75px;">
                    <li id ="main_tab1" class="active" rel="tab1" style="display:none;line-height: 44px;margin-top: -16px;"> Attributes</li>
                    <li id ="main_tab2" rel="tab2" style="display:none;width: 78px;margin-top: -16px;"><span style="margin-top: 8px;float: left;">Complementary Items</span></li>
                    <li id ="main_tab3" rel="tab1" style="background: #FFFFFF;margin-right: 91px;margin-top: -16px;border-bottom: 1px solid #F4F4F4;width: 85px;min-height: 42px;text-align: center;line-height: 50px;"> <? if($menu_name==''){echo $_GET['menuname'];}else{echo $menu_name;}?></li>
                </ul>
                </div>
                <div class="tab_container" style="min-height:660px;width:230px;margin-right: 40px;margin-top: -43px;">
					<?php 
						$menu_desc = dbAbstract::ExecuteArray("Select * from menus where id = " . $menu_id . "", 1); 
						$mMenuStatus = $menu_desc['status'];
					?>
                    <div id="tab1" class="tab_content">
						<script type="text/javascript" language="javascript">
							$(document).ready(function()
							{
							    $(".onoffswitch-inner").click(function() 
								{
									var mMenuID = $("#chkOnOff").attr("menuid");
									var mStatus = $("#chkOnOff").attr("status");
									
									var milliseconds = (new Date).getTime();
									ajaxUrl =  "admin_contents/menus/menu_ajax.php?menuonoff=1&menuid="+mMenuID+"&status="+mStatus+"&"+milliseconds;
									$.ajax({
										url: ajaxUrl,
										success: function(data) 
										{	
											if (isNumber(data))
											{
												$("#chkOnOff").attr("status", data);
												if ((data==1) || (data=="1"))
												{
													$("#chkOnOff").attr("checked","checked");
												}
												else
												{
													$("#chkOnOff").removeAttr("checked");
												}
											}
											else
											{
												alert(data);
											}
										},
										error: function (jqXHR, textStatus, errorThrown) 
										{	
											//alert(jqXHR.status);
											alert(textStatus);
										}
									});
								});
							});
						</script>
						<a class="hover_gray" style="padding: 2px 6px 2px 5px; border-radius: 50%;color: black;background-color: #b9b9b9; font-size: 12px; margin-left: 130px !important; margin-top: -15px !important; cursor: pointer;color: white;">?<i>Use this button to turn menus on and off.</i></a>
						<div style="margin-bottom: 20px; padding-left: 40px;">
							<style>
							#lblOnOff
							{
								padding-left: 0px !important;	
							}
							
							#lblOnOff:before
							{
								display: none;
							}
							</style>
							
							<div class="onoffswitch">
								<input type="checkbox" class="onoffswitch-checkbox" id="chkOnOff" status="<?=$menu_desc['status']?>" menuid="<?=$menu_id?>" <?php if (trim($mMenuStatus)==1) { echo("checked"); } ?> />
							  	<label class="onoffswitch-label" for="myonoffswitch" id="lblOnOff">
									<div class="onoffswitch-inner" status="<?=$menu_desc['status']?>" menuid="<?=$menu_id?>"></div>
									<div class="onoffswitch-switch"></div>
							  	</label>
							</div>
							
						</div>
                        <ul id="menuinfo">
                            <input type ="text" value="<? if($menu_name==''){echo $_GET['menuname'];}else{echo $menu_name;}?>" id="menuname" name="menuname"  style="width: 200px;margin-top: 30px;height: 25px;margin-left: -26px;" class="textAreaClass" placeholder="Menu Name"/>
                            <input type="text" id ="menuordering" name ="menuordering" value="<?= $menu_desc['menu_ordering'] ?>" style="width: 200px;margin-top: 30px;height: 25px;margin-left: -26px;" class="textAreaClass" placeholder="Orderin No" maxlength="3"/>
                            <textarea rows="3" cols="17"  id="description_menu" name="description_menu" style="resize: none;margin-top: 17px;margin-left: -26px;width:200px;height: 75px;" class="textAreaClass" placeholder="Menu Description">
                                <?= $menu_desc['menu_desc'] ?></textarea>
                            <div class="cancel" style="width:153px;margin-left: -26px"><a href="ajax.php?mod=menus&item=hours&menuid=<?=$menu_id?>&<?php echo time() ?>" class="loadMenuHours" style="width: 100px;text-decoration: none;color: white;">Change Menu Hours</a></div>

                            <div style="margin-top: 21px;color: #A2A3A3;margin-left: -26px;">To delete this menu<div>Contact us at <br/>800-648-6238</div></div>
                            <div id="copyDiv" class="cancel" style="width:153px">Copy this menu</div>
                        <?php if ( $_SESSION['admin_type'] == 'admin' ) {?>
                        <input type="submit" value="Delete this menu" name="btnDeleteMenu" id="btnDeleteMenu" style="width: 153px;margin-left: -26px;font-size: 14px;" class="cancel" />
                        <?php } ?>
                        <input type="hidden" id="allowDelete" name="allowDelete" value="0" />
                        </ul>
                        <input type="submit" value="Update" name="btnSubmit" id="btnSubmit" style="width: 100px;margin-left: 45px;" class="cancel" />
                     

                        <div id="NewAttributeLi" style="display: none;cursor:pointer"><a href="#dvAddAttributeSM" id="btnAddAttributeSM" style="color: #A2A3A3;text-decoration: none"><span style="float:left"><img src="img/add_icon.png"></span><span style="margin-left: 12px;">Create new Attribute</span></a></div>
                        <div id="ExistingAttributeLi" style="margin-left: 30px;display: none;cursor:pointer"><span style="margin-left: 12px;color: #A2A3A3;">Add Existing Attribute</span></div>
                        <div class="ulCategorydiv" style="display:none;">
                            <ul id="attr-list">


                            </ul>

                         <div style="margin-top: 135px;text-align: center;display:none" class="noAttributes">
                           <span style="display:block">No Attributes</span>
                           <span>Add Some?</span>
                        </div>
                        </div>
                        
                    </div>
                    <div id="tab2" class="tab_content">
                      
                        <div id="RelatedItemLi" style="cursor:pointer"><span style="float:left;width: 24px;height: 24px;"><img src="img/add_icon.png"></span><span style="color: #A2A3A3;">Add new Complementary Items</span></div>
                       <div class="ulCategorydiv" style="display:none;">
                            <ul id="related-list">

                            </ul>
                        <div style="text-align: center;" class="noRelatedItems">
                           <span style="display:block;margin-top: 135px;">No Complementary Items</span>
                           <span>Add Some?</span>
                        </div>
                        </div>
                        <div id="mainFancyBox" style="display:none;width: 226px;border: 2px solid #D6E2E0;background: #F9F8F8;margin-left: -20px;margin-top: 6px;">
                        <div style="text-align: center;line-height: 25px;width:226px">
                        <div style="text-align: center;margin-left: 13px;margin-bottom: 8px;color: #8A8A8A;font-size: 17px;">Add New Complementary Item</div>

                        <select class="chzn-select"  name="typeRelatedItemName" id="typeRelatedItemName" style="width:200px;">
                        <option value="">--Please Select--</option>
                        
                      
                        </select>
                        <br/>
                        <div id="displayMessage" class="alert-error" style="display: none;width: 150px;margin-left: 26px;margin-top: 15px">Item already Added</div>
                        <div style="text-align: center">or</div>
                        <div style="text-align: center">
                        <input type="button" value="Browse" id="browseRelatedItems" name="browseRelatedItems" class="cancel" style="margin-top:0px;width:120px;line-height: 26px;;height:28px;background-color: #565656"></div>
                        <input type="button" value="Add" id="addRelatedIteminProduct" name="addRelatedIteminProduct" class="cancel" style="margin-top:10px;width:120px">
                        <input type="button" value="Cancel" id="closeRelatedItemDiv" name="closeRelatedItemDiv" class="cancel" style="margin-top:10px;margin-bottom: 20px;width:120px">
                        </div>
                    </div>
                    </div>
                    
                    <div id="back-top" style="display: none"><a href="#top"><img src="img/arrowUp.png" style="width: 58px; position: absolute" id="back-img"/></a></div>
                </div>


                <?php include('submenu_table.php'); ?>
                            </table>

                        </form>
                    </body>
                </html>
               
	<div style="display: none; font-family: Arial; border: 1px solid #CCC; width: 600px;" id="dvAddAttributeSM">
		<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
			<tr style="height: 50px; background-color:#25AAE1 !important; text-align: center; vertical-align: middle;">
				<td>
					<span style="font-size: 23px; color: #FFFFFF;" id="TopHeadingSM">Add New Attribute</span>
				</td>
			</tr>
			<tr>
				<table style="background-color: #ECEDEE; width: 100%; text-align: center;" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="4"><div class="validated_error"style="display:none;color: red;margin-top: 10px;font-size: 18px;">Please fill required fields</div>
						</td>
					</tr>
					<tr style="height: 50px;">
						<td colspan="4">
                                        <select id ="attr_chooseSM" name="attr_chooseSM" style="height: 36px;width: 140px; margin-left: -15px;">
								<option value="">Please Select</option>
								<option value="1">Choose</option>
								<option value="2">Pick</option>
								<option value="3">Add</option>
								<option value="4">Create Your Own</option>
							</select>
                                        <span style="color:red; visibility: hidden; margin-left: 8px;" id="spnChooseAttrSM">*</span>
                                        <input type="text" id="txtAttTitleSM" name="txtAttTitleSM" placeholder="Display Title (Example - &quot;Choose Sauce&quot;)" style="width: 302px; text-indent: 5px; height: 30px;" /><span style="color:red; visibility: hidden; margin-left: 2px;" id="spnAttTitleReqSM">*</span>
            </td>                  
                        </tr>
                        <tr style="height: 10px;">
                                <td colspan="4">
                                        <div id="final_attr_nameSM" name="final_attr_nameSM" style="font-size: 14px;color: green;"><span id="span_attr_nameSM"></span></div>
                                </td>
                        </tr>            
                        <tr>
                                <td colspan="4">
                                        <table style="background-color: #ECEDEE; width: 100%; text-align: center; margin-left: -70px;" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
<!--									<td align="center" style="width: 25%;">
                                                                <select id ="attr_limitSM" name="attr_limitSM" style="height: 32px;width: 60px; margin-top: 14px; margin-right: -975px; display: none; " placeholder="Limit">
                                                                        <option value="">Limit</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                </select>
                                                                <span style="color:red; visibility: hidden; margin-left: 2px;" id="spnChooseLimitSM">*</span> 
                                                        </td>-->
									<td align="left" valign="middle" >
                                                                            <!--style="width: 35%;"-->
                                                                <div style="color: #25AAE1; font-size:14px; margin-left: 130px;">Charge extra if limit exceeded</div>
									</td>
                                                        <td align="left">
                                                                <div style="margin-left: 8px;">
											<input type="radio" name="chkLimitExceedSM" id="chkLimitExceedNoSM" value="0"  class="chk_style"><label for="chkLimitExceedNoSM" style="color: #25AAE1; font-size:14px;">No</label>
										 	<input type="radio" name="chkLimitExceedSM" id="chkLimitExceedYesSM" value="1" class="chk_style"><label for="chkLimitExceedYesSM" style="color: #25AAE1; font-size:14px;">Yes</label>
<!--									<input type="text" id="txtLimitExceedSM" name="txtLimitExceedSM" maxlength="4" placeholder="" style="text-indent: 5px; width: 50px; height: 25px; display:none" />
                                                                        <span style="color:red; visibility: hidden;" id="spnLimitExceedSM">*</span>-->
										</div>
									</td>
                                                        <td colspan="4">
                                                            <input type="text" id="txtLimitExceedSM" name="txtLimitExceedSM" maxlength="4" placeholder="" style="text-indent: 5px;height: 25px; width: 50px; margin-left: 5px;display:none" />
                                                            <span style="color:red; visibility: hidden;" id="spnLimitExceedSM">*</span>
                                                        </td>   

                                                        <td colspan="4">    
                                                            <select id ="attr_limitSM" name="attr_limitSM" style="height: 33px;width: 60px; display: none;margin-right: -3px" placeholder="Limit"> 
                                                                        <option value="">Limit</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                            </select>
                                                                <span style="color:red; visibility: hidden;" id="spnChooseLimitSM">*</span>
                                                        </td>
								</tr>
							</table>
						</td>
					</tr>

                                        <tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr style="height: 50px;">
						<td colspan="4">
                            <input type="text" id="txtAttNameSM" name="txtAttNameSM" placeholder="Admin Name" style="width:160px; text-indent: 5px; height: 30px;margin-top: 6px;" /><span style="color:red; visibility: hidden; margin: 2px;" id="spnNameReqSM">*</span>
						</td>
					</tr>
					<tr style="height: 5px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<span style="color: red; display: none;" id="spnDupAttSM">Attribute already exists.</span>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td style="width: 10%;">
						</td>
						<td align="left">
							<input type="checkbox" name="chkAttReqSM" id="chkAttReqSM" class="chk_style"/><label for="chkAttReqSM"  style="color: #25AAE1; font-size:14px;">Attribute Required for Ordering</label>
						</td>
						<td style="width: 10%;">
						</td>
						<td align="left">
							<input type="radio" name="chkAttAddSM" id="chkAttAddSM" value="1" checked="checked" class="chk_style"/><label for="chkAttAddSM"  style="color: #25AAE1; font-size:14px;margin-top: 8px; margin-left: -75px;">Attribute adds to price</label><br />
							<input type="radio" name="chkAttAddSM" id="chkAttTotalSM" value="2" class="chk_style"/><label for="chkAttTotalSM"  style="color: #25AAE1; font-size:14px;margin-top: 8px; margin-left: -75px;">Attribute displays total price</label>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td align="center" colspan="4" align="center">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width: 30%;">
									</td>
									<td style="width: 14%;" valign="top">
										<span style="color: #25AAE1; font-size:14px;">Layout:</span>
                                                                                <a class="hover_gray" style="padding: 2px 6px 2px 5px;border-radius: 50%;color: black;background-color: #b9b9b9;cursor: pointer;color: white;">?<i style="width:165px;margin-left:-85px">This is the method through which your customers will select options. Radio Buttons are used for making one choice. Checkboxes for multiple choices.</i></a>
									</td>
									<td>
										<input type="radio" name="rbAttSM" id="rbAttDDSM" value="1" checked="checked" class="chk_style"/><label for="rbAttDDSM"  style="color: #25AAE1; font-size:14px;margin-top: 8px;">Drop Down Menu</label><br />
										<input type="radio" name="rbAttSM" id="rbAttCBSM" value="2" class="chk_style"/><label for="rbAttCBSM"  style="color: #25AAE1; font-size:14px;margin-top: 8px;">Check Boxes</label><span style="font-size:14px;"></span><br />
										<input type="radio" name="rbAttSM" id="rbAttRBSM" value="3" class="chk_style"/><label for="rbAttRBSM"  style="color: #25AAE1; font-size:14px;margin-top: 8px;">Radio Buttons</label>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width: 5%;">
									</td>
									<td style="width: 90%;">
										<hr />
									</td>
									<td style="width: 5%;">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td style="width: 10%;">
						</td>
						<td align="left">
							<input type="text" id="txtAttSubTitleSM" name="txtAttSubTitleSM" placeholder="Example - &quot;Hot Sauce&quot;" style="width: 268px; text-indent: 5px; height: 30px;" /><span style="color:red; visibility: hidden;" id="spnTitleReqSM">*</span>
						</td>
						<td style="width: 1%;">
						</td>
						<td align="left">
							&nbsp;<input type="text" id="txtAttPriceSM" maxlength="7" name="txtAttPriceSM" placeholder=".75" style="width: 120px; text-indent: 5px; height: 30px; margin-right: 72px;" />
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td style="width: 10%;">
						</td>
						<td colspan="3" align="left">
							<input type="checkbox" name="chkAttDefSM" class="chk_style" id="chkAttDefSM"/><label for="chkAttDefSM" style="color: #25AAE1; font-size:14px;">Default Attribute?</label>
                                                        <a class="hover_gray" style="padding: 2px 6px 2px 5px;border-radius: 50%;color: black;background-color: #b9b9b9;cursor: pointer;color: white;">?<i style="width:165px;margin-left:-85px">Required for check-out. If customer neglects to make a selection, this will be the default selection.</i></a>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<table style="width: 100%; margin: 0px;" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td style="width: 40%;">
									</td>
									<td style="width: 60%; text-align: left !important;" align="left">
										<input type="button" id="btnAddAttrSM" name="btnAddAttrSM" value="Add">
                                                                                <!-- Saad Change - 25-Sept-2014-->
                                                                                <input type="button" id="btnUpdateAttrSM" name="btnUpdateAttrSM" class="btnUpdateAttrOption" value="Update" style="display:none;">
                                                                                <input type="button" id="btnCancelAttrSM" name="btnCancelAttrSM" class="btnUpdateAttrOption" value="Cancel" onclick="cancelEditAttributeOptionSM();" style="display:none;">
                                                                                &nbsp;<span style="color: red; display: none;" id="spnDupSM">Attribute already exists.</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
                                                    <div style="overflow-y:scroll;height:100px ">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width: 5%;">
									</td>
									<td style="width: 90%;">
										<input type="hidden" id="hdnSubmenuIDSM" name="hdnSubmenuIDSM" />
										<input type="hidden" id="hdnOptionsSM" name="hdnOptionsSM" />
                                                                                <input type="hidden" id="hdnEditOptionIdSM" name="hdnEditOptionIdSM" />
										<input type="hidden" id="hdnAttributesSM" name="hdnAttributesSM" />
                                                                                <input type="hidden" id="hdnAttributeUpdateSet1" name="hdnAttributeUpdateSet1" />
                                                                                <input type="hidden" id="hdnNameSM" name="hdnNameSM" />
										<table id="tblOptionsSM" style="margin-top: 5px; font-size: 12px; width: 100%; border: 1px solid #CCCCCC; background-color: #FFFFFF;" cellpadding="0" cellspacing="0">
                                                                                <tr>
                                                                                        <td style="width: 5%;">
                                                                                        </td>
                                                                                        <td style="width: 50%;" align="left">
                                                                                                <strong style="color:#25aee1;">Option</strong>
                                                                                        </td>
                                                                                        <td style="width: 15%;" align="left">
                                                                                                <strong style="color:#25aee1;">Price</strong>
                                                                                        </td>
                                                                                        <td style="width: 15%;" align="left">
                                                                                                <strong style="color:#25aee1;">Default</strong>
                                                                                        </td>
                                                                                        <td style="width: 10%;">

                                                                                        </td>
                                                                                        <td style="width: 5%;">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr style="height: 5px;">
                                                                                        <td colspan="6">
                                                                                        </td>
                                                                                </tr>
										</table>
									</td>
									<td style="width: 5%;">
									</td>
								</tr>
							</table>
                                                    </div>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width: 5%;"></td>
									<td style="width: 65%;">
										<span style="font-size: 13px;">
											Would you like to apply this to an entire Submenu?
										</span>
										<input type="checkbox" name="chkAttEntireSM" id="chkAttEntireSM" value="1" class="chk_style"/>
                                                                                <label for="chkAttEntireSM" style="color: #25AAE1; font-size:14px;margin-bottom: 15px;"></label>
									</td>
									<td style="width: 12%;" align="right">
										<input type="button" id="btnSaveSM" name="btnSaveSM" value="Save" style="margin-right: 10px; width:65px;">
									</td>
									<td>
                                                                            <input type="button" class="btnCancelSM"  value="Cancel" style="margin-right: 60px; width:65px;">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
				</table>
			</tr>
		</table>
	</div>
    <div id="popup_boxSubMenu" class="popup_box" style="width:400px;min-height:250px;">

        <div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 112px;">Save Changes?</div></div>
            <div style="margin-top: 40px;margin-left: 85px;">
                <input type="button" id="btnYesSubMenu" name="btnYesSubMenu" value="Yes" class="cancel" style="font-size: 20px;">
                <input type="button" id="btnNoSubMenu" name="btnNoSubMenu" value="No" class="cancel" style="font-size: 20px;">
            </div>

       </div>
    <div id="popup_boxMenu" class="popup_box" style="width:400px;min-height:250px;">

        <div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 112px;">Save Changes?</div></div>
            <div style="margin-top: 40px;margin-left: 85px;">
                <input type="button" id="btnYesMenu" name="btnYesMenu" value="Yes" class="cancel" style="font-size: 20px;">
                <input type="button" id="btnNoMenu" name="btnNoMenu" value="No" class="cancel" style="font-size: 20px;">
            </div>

       </div>
    
    	
        </body>
                </html>