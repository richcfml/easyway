<?php
session_start();
include "../config.php";
if(isset($_SESSION['admin_session_user_name'])){
    $sso_token = $_REQUEST['sso_token'];
    $sso_ticket = $_REQUEST['sso_ticket'];
    $restaurant_query = dbAbstract::Execute("SELECT * from vendasta_validation where sso_token = '".$sso_token."'
                                                                        and sso_ticket = '".$sso_ticket."'
                                    ");
    $restaurant_info = dbAbstract::returnArray($restaurant_query);
    if($restaurant_info['restaurant_id'] == $_SESSION['ResturantId'] )
    {
        dbAbstract::Update("update vendasta_validation set status = 1 where sso_token = '".$sso_token."'
                                                        and sso_ticket = '".$sso_ticket."'
            ");
        return 200;
    } else {
        return 500;
    }
} else {
    return 500;
}

?>
<?php mysqli_close($mysqli);?>