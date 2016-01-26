<?php 
$mAccountsCreated = "0";
$mTotalRevenue = "$0.00";
$mTotalOrders = "0";
$mEcommerceConversionRate = "0.00%";
$mAverageOrderValue = "$0.00";
$mAbandonmentRate = "0.00%";
$mPickupRate = "0.00%";
$mDeliveryRate = "0.00%";
$mDesktopVisits = "0";
$mMobileVisists = "0";
$mWebsiteOrders = "0";
$mMobileOrders = "0";
$mCashPercentage = "0";
$mCreditCardPercentage = "0";

$mSQLSSO = "SELECT COUNT(*) AS UserCount FROM bh_sso_user WHERE DATEDIFF(CreateDate, NOW()) < 60";
$mResSSO = dbAbstract::Execute($mSQLSSO, 1);

if ($mResSSO)
{
    if (dbAbstract::returnRowsCount($mResSSO, 1) > 0)
    {
        $mRowSSO = dbAbstract::returnObject($mResSSO, 1);
        $mAccountsCreated = $mRowSSO->UserCount;
    }
}

$mSQLOrders = "SELECT O.* FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60";
$mResOrders = dbAbstract::Execute($mSQLOrders, 1);

if ($mResOrders)
{
    $mRowCountOrders = dbAbstract::returnRowsCount($mResOrders, 1);
    if ($mRowCountOrders > 0)
    {
        $mTotalOrders = $mRowCountOrders;
    }
}

$mMobileVisitsSQL = "SELECT COUNT(M.ID) AS VisitCount FROM mobilevisits M INNER JOIN resturants R ON M.RestaurantID = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(M.CreateDate, NOW()) < 60";
$mResMobileVisits = dbAbstract::Execute($mMobileVisitsSQL, 1);

if ($mResMobileVisits)
{
    if (dbAbstract::returnRowsCount($mResMobileVisits, 1) > 0)
    {
        $mRowMobileVisits = dbAbstract::returnObject($mResMobileVisits, 1);
        $mMobileVisists = $mRowMobileVisits->VisitCount;
    }
}

$mWebsiteVisitsSQL = "SELECT COUNT(W.ID) AS VisitCount FROM websitevisits W INNER JOIN resturants R ON W.RestaurantID = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(W.CreateDate, NOW()) < 60";
$mResWebsiteVisits = dbAbstract::Execute($mWebsiteVisitsSQL, 1);

if ($mResWebsiteVisits)
{
    if (dbAbstract::returnRowsCount($mResWebsiteVisits, 1) > 0)
    {
        $mRowWebsiteVisits = dbAbstract::returnObject($mResWebsiteVisits, 1);
        $mDesktopVisits = $mRowWebsiteVisits->VisitCount;
    }
}

$mSQLTopDelis = "SELECT COUNT(O.OrderID) AS OrderCount, R.name AS DeliName FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60 GROUP BY R.name ORDER BY OrderCount DESC LIMIT 5";
$mResTopDelis = dbAbstract::Execute($mSQLTopDelis, 1);
$mTopDeliStr = "";
if ($mResTopDelis)
{
    if (dbAbstract::returnRowsCount($mResTopDelis, 1) > 0)
    {
        $mTopDeliCount = 1;
        while ($mRowTopDelis = dbAbstract::returnObject($mResTopDelis, 1))
        {
            $mTopDeliStr .= "<strong>".$mTopDeliCount.". </strong>".$mRowTopDelis->DeliName."<br /><br />";
            $mTopDeliCount = $mTopDeliCount + 1;
        }
    }
    else
    {
        $mTopDeliStr = "<strong>No Deli found.</strong>";
    }
}
else
{
    $mTopDeliStr = "<strong>No Deli found.</strong>";
}

$mSQLTotalRevenue = "SELECT SUM(O.Totel) AS TotalAmount FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60";
$mResTotalRevenue = dbAbstract::Execute($mSQLTotalRevenue, 1);

