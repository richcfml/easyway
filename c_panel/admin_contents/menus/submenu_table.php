<tr style="height:553px;" id="maintbltr">
    <td>

    </td>
</tr>
<tr>
    <td>
        <input type="submit" value="" name="btnSubmit" style="width: 150px;margin-left: 21px; background-image: url(img/uso.png);" id="btnSubmit1" onmouseover="$('#btnSubmit1').attr('style', 'width: 150px;margin-left: 21px; background-image: url(img/usoh.png);');" onmouseout="$('#btnSubmit1').attr('style', 'width: 150px;margin-left: 21px; background-image: url(img/uso.png);');" class="cancel" />
    </td>
</tr>
<tr>
    <td>
        <div class="demo" id="div1" ondrop="drop(event)" ondragover="allowDrop(event)">
            <?php
			$mColumn1Count = -1;
			$mSQLCl = "SELECT IFNULL(Column1Count, -1) AS Column1Count FROM menus WHERE id=".$menu_id;
			$mResC1 = dbAbstract::Execute($mSQLCl, 1);
			if (dbAbstract::returnRowsCount($mResC1, 1)>0)
			{
				$mRowC1 = dbAbstract::returnObject($mResC1, 1);
				$mColumn1Count = $mRowC1->Column1Count;
			}
            $mLoopCount = 0;
            $mCat_ID;
            $mRowCount = dbAbstract::returnRowsCount($mRes, 1);
            $mHalfCount = ceil($mRowCount / 2);
            $mDivider = $mHalfCount;
            if ($mColumn1Count>=0)
            {
                    $mDivider = $mColumn1Count;
            }
			else
			{
				dbAbstract::Update("UPDATE menus SET Column1Count=".$mDivider." WHERE id=".$menu_id, 1);
			}

            $mColumn1Flag = false;
            $mColumn = 1;
            
            $subcatIDs="";
            $getcategories = array();
            while ($mRow1 = dbAbstract::returnAssoc($mRes, 1)) {
                $subcatIDs.=$mRow1["cat_id"].",";
                $getcategories[]=$mRow1;
            }
            $subcatIDs=substr($subcatIDs,0,-1);
            $mSQLPr = "SELECT * FROM product WHERE sub_cat_id in( " . $subcatIDs . ") order by SortOrder";
            $mResPr = dbAbstract::Execute($mSQLPr, 1);
            $getItem = array();
            $catCount = count($getcategories);
            while($getRecord = dbAbstract::returnAssoc($mResPr, 1))
            {
                $getItem[]=$getRecord;
            }
            $itemCount = count($getItem);
            for($i=0; $i<=$catCount-1;$i++)
            {
                for($j=0; $j<=$itemCount-1;$j++)
                {
                    if($getcategories[$i]['cat_id'] ==$getItem[$j]['sub_cat_id'])
                    {
                        $getcategories[$i]['products'][$j] = $getItem[$j];
                    }
                }
            }

          foreach($getcategories as $mRow)
          {
                if ($mLoopCount == 0) {
            ?>
                    <ul id="sortable1" class="connectedSortable" sub_cat="<?=$mRow["cat_id"]?>" style="width: 38%;margin-left: 20px">
                <?php
                }

                if ($mLoopCount == $mDivider) {
					$mColumn1Flag = true;
					$mColumn = 2;
                ?>
                </ul>
                <ul id="sortable2" class="connectedSortable" sub_cat="<?=$mRow["cat_id"]?>" style="width: 35%;margin-left: 90px">
                <?php
                }
                ?>
                <li id="item<?= $mLoopCount ?>" class="lItem" style="margin-top: 10px;background: white;width: 300px;" catid="<?= $mRow["cat_id"] ?>">
                    <div class="menu_heading_top" sub_cat="<?=$mRow["cat_id"]?>">
                   <img src="img/move.png" sub_cat="<?=$mRow["cat_id"]?>" alt="Move" data-tooltip="Move" id="imgMove" class="MoveImg"/>
                   
                   <div id="menu_operations" sub_cat="<?=$mRow["cat_id"]?>" style="float:left;height:22px" class="menu_operations">
                       	<img style="padding: 3px;" sub_cat="<?=$mRow["cat_id"]?>" src="img/pencil.png" data-tooltip="Edit" id="<?= $mRow["cat_id"] ?>" class="submenu_edit" alt="<?= stripcslashes($mRow["cat_name"]) ?>" cat_id ="<?= $mRow["cat_id"] ?>" desc="<?= $mRow["cat_des"] ?>"/>
                       	
                        <a href="?mod=new_menu&item=addproduct_new&sub_cat=<?= $mRow["cat_id"]?>" style="text-decoration: none; padding: 0px !important;" alt="<?= $mRow["cat_id"] ?>" data-tooltip="Add Item" id="submenu_Add_<?= $mRow["cat_id"] ?>" class="submenu_item_Add" sub_cat="<?=$mRow["cat_id"]?>">
                        <img style="padding: 3px;" sub_cat="<?=$mRow["cat_id"]?>" data-tooltip="Add Item" src="img/plus.png" alt="Add Item"/></a>
                       	<img style="padding: 3px;" sub_cat="<?=$mRow["cat_id"]?>" src="img/copy.png" alt="<?= $mRow["cat_id"] ?>" data-tooltip="Copy" id="submenu_Copy_<?= $mRow["cat_id"] ?>" class="submenu_item_Copy"/>
		       			<img style="padding: 2px;" sub_cat="<?=$mRow["cat_id"]?>" column="<?=$mColumn?>" rid="<?=$Objrestaurant->id?>" mid="<?=$menu_id?>" src="img/delete_icon2.png" alt="<?= $mRow["cat_id"] ?>" data-tooltip="Delete" id="submenu_Delete_<?= $mRow["cat_id"] ?>" class="submenu_item_Delete"/>
                    </div>
                   <div sub_cat="<?=$mRow["cat_id"]?>" style="text-align: center;color: #5B5B5B;width: 300px;float: left;margin-top: 4px; background:#d0d0d0; ">
				   	<span class ="lblCat" sub_cat="<?=$mRow["cat_id"]?>" style="font-weight: bold; font-size: 15px;cursor: pointer;" id="<?= $mRow["cat_id"] ?>">
						<table style="width: 90%; margin: 0px;" cellpadding="0" cellspacing="0" border="0" sub_cat="<?=$mRow["cat_id"]?>">
							<tr sub_cat="<?=$mRow["cat_id"]?>">
								<td style="width: 40%; text-align: right;" align="right" sub_cat="<?=$mRow["cat_id"]?>">
									<?php 
									if ($mRow['status'] == 1) 
									{
									?>
										<img src="img/enable_submenu.png" width="16" height="16" border="0" data-tooltip="Enabled" class ="rdb_statusSM" alt="<?= $mRow["cat_id"] ?>" status="<?= $mRow["status"] ?>" style="cursor: pointer; cursor: hand;" sub_cat="<?=$mRow["cat_id"]?>" />	
									<?php 
									} 
									else if ($mRow['status'] == 0) 
									{
									?>
										<img src="img/disable_submenu.png" width="16" height="16" border="0" data-tooltip="Disabled" class ="rdb_statusSM" alt="<?= $mRow["cat_id"] ?>" status="<?= $mRow["status"] ?>" style="cursor: pointer; cursor: hand;" sub_cat="<?=$mRow["cat_id"]?>"/>
									<?php 
									} 
									?>
								</td>
								<td style="width: 2%;" sub_cat="<?=$mRow["cat_id"]?>">
								</td>
								<td style="width: 58%; text-align: left;" align="left;" sub_cat="<?=$mRow["cat_id"]?>">
									<?php
									if ($mRow['status'] == 0) 
									{
									?>
										<span style="color: #E8E8E8;" id="spn<?=$mRow['cat_id']?>" sub_cat="<?=$mRow["cat_id"]?>">
									<?php
									}
									else
									{
									?>
										<span id="spn<?=$mRow['cat_id']?>" sub_cat="<?=$mRow["cat_id"]?>">
									<?php
									}
									?>
			                				<?= wordwrap(str_replace("\\", "", $mRow["cat_name"]), 10, "\n", true); ?> 
									</span>		
								</td>
							</tr>
						</table>
                    </span>

                                                                    <i class="fa fa-minus collapseImage"  data-tooltip="collapse Menu" <?php if(dbAbstract::returnRowsCount($mResPr, 1) == 0){ ?>style="display:none"<?}?>></i>

                   </div>
                    <span id="lblCatID" style="display: none;" sub_cat="<?=$mRow["cat_id"]?>"><?= $mRow["cat_id"] ?></span>

                 </div>
                    <div class="toggleSubmenu" sub_cat="<?=$mRow["cat_id"]?>">
                    <?php $mRowPr1 = $mRow['products'];
                    if (count($mRowPr1)>0)
					{
                   		foreach ($mRowPr1 as $mRowPr) 
						{
							if($mRowPr['signature_sandwitch_id'] > 0){
							  $ss_obj  = dbAbstract::ExecuteObject("select start_date,end_date from bh_signature_sandwitch where id='".$mRowPr['signature_sandwitch_id']."'");
							  
							}
							if($mRowPr['signature_sandwitch_id']==0 || ($mRowPr['signature_sandwitch_id'] > 0 && strtotime($ss_obj->end_date) >= strtotime(date('Y-m-d')))){
                    ?>
                        <ul id="tblS" class="clsS" sub_cat="<?=$mRow["cat_id"]?>">
                            <li id="liPrd" class="liPrd" sub_cat="<?=$mRow["cat_id"]?>">
                                <div class ="item_data" id="<?= $mRowPr["prd_id"] ?>" sub_cat="<?=$mRow["cat_id"]?>">
                                    <table cellpadding="0" cellspacing="0" 
                                    	style="margin-bottom:7px; font-size:12px; width:100%; 
                                        background-color:<?=(($mRowPr['signature_sandwitch_id'] > 0)? '#000':'#F0F0F0')?>;" id="tblProduct" class="tblProduct">

                                        <tr sub_cat="<?=$mRow["cat_id"]?>">
                                            <td style=" font-size: 12px;" sub_cat="<?=$mRow["cat_id"]?>">
                                                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 45px;">
                                                    <tr style="height: 20px;" sub_cat="<?=$mRow["cat_id"]?>">
                                                        <td valign="top" style="width: 85%;" sub_cat="<?=$mRow["cat_id"]?>">
                                                            <div class="enable-menu-options" sub_cat="<?=$mRow["cat_id"]?>">
                                                            <?php
                                                                if ($mRowPr["item_image"]) {
                                                                ?>
                                                                    <div id="dvIcon" sub_cat="<?=$mRow["cat_id"]?>">
                                                                        <img src="img/imageyes.png" alt="Image" title="Image" id="imgYesImage" sub_cat="<?=$mRow["cat_id"]?>"/>
                                                                    </div>
                                                                <?php
                                                                }
                                                                ?>
                                                                <div id="dvOptions" style="display: none;height:22px" class="dvOptions" sub_cat="<?=$mRow["cat_id"]?>">
                                                                    <img style="padding: 3px;" src="img/pencil.png" data-tooltip="Edit" alt="<?= $mRowPr["prd_id"] ?>"  id="imgEdit" class="editItem" sub_cat="<?=$mRow["cat_id"]?>" <?php if($mRowPr["signature_sandwitch_id"]!= 0 && $mRowPr["signature_sandwitch_id"]!= ""){ ?> signature_sandwitch="1" <?php } ?> />
                                                                    <img style="padding: 3px;" src="img/copy.png" alt="<?= $mRowPr["prd_id"] ?>" data-tooltip="Copy" id="imgCopy" class="copyItem" sub_cat="<?=$mRow["cat_id"]?>"/>
                                                                    <img style="padding: 2px;" src="img/delete_icon2.png" alt="<?= $mRowPr["prd_id"] ?>" data-tooltip="Delete" id="imgDelete" class="deleteItem" sub_cat="<?=$mRow["cat_id"]?>"/>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td sub_cat="<?=$mRow["cat_id"]?>" style="width: 15%;">
                                                            <img src="img/move.png" alt="Move" title="Move" id="imgMove" style="float: left;" class="imgMove" sub_cat="<?=$mRow["cat_id"]?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr sub_cat="<?=$mRow["cat_id"]?>">
                                                        <td style="width: 85%; float:left" sub_cat="<?=$mRow["cat_id"]?>">
                                                            <?php 
                                                                if ($mRowPr['status'] == 1) 
                                                                {
                                                            ?>
                                                                <div style="float: left;margin-right: 10px;margin-left: 30px;" sub_cat="<?=$mRow["cat_id"]?>">
                                                                    <img src="img/enable.png" width="16" height="16" border="0" data-tooltip="Enabled" class ="rdb_status" alt="<?= $mRowPr["prd_id"] ?>" status="<?= $mRowPr["status"] ?>" sub_cat="<?=$mRow["cat_id"]?>"/>
                                                                </div>
                                                            <?php 
                                                                } 
                                                                else if ($mRowPr['status'] == 0) 
                                                                {
                                                            ?>
                                                                <div style="float: left;margin-right: 10px;margin-left: 30px;" sub_cat="<?=$mRow["cat_id"]?>">
                                                                    <img src="img/disable.png" width="16" height="16" border="0" data-tooltip="Disabled" class ="rdb_status" alt="<?= $mRowPr["prd_id"] ?>" status="<?= $mRowPr["status"] ?>" sub_cat="<?=$mRow["cat_id"]?>"/>
                                                                </div>
                                                            <?php 
                                                                } 
                                                            ?>
                                                            <div style="margin-left: 55px; font-weight: bold; <?=(($mRowPr['signature_sandwitch_id'] > 0)? 'text-align:left;color:#FFF;width:100%':'')?>" sub_cat="<?=$mRow["cat_id"]?>" class="<?=(($mRowPr["status"] == 0)? 'disable-menu':'enable-menu')?> <?=(($mRowPr['signature_sandwitch_id'] > 0)? 'ss_prodTitle':'')?>">
                                                              <div style="width:150px">
                                                                    <?= wordwrap($mRowPr["item_title"], 10, "\n", true); ?>
                                                              </div>
                                                            </div>
                                                            <span id="lblProductID" sub_cat="<?=$mRow["cat_id"]?>" style="display: none;"><?= $mRowPr["prd_id"] ?></span>
                                                        </td>
                                                        <td style="font-size: 12px; float:left; width: 15%;" sub_cat="<?=$mRow["cat_id"]?>">
                                                            <div sub_cat="<?=$mRow["cat_id"]?>" class="<?=(($mRowPr["status"] == 0)? 'disable-menu':'enable-menu')?>" <?=(($mRowPr['signature_sandwitch_id'] > 0)? 'style="color:#FFF;margin-top: 5px;"':'')?>>
                                                                <?= $currency . $mRowPr["retail_price"] ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr sub_cat="<?=$mRow["cat_id"]?>">
                                                        <td style="font-style: italic; width: 100%;word-wrap: break-word;" sub_cat="<?=$mRow["cat_id"]?>" colspan="2">
                                                            <?php
                                                            if($mRowPr['signature_sandwitch_id'] > 0)
                                                            {
                                                            echo '<div style="margin-left: 55px;" class="ss_prodDates">Featured Sandwich '.
                                                                    date('m/d',strtotime($ss_obj->start_date)).' - '.date('m/d',strtotime($ss_obj->end_date)).
                                                                '</div>';
                                                            }
                                                            ?>
                                                            <div sub_cat="<?=$mRow["cat_id"]?>" style="margin-left:55px;width: 200px; <?=(($mRowPr['signature_sandwitch_id']>0)? 'color:#c3bcaf':'')?>" class="<?=(($mRowPr["status"]==0)? 'disable-menu':'enable-menu')?>">
                                                                <?php
                                                                echo str_replace("&#169;", "<sub>&#169;</sub>", str_replace("&#8482;", "<sub>&#8482;</sub>", str_replace("&#174;", "<sub>&#174;</sub>", getProductDescription(wordwrap($mRowPr["item_des"], 20, "\n", false)))));
																?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td align="right" sub_cat="<?=$mRow["cat_id"]?>">

                                            </td>
                                        </tr>
                                        <tr style="height: 5px;" sub_cat="<?=$mRow["cat_id"]?>">
                                            <td colspan="2" sub_cat="<?=$mRow["cat_id"]?>">
                                            </td>
                                        </tr>
                                        <tr sub_cat="<?=$mRow["cat_id"]?>">

                                        </tr>
                                        <tr sub_cat="<?=$mRow["cat_id"]?>">
                                            <td sub_cat="<?=$mRow["cat_id"]?>"><div style="margin-left: 25px;" sub_cat="<?=$mRow["cat_id"]?>">
                                            <?php
                                                            if (strpos($mRowPr["item_type"], '0') !== false) {
                                                                echo '<img src="img/new_icon22.png" style="margin-left: 10px;" id="new1" data-tooltip="New"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '1') !== false) {
                                                                echo '<img src="img/vegan_icon22.png" style="margin-left: 10px;" id ="vegan1" data-tooltip="Vegan"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '2') !== false) {
                                                                echo '<img src="img/POPULAR_icon22.png" style="margin-left: 10px;" data-tooltip="Popular" id="Popular1"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '3') !== false) {
                                                                echo '<img src="img/nutfree_icon22.png" style="margin-left: 10px;" data-tooltip="Nut Free" id="nut_free1"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '4') !== false) {
                                                                echo '<img src="img/glutenfree_icon22.png" style="margin-left: 10px;" data-tooltip="Gluten Free" id="glutenfree1"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '5') !== false) {
                                                                echo '<img src="img/LOWFAT_icon22.png" style="margin-left: 10px;" data-tooltip="Low Fat" id ="LOWFAT1"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '6') !== false) {
                                                                echo '<img src="img/vegetarian_icon22.png" style="margin-left: 10px;" data-tooltip="Vegetarian" id ="Vegetarian1"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '7') !== false) {
                                                                echo '<img src="img/spicy_icon22.png" style="margin-left: 10px;" data-tooltip="mild" class ="spicy1"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '8') !== false) {
                                                                echo '<img src="img/spicy_icon22.png" style="margin-left: 10px;" data-tooltip="Medium" class ="spicy1"/>';
                                                                echo '<img src="img/spicy_icon22.png" style="margin-left: 10px;" data-tooltip="Medium" class ="spicy1"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], '9') !== false) {
                                                                echo '<img src="img/spicy_icon22.png" style="margin-left: 10px;" data-tooltip="Hot" class ="spicy1"/>';
                                                                echo '<img src="img/spicy_icon22.png" style="margin-left: 10px;" data-tooltip="Hot" class ="spicy1"/>';
                                                                echo '<img src="img/spicy_icon22.png" style="margin-left: 10px;" data-tooltip="Hot" class ="spicy1"/>';
                                                            }
                                                            if (strpos($mRowPr["item_type"], 'B') !== false) {
                                                                echo '<img src="img/bh_item1.png?v4" style="margin-left: 10px;" data-tooltip="BH Item" class ="spicy1"/>';
                                                            }
                                            ?>      </div>
                                                        </td>

                                                    </tr>
                                                    <tr style="height: 20px;" sub_cat="<?=$mRow["cat_id"]?>">
                                                        <td colspan="2" sub_cat="<?=$mRow["cat_id"]?>">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </li>
                                    </ul>

                    <?php
							}
						}
					}
					else
					{
                    ?>
					 	<ul id="tblS" class="clsS" style="min-height: 3px;" sub_cat="<?=$mRow["cat_id"]?>">
                        </ul>
					<?php
					}
					?>
                   </div>
                </li>
                <?php
                                                        $mLoopCount++;
                                                        if ($mLoopCount == $mRowCount) {
                ?>
                                                        </ul>
            <?php
                                                        }
			}
			if ($mColumn1Flag == false)
			{
			?>
                </ul>
                <ul id="sortable2" class="connectedSortable" style="width: 35%;margin-left: 90px"></ul>
			<?php
			}
            ?>
        </div>
    </td>
</tr>
<tr>
    <td>
        <input type="submit" value=""  name="btnSubmit" style="width: 150px; margin-left: 21px;margin-bottom: 25px; background-image: url(img/uso.png);" id="btnSubmit2" onmouseover="$('#btnSubmit2').attr('style', 'width: 150px; margin-left: 21px;margin-bottom: 25px; background-image: url(img/usoh.png);');" onmouseout="$('#btnSubmit2').attr('style', 'width: 150px; margin-left: 21px;margin-bottom: 25px; background-image: url(img/uso.png);');" class="cancel" />
    </td>
</tr>

