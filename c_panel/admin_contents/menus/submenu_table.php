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
        <div class="demo">
            <?php
			$mColumn1Count = -1;
			$mSQLCl = "SELECT IFNULL(Column1Count, -1) AS Column1Count FROM menus WHERE id=".$menu_id;
			$mResC1 = mysql_query($mSQLCl);
			if (mysql_num_rows($mResC1)>0)
			{
				$mRowC1 = mysql_fetch_object($mResC1);
				$mColumn1Count = $mRowC1->Column1Count;
			}
            $mLoopCount = 0;
            $mCat_ID;
            $mRowCount = mysql_num_rows($mRes);
            $mHalfCount = ceil($mRowCount / 2);
            $mDivider = $mHalfCount;
            if ($mColumn1Count>=0)
            {
                    $mDivider = $mColumn1Count;
            }
			else
			{
				mysql_query("UPDATE menus SET Column1Count=".$mDivider." WHERE id=".$menu_id);
			}

            $mColumn1Flag = false;
            $mColumn = 1;
            
            $subcatIDs="";
            $getcategories = array();
            while ($mRow1 = mysql_fetch_assoc($mRes)) {
                $subcatIDs.=$mRow1["cat_id"].",";
                $getcategories[]=$mRow1;
            }
            //echo "<pre>";print_r($getRecord);
            $subcatIDs=substr($subcatIDs,0,-1);
            $mSQLPr = "SELECT * FROM product WHERE sub_cat_id in( " . $subcatIDs . ") order by SortOrder";
            $mResPr = mysql_query($mSQLPr);
            $getItem = array();
            $catCount = count($getcategories);
            while($getRecord = mysql_fetch_assoc($mResPr))
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
                    <ul id="sortable1" class="connectedSortable" style="width: 38%;margin-left: 20px">
                <?php
                }

                if ($mLoopCount == $mDivider) {
					$mColumn1Flag = true;
					$mColumn = 2;
                ?>
                </ul>
                <ul id="sortable2" class="connectedSortable" style="width: 35%;margin-left: 90px">
                <?php
                }
                ?>
                <li id="item<?= $mLoopCount ?>" class="lItem" style="margin-top: 10px;background: white;width: 300px;">
                    <div class="menu_heading_top">
                   <img src="img/move.png" alt="Move" data-tooltip="Move" id="imgMove" class="MoveImg"/>
                   <div id="menu_operations" style="float:left;height:22px" class="menu_operations">
                       	<img style="padding: 3px;" src="img/pencil.png" data-tooltip="Edit" id="<?= $mRow["cat_id"] ?>" class="submenu_edit" alt="<?= stripcslashes($mRow["cat_name"]) ?>" cat_id ="<?= $mRow["cat_id"] ?>" desc="<?= $mRow["cat_des"] ?>"/>
                       	<a href="?mod=new_menu&item=addproduct_new&sub_cat=<?= $mRow["cat_id"]?>" style="text-decoration: none; padding: 0px !important;" alt="<?= $mRow["cat_id"] ?>" data-tooltip="Add Item" id="submenu_Add_<?= $mRow["cat_id"] ?>" class="submenu_item_Add"><img style="padding: 3px;" data-tooltip="Add Item" src="img/plus.png" alt="Add Item"/></a>
                       	<img style="padding: 3px;" src="img/copy.png" alt="<?= $mRow["cat_id"] ?>" data-tooltip="Copy" id="submenu_Copy_<?= $mRow["cat_id"] ?>" class="submenu_item_Copy"/>
		       			<img style="padding: 2px;" column="<?=$mColumn?>" rid="<?=$Objrestaurant->id?>" mid="<?=$menu_id?>" src="img/delete_icon2.png" alt="<?= $mRow["cat_id"] ?>" data-tooltip="Delete" id="submenu_Delete_<?= $mRow["cat_id"] ?>" class="submenu_item_Delete"/>
                    </div>
                   <div style="text-align: center;color: #5B5B5B;width: 300px;float: left;margin-top: 4px; background:#d0d0d0; ">
<!------------------------------------------------------------------------------------------>                       
				   	<span class ="lblCat" style="font-weight: bold; font-size: 15px;cursor: pointer;" id="<?= $mRow["cat_id"] ?>">