if ($mResTotalRevenue)
{
    if (dbAbstract::returnRowsCount($mResTotalRevenue, 1) > 0)
    {
        $mRowTotalRevenue = dbAbstract::returnObject($mResTotalRevenue, 1);
        $mTotalRevenue = number_format($mRowTotalRevenue->TotalAmount, 2, ".", "");
        if ($mTotalRevenue > 0)
        {
            $mAverageOrderValue = "$".number_format(($mTotalRevenue/$mTotalOrders), 2, ".", "");
        }
        $mTotalRevenue = "$".$mTotalRevenue;
    }
}

$mSQLCash = "SELECT COUNT(*) AS OrderCount FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60 AND TRIM(LOWER(O.payment_method))='cash'";
$mResCash = dbAbstract::Execute($mSQLCash, 1);

if ($mResCash)
{
    if (dbAbstract::returnRowsCount($mResCash, 1) > 0)
    {
        $mRowCash = dbAbstract::returnObject($mResCash, 1);
        $mCashCount = $mRowCash->OrderCount;
        if ($mTotalOrders > 0)
        {
            $mCashPercentage = number_format(($mCashCount/$mTotalOrders)*100, 2, ".", "");
        }
    }
}

$mSQLCredit = "SELECT COUNT(*) AS OrderCount FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60 AND TRIM(LOWER(O.payment_method))='credit card'";
$mResCredit = dbAbstract::Execute($mSQLCredit, 1);

if ($mResCredit)
{
    if (dbAbstract::returnRowsCount($mResCredit, 1) > 0)
    {
        $mRowCredit = dbAbstract::returnObject($mResCredit, 1);
        $mCreditCardCount = $mRowCredit->OrderCount;
        if ($mTotalOrders > 0)
        {
            $mCreditCardPercentage = number_format(($mCreditCardCount/$mTotalOrders)*100, 2, ".", "");
        }
    }
}

$mSQLPickup = "SELECT COUNT(*) AS OrderCount FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60 AND TRIM(LOWER(O.order_receiving_method))='pickup'";
$mResPickup = dbAbstract::Execute($mSQLPickup, 1);

if ($mResPickup)
{
    if (dbAbstract::returnRowsCount($mResPickup, 1) > 0)
    {
        $mRowPickup = dbAbstract::returnObject($mResPickup, 1);
        $mPickupCount = $mRowPickup->OrderCount;
        if ($mTotalOrders > 0)
        {
            $mPickupRate = number_format(($mPickupCount/$mTotalOrders)*100, 2, ".", "")."%";
        }
    }
}

$mSQLDelivery = "SELECT COUNT(*) AS OrderCount FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60 AND TRIM(LOWER(O.order_receiving_method))='delivery'";
$mResDelivery = dbAbstract::Execute($mSQLDelivery, 1);

if ($mResDelivery)
{
    if (dbAbstract::returnRowsCount($mResDelivery, 1) > 0)
    {
        $mRowDelivery = dbAbstract::returnObject($mResDelivery, 1);
        $mDeliveryCount = $mRowDelivery->OrderCount;
        if ($mTotalOrders > 0)
        {
            $mDeliveryRate = number_format(($mDeliveryCount/$mTotalOrders)*100, 2, ".", "")."%";
        }
    }
}

$mSQLMobile = "SELECT COUNT(*) AS OrderCount FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60 AND TRIM(LOWER(O.platform_used))='2'";
$mResMobile = dbAbstract::Execute($mSQLMobile, 1);

if ($mResMobile)
{
    if (dbAbstract::returnRowsCount($mResMobile, 1) > 0)
    {
        $mRowMobile = dbAbstract::returnObject($mResMobile, 1);
        $mMobileOrders = $mRowMobile->OrderCount;
    }
}

$mSQLWebsite = "SELECT COUNT(*) AS OrderCount FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE R.status = 1 AND R.bh_restaurant = 1 AND DATEDIFF(O.CreateDate, NOW()) < 60 AND TRIM(LOWER(O.platform_used))='1'";
$mResWebsite = dbAbstract::Execute($mSQLWebsite, 1);

if ($mResWebsite)
{
    if (dbAbstract::returnRowsCount($mResWebsite, 1) > 0)
    {
        $mRowWebsite = dbAbstract::returnObject($mResWebsite, 1);
        $mWebsiteOrders = $mRowWebsite->OrderCount;
    }
}

$mEcommerceNotOrdered = 0;
$mEcommerceOrdered = 0;

