  <?  if ($mod=='resturants') { ?>
		<!-- pull down menu-->
       <section class="sub_menu_container" id="sub_menu_contain">
			<div id="sub_menu">
				<ul>
					<?php
						$objCategory->menu_id = $menuid;
						 // used in header
	$categoryid = (isset($_GET['category']) ? $_GET['category']:"");
						$categories = $objCategory->getcategories();
					 
						for($i = 0; $i < count($categories); $i++) {
							$class = '';
							$category = $categories[$i];
						
						  if($i == 0 && $categoryid == "") {
								$categoryid = $category->cat_id;
								$class = ' current_menulist';
								
							} elseif($categoryid == $category->cat_id) {
								$class = ' current_menulist';
							}
					?>
					<li>
						<a class="submenu <?php echo $class; ?>" category_id=<?php echo $category->cat_id; ?> href="<?=$SiteUrl?><?= $objRestaurant1->url ."/"?>?menuid=<?=$menuid; ?>&category=<?php echo $category->cat_id; ?>">
							<?php echo stripslashes($category->cat_name); ?>
						</a>
					</li>
					<? } ?>
				</ul>
			</div>
		</section>
		<section class="pull_conatiner" >
			<div id="menu_pull">
				<div class="pull_txt">Pull Up Menu</div>
				<div class="pull_icon_conatiner">
					<div class="pull_menu"></div>
					<div class="pull_arrow"></div>
				</div>
			</div>
		</section>
    <div class="modal-backdrop fade in"></div>
	<?	} ?>
		
     