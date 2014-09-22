<?
session_start();
?>
<link href="../../css/adminMain.css" rel="stylesheet" type="text/css" />

<?
if ($_SESSION['admin_type'] != 'admin' && $_SESSION['admin_type'] != 'reseller') {
    die("Invalid access");
}

include("../../../includes/config.php");
if (empty($_REQUEST['reseller_id'])) {
    die("Please select a reseller");
}
$reseller_id = $_REQUEST['reseller_id'];
$action = "add";
$row_to_update = array(
    "settings_id" => 0
    , "return_url" => ""
    , "update_return_url" => ""
    , "product_id" => ""
    , "hosted_page_url" => ""
    , "api_access_key" => ""
    , "site_shared_key" => ""
    , "credit_card_orders_quantity" => ""
    , "credit_card_orders_amount" => ""
    , "cash_orders_quantity" => ""
    , "cash_orders_amount" => ""
    , "rapid_reorder_text_messages" => ""
    , "premium_account" => ""
    , "yelp_review_request" => ""
    , "yelp_restaurant_url" => ""
);

$return_url = "http://easywayordering.com/self-signup-wizard/index.php";
$update_return_url = "http://easywayordering.com/self-signup-wizard/handle_update_payment.php";
$message = "";

if (isset($_REQUEST["action"])) {
    $action = $_REQUEST["action"];
    $chargify_product_id = 0;

    extract($_REQUEST);
    if (isset($_REQUEST['premium_account'])) {
        $premium_account = 1;
    } else {
        $premium_account = 0;
    }
    if (isset($_REQUEST['yelp_review_request']) && !empty($_REQUEST['yelp_restaurant_url'])) {
        $yelp_review_request = 1;
    } else {
        $yelp_review_request = 0;
    }
    if ($_REQUEST['action'] == 'delete') {
        mysql_query(
                "DELETE FROM chargify_products
				WHERE settings_id='$chargify_product_id' AND user_id=$reseller_id"
        );
        $message = "1 product deleted";
    }else if($_REQUEST['action'] == 'enable'){
        mysql_query(
                "Update chargify_products set status = 1
				WHERE settings_id='$chargify_product_id' AND user_id=$reseller_id"
        );
    }else if($_REQUEST['action'] == 'disable'){
        mysql_query(
                "Update chargify_products set status = 0
				WHERE settings_id='$chargify_product_id' AND user_id=$reseller_id"
        );
    }

     else if ($_REQUEST['action'] == 'edit') {
        
        if (isset($_REQUEST["submit"])) {
            
            mysql_query(
                    "UPDATE chargify_products
					SET user_id=$reseller_id
						,product_id='$product_id'
						,return_url='$return_url'
						,update_return_url='$update_return_url'
						,hosted_page_url='$hosted_page_url'
						,api_access_key='$api_access_key'
						,site_shared_key='$site_shared_key'
						,credit_card_orders_quantity='$credit_card_orders_quantity'
						,credit_card_orders_amount='$credit_card_orders_amount'
						,cash_orders_quantity='$cash_orders_quantity'
						,cash_orders_amount='$cash_orders_amount'
						,rapid_reorder_text_messages='$rapid_reorder_text_messages'
                                                ,premium_account='$premium_account'
                                                ,yelp_review_request='$yelp_review_request'
                                                ,yelp_restaurant_url='$yelp_restaurant_url'
					WHERE settings_id='$chargify_product_id' AND user_id=$reseller_id
					"
            );
            $message = "Product Updated";
            $action = "add";
        } else {
            $row_to_update = mysql_query(
                            "SELECT settings_id
						,product_id
						,return_url
						,hosted_page_url
						,api_access_key
						,site_shared_key
						,update_return_url
						,credit_card_orders_quantity
						,credit_card_orders_amount
						,cash_orders_quantity
						,cash_orders_amount
						,rapid_reorder_text_messages
                                                ,premium_account
                                                ,yelp_review_request
                                                ,yelp_restaurant_url
					FROM chargify_products 
					WHERE settings_id='$chargify_product_id' AND user_id=$reseller_id"
            );
            $row_to_update = mysql_fetch_assoc($row_to_update);
        }
    } else if ($_REQUEST['action'] == 'add') {
        mysql_query(
                "INSERT INTO chargify_products (
					user_id
					,product_id
					,return_url
					,update_return_url
					,hosted_page_url
					,api_access_key
					,site_shared_key
					,credit_card_orders_quantity
					,credit_card_orders_amount
					,cash_orders_quantity
					,cash_orders_amount
					,rapid_reorder_text_messages
                                        ,premium_account
                                        ,yelp_review_request
                                        ,yelp_restaurant_url
				) VALUES (
					$reseller_id
					,".trim($product_id)."
					,'$return_url'
					,'$update_return_url'
					,'$hosted_page_url'
					,'$api_access_key'
					,'$site_shared_key'
					,'$credit_card_orders_quantity'
					,'$credit_card_orders_amount'
					,'$cash_orders_quantity'
					,'$cash_orders_amount'
					,'$rapid_reorder_text_messages'
					,'$premium_account'
					,'$yelp_review_request'
					,'$yelp_restaurant_url'
				)
				"
        );
        $message = "Product Added";
    }
}
extract($row_to_update);
$chargify_products_query = mysql_query("
		SELECT settings_id
			,product_id
			,return_url
			,hosted_page_url
			,api_access_key
			,site_shared_key
			,update_return_url 
			,credit_card_orders_quantity
			,credit_card_orders_amount
			,cash_orders_quantity
			,cash_orders_amount
			,rapid_reorder_text_messages
                        ,premium_account
                        ,yelp_review_request
                        ,yelp_restaurant_url
		FROM chargify_products 
		WHERE user_id=$reseller_id
	");
$return_url = "http://easywayordering.com/self-signup-wizard/index.php";
$update_return_url = "http://easywayordering.com/self-signup-wizard/handle_update_payment.php";
?>
<body style="background-color:#FFFFFF">
    <div id="main_heading">
		Chargify Products List 
    </div>
    <? if ($_SESSION['admin_type'] == 'admin') {
 ?>
        <form action="?action=<?= $action ?>&reseller_id=<?= $reseller_id ?>" method="post" name="addlicense" enctype="multipart/form-data">
            <table  width="100%" border="0"  cellpadding="4" cellspacing="0">
                <tr align="left" valign="top">
                    <td class="Width" colspan="2">
                        <strong>
                        <?
                        $text = ($action == "edit" || $action == "add") ? $action : "add";
                        ucfirst($text)
                        ?> Product:
                    </strong>
                </td>
            </tr>
            <tr align="left" valign="top">
                <td style="font-size: 12px; width: 290px;">Product ID:</td>
                <td style="width: 300px;"><input type="text" name="product_id" id="product_id" value="<?= $product_id ?>"/></td>
            </tr>
            <tr align="left" valign="top">
                <td style="font-size: 12px;">Hosted Page URL:</td>
                <td><?php echo("|".$hosted_page_url); ?><input type="text" name="hosted_page_url" id="hosted_page_url" style="width: 300px;" value="<?= $hosted_page_url ?>" onChange="make_return_url(this)" onKeyUp="make_return_url(this)" /></td>
            </tr>
            <tr align="left" valign="top">
                <td style="font-size: 12px;">API Access Key:</td>
                <td><input type="text" name="api_access_key" id="api_access_key" style="width: 300px;" value="<?= $api_access_key ?>" /></td>
            </tr>
            <tr align="left" valign="top">
                <td style="font-size: 12px;">Site Shared Key:</td>
                <td><input type="text" name="site_shared_key" id="site_shared_key" style="width: 300px;" value="<?= $site_shared_key ?>" /></td>
            </tr>
            <tr align="left" valign="top">
                <td style="font-size: 12px;">Signup Return URL:</td>
                <td style="font-size: 12px;"><?= $return_url ?><input type="hidden" value="<?= $return_url ?>" name="return_url" /></td>
            </tr>
            <tr align="left" valign="top">
                <td style="font-size: 12px;">Update Return URL:</td>
                <td style="font-size: 12px;"><?= $update_return_url ?><input type="hidden" value="<?= $update_return_url ?>" name="update_return_url" /></td>
            </tr>
            <tr>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <script>                
                $(document).ready(function(){
                    $('#yelp_settings').hide();
                    if($('#premium_account').is(':checked')){
                        $('#yelp_settings').show();
                    } else {
                        $('#yelp_settings').hide();
                    }
                    $('#premium_account').change(function(){
                        if($('#premium_account').is(':checked')){
                            $('#yelp_settings').show();
                        } else {
                            $('#yelp_settings').hide();
                        }
                    });

                    $('#yelp_rest_url').hide();
                    if($('#yelp_review_request').is(':checked')){
                        $('#yelp_rest_url').show();
                    } else {
                        $('#yelp_rest_url').hide();
                    }
                    $('#yelp_review_request').change(function(){
                        if($('#yelp_review_request').is(':checked')){
                            $('#yelp_rest_url').show();
                        } else {
                            $('#yelp_rest_url').hide();
                        }
                    });
                });
            </script>
                <td colspan="2" style="font-size: 12px;">
                    <input type="checkbox" name="premium_account" id="premium_account" <?php
                        if (isset($premium_account) && $premium_account == '1') {
                            echo "checked";
                        }
                        ?> />Premium Account
                <div id="yelp_settings">
                    <b style="font-size: 13px;">Yelp Settings:</b><br>
                    <input type="checkbox" name="yelp_review_request" id="yelp_review_request" <?php
                                                                    if (isset($yelp_review_request) && $yelp_review_request == '1') {
                                                                        echo "checked";
                                                                    }
                            ?> />Yelp Review Requests
                    <div id="yelp_rest_url">
                        Yelp Restaurant URL:
                        <input type="text" name="yelp_restaurant_url" id="yelp_restaurant_url" value="<?= $yelp_restaurant_url ?>"/>
                    </div>
                </div>
                </td>

            </tr>
            
            <tr align="left" valign="top">
                <td style="font-size: 13px; font-weight: bold;">Metered Usage:</td>
                <td style="font-size: 13px; font-weight: bold;">Component ID:</td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Credit Card Orders(quantity):</td>
                <td><input type="text" name="credit_card_orders_quantity" value="<?= $credit_card_orders_quantity ?>" /></td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Credit Card Orders Amount(dollars):</td>
                <td><input type="text" name="credit_card_orders_amount" value="<?= $credit_card_orders_amount ?>" /></td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Cash Orders (quantity):</td>
                <td><input type="text" name="cash_orders_quantity" value="<?= $cash_orders_quantity ?>" /></td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Cash Orders Amount (dollar):</td>
                <td><input type="text" name="cash_orders_amount" value="<?= $cash_orders_amount ?>" /> </td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Rapid Reorder Text Messages:</td>
                <td><input type="text" name="rapid_reorder_text_messages" value="<?= $rapid_reorder_text_messages ?>" /></td>
            </tr>
            <!--
            <tr align="left" valign="top">
          <td style="font-size: 12px;">Status:</td>
                  <td>
                          <select name="status" id="status">
				<option value="active">Active</option>
				<option value="deactive">Deactive</option>
			</select>
		</td>
	  </tr>
	  -->
            <tr>
                <td style="font-size: 12px; color: #068D32;">&nbsp;<?= $message ?></td>
                            <td>
                                <input type="hidden" name="chargify_product_id" id="chargify_product_id" value="<?= $settings_id ?>" />
                                <input type="submit" name="submit" value="Submit" />
                            </td>
                        </tr>
                    </table>
                    <script type="text/javascript">
                        function make_return_url(elem_hosted_page_url) {
                            var host = elem_hosted_page_url.value;
                            var return_url = "http://easywayordering.com/self_signup_wizard/index.php?sub_uri=" + host.slice(8, host.indexOf("."));
                            document.getElementById("return_url").value = return_url;
                        }
                    </script>
                </form>
        <? } ?>
                <table class="listig_table" width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
        <?
                    if (mysql_num_rows($chargify_products_query) > 0) {
                        $counter = 0;
                        while ($chargify_product = mysql_fetch_object($chargify_products_query)) {
        ?>
<?
                            if ($counter++ % 2 == 0)
                                $bgcolor = '#F8F8F8'; else
                                $bgcolor = '';
?>
                            <tr bgcolor="<?= $bgcolor ?>">
                                <td style="vertical-align: top; padding: 5px; font-size: 12px; border-bottom: 1px solid #909090;">
                                    <table>
                                        <tr>
                                            <td style="width: 170px;"><b>Product ID:</b></td>
                                            <td>
<?= $chargify_product->product_id ?>
                            <? if ($_SESSION['admin_type'] == 'admin') {
 ?>
                                <div style="float: right;">
                                    <a href="?action=edit<?= "&reseller_id=" . $reseller_id ?>&chargify_product_id=<?= $chargify_product->settings_id ?>" style="text-decoration:underline;">Edit</a>
                                    <a href="?action=delete<?= "&reseller_id=" . $reseller_id ?>&chargify_product_id=<?= $chargify_product->settings_id ?>" onClick="return confirm('Are you sure you want to delete this product')" style="text-decoration:underline;">Delete</a>
                                    <? if($chargify_product->status==1){ ?>
                                    <a href="?action=disable<?= "&reseller_id=" . $reseller_id ?>&chargify_product_id=<?= $chargify_product->settings_id ?>" onClick="return confirm('Are you sure you want to disable this product')" style="text-decoration:underline;">Disable</a>
                                    <?}else{?>
                                    <a href="?action=enable<?= "&reseller_id=" . $reseller_id ?>&chargify_product_id=<?= $chargify_product->settings_id ?>" onClick="return confirm('Are you sure you want to enable this product')" style="text-decoration:underline;">Enable</a>
                                    <?}?>
                                </div>
<? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Hosted Page URL:</b></td>
                        <td><?= $chargify_product->hosted_page_url ?></td>
                    </tr>
                    <tr>
                        <td><b>API Access Key:</b></td>
                        <td><?= $chargify_product->api_access_key ?></td>
                    </tr>
                    <tr>
                        <td><b>Site Shared Key:</b> </td>
                        <td><?= $chargify_product->site_shared_key ?></td>
                    </tr>
                    <tr>
                        <td><b>Signup Return URL:</b></td>
                        <td><?= $chargify_product->return_url ?></td>
                    </tr>
                    <tr>
                        <td><b>Update Return URL:</b></td>
                        <td><?= $chargify_product->update_return_url ?></td>
                    </tr>
                    <tr>
                        <td><b>Premium Account:</b></td>
                        <td><?= $chargify_product->premium_account == 0 ? "No" : "Yes" ?></td>
                    </tr>
                    <?php if($chargify_product->yelp_review_request != 0 ) { ?>
                    <tr>
                        <td><b style="font-size: 13px;">Yelp Review Request:</b></td>
                        <td><?= $chargify_product->yelp_review_request == 0 ? "No" : "Yes" ?></td>
                    </tr>
                    <tr>
                        <td><b style="font-size: 13px;">Yelp Restaurant URL:</b></td>
                        <td><?= $chargify_product->yelp_restaurant_url?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2">
                            <b style="font-size: 13px;">Metered Usage Component IDs:</b>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Credit Card Orders(quantity):</b></td>
                        <td><?= $chargify_product->credit_card_orders_quantity ?></td>
                    </tr>
                    <tr>
                        <td><b>Credit Card Orders Amount(dollars):</b></td>
                        <td><?= $chargify_product->credit_card_orders_amount ?></td>
                    </tr>
                    <tr>
                        <td><b>Cash Orders (quantity):</b></td>
                        <td><?= $chargify_product->cash_orders_quantity ?></td>
                    </tr>
                    <tr>
                        <td><b>Cash Orders Amount (dollar):</b></td>
                        <td><?= $chargify_product->cash_orders_amount ?></td>
                    </tr>
                    <tr>
                        <td><b>Rapid Reorder Text Messages:</b></td>
                        <td><?= $chargify_product->rapid_reorder_text_messages ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
<?
                        }
                    } else {
?>
                        <tr>
                            <td colspan="4" style="font-weight: bold; text-align: center;">No products found.</td>
                        </tr>
<?
                    }
?>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </body>
<? @mysql_close($mysql_conn); ?>