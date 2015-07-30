<div style="float:left; width:20%;">

    <div id="all_rest" class="itemclosed" onClick="togglehandler(this.id, '');"><a href="?mod=resturant">All Restaurants</a></div>
    <?php
    

    //GET ALL RESELLERS 
    $reseller_sql = "select id, firstname, lastname,company_name FROM users where type ='reseller' ORDER BY company_name ASC ";

    $class = "";
    $reseller_qry = dbAbstract::Execute($reseller_sql,1);
    $reseller_id = 0;
    while ($reseller_rs = dbAbstract::returnAssoc($reseller_qry,1)) {
        $reseller_name = $reseller_rs['company_name'] . " [" . $reseller_rs['firstname'] . " " . $reseller_rs['lastname'] . " ]";
        if ($reseller_id == $reseller_rs['id'])
            $display = "block";
        else
            $display = "none";
        ?>
        <div  style="width:100%;">


            <div id="<?= "reseller" . $reseller_rs['id'] ?>" class="itemclosed" onClick="togglehandler(this.id, '_<?= $reseller_rs['id'] ?>');"><a href="javascript:void(0);"><?= $reseller_name ?></a></div>

            <div id="_<?= $reseller_rs['id'] ?>" class="searchlist" style="display:<?= $display ?>;">
                <ul>
                    <li class="<?= $class ?>"> <a href="?mod=resturant&reseller=<?= $reseller_rs['id'] ?>">All Restaurants </a></li>
                    <?
                    //GET ALL CLIENTS
                    $reseller_client_sql1 = "SELECT client_id,restaurant_count,firstname,lastname FROM reseller_client WHERE reseller_id  = '" . $reseller_rs['id'] . "' ";
                    $reseller_client_qry1 = dbAbstract::Execute($reseller_client_sql1,1);
                    $client_ids1 = "";

                    $j = 0;
                    while ($reseller_client_rs1 = dbAbstract::returnArray($reseller_client_qry1,1)) {

                        if ($j == 0) {
                            $client_ids1 = $reseller_client_rs1['client_id'];
                            $client_restaurant_count = $reseller_client_rs1['restaurant_count'];
                            $client_name = $reseller_client_rs1['firstname'].' '.$reseller_client_rs1['lastname'];
                        } else {
                            $client_ids1 .= " ," . $reseller_client_rs1['client_id'];
                            $client_restaurant_count = $reseller_client_rs1['restaurant_count'];
                            $client_name = $reseller_client_rs1['firstname'].' '.$reseller_client_rs1['lastname'];
                        }
                        $j++;
                       if ($Clentid == $reseller_client_rs1['client_id'])
                            $class = "";
                        else
                            $class = "";
                         ?> 
                        <li class="<?= $class ?>"> <a href="?mod=resturant&resellerId=<?= $reseller_rs['id'] ?>&client_id=<?= $reseller_client_rs1['client_id'] ?>"><?= $client_name?> <span style="color:#999"> &nbsp;(<?= $client_restaurant_count ?>) </span> </a> </a></li>
                            <?php
                    }
?>
                </ul>
            </div>
            <div id="dotted_line"></div>
        </div>
<? } ?>

</div>