<!------------------------------------------------------------------------------------------->                                            
						<table style="width: 90%; margin: 0px;" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td style="width: 40%; text-align: right;" align="right">
									<?php 
									if ($mRow['status'] == 1) 
									{
									?>
										<img src="img/enable_submenu.png" width="16" height="16" border="0" data-tooltip="Enabled" class ="rdb_statusSM" alt="<?= $mRow["cat_id"] ?>" status="<?= $mRow["status"] ?>" style="cursor: pointer; cursor: hand;" />	
									<?php 
									} 
									else if ($mRow['status'] == 0) 
									{
									?>
										<img src="img/disable_submenu.png" width="16" height="16" border="0" data-tooltip="Disabled" class ="rdb_statusSM" alt="<?= $mRow["cat_id"] ?>" status="<?= $mRow["status"] ?>" style="cursor: pointer; cursor: hand;"/>
									<?php 
									} 
									?>
								</td>
								<td style="width: 2%;">
								</td>
								<td style="width: 58%; text-align: left;" align="left;">
									<?php
									if ($mRow['status'] == 0) 
									{
									?>
										<span style="color: #E8E8E8;" id="spn<?=$mRow['cat_id']?>">
									<?php
									}
									else
									{
									?>
										<span id="spn<?=$mRow['cat_id']?>">
									<?php
									}
									?>
			                				<?= wordwrap(str_replace("\\", "", $mRow["cat_name"]), 10, "\n", true); ?> 
									</span>		
								</td>
							</tr>
						</table>
                    </span>

                   <i class="fa fa-minus collapseImage"  data-tooltip="collapse Menu" <?if(mysql_num_rows($mResPr) == 0){ ?>style="display:none"<?}?>></i>

                   </div>
                    <span id="lblCatID" style="display: none;"><?= $mRow["cat_id"] ?></span>

                 </div>
                    <div style="width: 300px; height: 10px; margin-top: 2px;"> </div>
                    <div class="toggleSubmenu">
                    <?php $mRowPr1 = $mRow['products'];
                    if (count($mRowPr1)>0)
					{
                   		foreach ($mRowPr1 as $mRowPr) 
						{
                    ?>
                        <ul id="tblS" class="clsS">
                            <li id="liPrd" class="liPrd">
                                <div class ="item_data" id="<?= $mRowPr["prd_id"] ?>">
                                    <table  cellpadding="0" cellspacing="0" style="margin-bottom: 7px; font-size: 12px; width: 100%;background-color: #F0F0F0;" id="tblProduct" class="tblProduct">

                                        <tr>
                                            <td style=" font-size: 12px;">
                                                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 45px;">
                                                      <tr style="height: 20px;">
                                                            <td valign="top" width="100%">

                                                                <div class="enable-menu-options">
                                                                <?php
                                                                if ($mRowPr["item_image"]) {
                                                                ?>
                                                                    <div id="dvIcon">
                                                                        <img src="img/imageyes.png" alt="Image" title="Image" id="imgYesImage"/>
                                                                    </div>
                                                                <?php
                                                                }
                                                                ?>
                                                                <div id="dvOptions" style="display: none;height:22px" class="dvOptions">
                                                                    <img style="padding: 3px;" src="img/pencil.png" data-tooltip="Edit" alt="<?= $mRowPr["prd_id"] ?>"  id="imgEdit" class="editItem"/>
                                                                    <img style="padding: 3px;" src="img/copy.png" alt="<?= $mRowPr["prd_id"] ?>" data-tooltip="Copy" id="imgCopy" class="copyItem"/>
                                                                    <img style="padding: 2px;" src="img/delete_icon2.png" alt="<?= $mRowPr["prd_id"] ?>" data-tooltip="Delete" id="imgDelete" class="deleteItem"/>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <img src="img/move.png" alt="Move" title="Move" id="imgMove" style="float: left;" class="imgMove"/>
                                                        </td>
                                                    </tr>
                                                <tr>
                                                    <td style="width: 200px;float:left">
                                                        <? if ($mRowPr['status'] == 1) {
                                                        ?>
                                                                <div style="float: left;margin-right: 10px;margin-left: 30px;">
                                                                    <img src="img/enable.png" width="16" height="16" border="0" data-tooltip="Enabled" class ="rdb_status" alt="<?= $mRowPr["prd_id"] ?>" status="<?= $mRowPr["status"] ?>"/>
                                                                </div>
                                                        <? } else if ($mRowPr['status'] == 0) {
                                                        ?>
                                                                <div style="float: left;margin-right: 10px;margin-left: 30px;;">
                                                                    <img src="img/disable.png" width="16" height="16" border="0" data-tooltip="Disabled" class ="rdb_status" alt="<?= $mRowPr["prd_id"] ?>" status="<?= $mRowPr["status"] ?>"/>
                                                                </div>
                                                        <? } ?>
                                                            <div style="margin-left: 55px; font-weight: bold;" <? if ($mRowPr["status"] == 0) { ?> class="disable-menu" <? } else { ?>class="enable-menu"<? } ?>><?= wordwrap($mRowPr["item_title"], 10, "\n", true); ?></div>
                                                            <span id="lblProductID" style="display: none;"><?= $mRowPr["prd_id"] ?></span>
                                                        </td>
                                                        <td style=" font-size: 12px;float:left;margin-left: 20px;">


                                                            <div <? if ($mRowPr["status"] == 0) { ?> class="disable-menu" <? } { ?>class="enable-menu"<? } ?>><?= $currency . $mRowPr["retail_price"] ?></div>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-style: italic">
                                                            <div style="margin-left: 55px;"<? if ($mRowPr["status"] == 0) { ?> class="disable-menu" <? } else { ?>class="enable-menu"<? } ?>><?= wordwrap($mRowPr["item_des"], 20, "\n", true); ?></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td align="right">

                                            </td>
                                        </tr>
                                        <tr style="height: 5px;">
                                            <td colspan="2">
                                            </td>
                                        </tr>
                                        <tr>

                                        </tr>
                                        <tr>
                                            <td><div style="margin-left: 25px;">
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
                                            ?>      </div>
                                                        </td>

                                                    </tr>
                                                    <tr style="height: 20px;">
                                                        <td colspan="2">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </li>
                                    </ul>

                    <?php
                    	}
					}
					else
					{
                    ?>
					 	<ul id="tblS" class="clsS" style="min-height: 3px;">
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