$mSQLEcommerceNotOrdered = "SELECT COUNT(*) AS RecordCount FROM bh_ecommerce WHERE DATEDIFF(CreateDate, NOW()) < 60 AND OrderPlaced = 0";
$mResEcommerceNotOrdered = dbAbstract::Execute($mSQLEcommerceNotOrdered, 1);

if ($mResEcommerceNotOrdered)
{
    if (dbAbstract::returnRowsCount($mResEcommerceNotOrdered, 1) > 0)
    {
        $mRowEcommerceNotOrdered = dbAbstract::returnObject($mResEcommerceNotOrdered, 1);
        $mEcommerceNotOrdered = $mRowEcommerceNotOrdered->RecordCount;
    }
}

$mSQLEcommerceOrdered = "SELECT COUNT(*) AS RecordCount FROM bh_ecommerce WHERE DATEDIFF(CreateDate, NOW()) < 60 AND OrderPlaced = 1";
$mResEcommerceOrdered = dbAbstract::Execute($mSQLEcommerceOrdered, 1);

if ($mResEcommerceOrdered)
{
    if (dbAbstract::returnRowsCount($mResEcommerceOrdered, 1) > 0)
    {
        $mRowEcommerceOrdered = dbAbstract::returnObject($mResEcommerceOrdered, 1);
        $mEcommerceOrdered = $mRowEcommerceOrdered->RecordCount;
    }
}

if ($mEcommerceOrdered > 0)
{
    $mEcommerceConversionRate = number_format(($mEcommerceOrdered/($mEcommerceNotOrdered + $mEcommerceOrdered))*100, 2, ".", "")."%";
} 

$mAbandonedCount = 0;
$mSQLAbandonedCarts = "SELECT COUNT(*) AS RecordCount FROM abandoned_carts A INNER JOIN resturants R ON R.id = A.resturant_id WHERE DATEDIFF(A.dated, NOW()) < 60 AND R.bh_restaurant = 1 AND R.status = 1";
$mResAbandonedCarts = dbAbstract::Execute($mSQLAbandonedCarts, 1);

if ($mResAbandonedCarts)
{
    if (dbAbstract::returnRowsCount($mResAbandonedCarts, 1) > 0)
    {
        $mRowAbandonedCarts = dbAbstract::returnObject($mResAbandonedCarts, 1);
        $mAbandonedCount = $mRowAbandonedCarts->RecordCount;
    }
}

if ($mAbandonedCount > 0)
{
    $mAbandonmentRate = number_format(($mAbandonedCount/($mTotalOrders + $mAbandonedCount))*100, 2, ".", "")."%";
}

?>
<style type="text/css">
    .bhHeading
    {
        font-weight: bold;
        font-size: 13px;
    }
    
    .bhText
    {
        font-weight: bold;
        font-size: 22px;
    }
    
    .bhText1
    {
        font-size: 18px;
    }
</style>
<div id="contents">
    <div id="main_heading" class="clearfix">
        <span style="font-size:18px; display: block; float: left;">Boar's Head Analytics</span>
        <span style="display: block; float: right; padding-right: 20px;">
            <?php
                echo date("F j, Y", strtotime("-60 day")) . " - " . date("F j, Y", strtotime("0 day"));
            ?>
        </span>
    </div>
    <div id="contents_area" style="float:left; margin-left:15px; width:95%;">
        <div style="background-color: #CCCCCC; width: 100%; padding: 4px; text-align: center;">
            <table style="width: 100%; background-color: #FFFFFF; border: 1px solid #000000; text-align: center;" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr>
                    <td style="width: 3%;">
                        
                    </td>
                    <td style="width: 22%;">
                        <span class="bhHeading">ACCOUNTS CREATED</span>
                    </td>
                    <td style="width: 3%;">
                        
                    </td>
                    <td style="width: 20%;">
                        <span class="bhHeading">TOTAL REVENUE</span>
                    </td>
                    <td style="width: 3%;">
                        
                    </td>
                    <td style="width: 20%;">
                        <span class="bhHeading">TOTAL ORDERS</span>
                    </td>
                    <td style="width: 3%;">
                        
                    </td>
                    <td style="width: 23%;">
                        <span class="bhHeading">ECOMMERCE CONVERSION RATE</span>
                    </td>
                    <td style="width: 3%;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mAccountsCreated?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mTotalRevenue?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mTotalOrders?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mEcommerceConversionRate?></span>
                    </td>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr style="width: 100%; height: 1px; background-color: #CCCCCC;">
                    <td colspan="9" style="width: 100%; height: 1px; background-color: #CCCCCC;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhHeading">AVERAGE ORDER VALUE</span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhHeading">ABANDONMENT RATE</span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhHeading">PICKUP RATE</span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhHeading">DELIVERY RATE</span>
                    </td>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mAverageOrderValue?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mAbandonmentRate?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mPickupRate?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mDeliveryRate?></span>
                    </td>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr style="width: 100%; height: 1px; background-color: #CCCCCC;">
                    <td colspan="9" style="width: 100%; height: 1px; background-color: #CCCCCC;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhHeading">DESKTOP VISITS</span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhHeading">MOBILE VISITS</span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhHeading">WEBSITE ORDERS</span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhHeading">MOBILE ORDERS</span>
                    </td>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mDesktopVisits?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mMobileVisists?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mWebsiteOrders?></span>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <span class="bhText"><?=$mMobileOrders?></span>
                    </td>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="width: 100%; height: 20px; background-color: #FFFFFF;">
                        
                    </td>
                </tr>
                <tr style="width: 100%; height: 1px; background-color: #CCCCCC;">
                    <td colspan="9" style="width: 100%; height: 1px; background-color: #CCCCCC;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="9">
                        <table style="width: 100%; background-color: #FFFFFF; text-align: center;" cellpadding="0" cellspacing="0">
                            <tr style="background-color: #FFFFFF;">
                                <td style="width: 50%; vertical-align: top !important; background-color: #FFFFFF;">
                                    <table style="width: 100%; background-color: #FFFFFF; text-align: center;" cellpadding="0" cellspacing="0">
                                        <tr style="height: 30px; background-color: #FFFFFF;">
                                            <td colspan="2">
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <span class="bhText">TOP 5 DELIS</span>
                                            </td>
                                        </tr>
                                        <tr style="height: 20px; background-color: #FFFFFF;">
                                            <td colspan="2">
                                                
                                            </td>
                                        </tr>
                                        <tr style="height: 30px; background-color: #FFFFFF;">
                                            <td style="width: 30%;">
                                                
                                            </td>
                                            <td style="text-align: left !important;">
                                                <span class="bhText1"><?=$mTopDeliStr;?></span>
                                            </td>
                                        </tr>
                                        <tr style="height: 20px; background-color: #FFFFFF;">
                                            <td colspan="2">
                                                
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 1px; border: 1px solid #CCCCCC;">
                                
                                </td>
                                <td style="width: 50%;">
                                    <table style="width: 100%; background-color: #FFFFFF; text-align: center;" cellpadding="0" cellspacing="0">
                                        <tr style="height: 30px; background-color: #FFFFFF;">
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                if (($mCashPercentage > 0) && ($mCreditCardPercentage > 0))
                                                {
                                                ?>
                                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                                <script type="text/javascript">
                                                    google.charts.load('current', {'packages':['corechart']});
                                                    google.charts.setOnLoadCallback(drawChart);
                                                    function drawChart() 
                                                    {
                                                        var data = google.visualization.arrayToDataTable([
                                                          ['Payment Type', 'Percentage'],
                                                          ['Cash', <?=$mCashPercentage?>],
                                                          ['Credit', <?=$mCreditCardPercentage?>]
                                                        ]);

                                                        var options = {
                                                            title: '',
                                                            width: '500',
                                                            height: '270',
                                                            colors: ['#3A94BB', '#00537B'],
                                                            legend: 'left',
                                                        };

                                                        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                                                        chart.draw(data, options);
                                                    }
                                                </script>
                                                <div id="piechart" style="width: 500px; height: 270px;"></div>
                                                <div id="legend"></div>
                                                <?php
                                                }
                                                else
                                                {
                                                ?>
                                                    NOT ENOUGH DATA TO DRAW GRAPH
                                                <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr style="height: 30px; background-color: #FFFFFF;">
                                            <td>
                                                
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="clear: both;"></div>
</div>