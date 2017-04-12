<?php
class Users
{
    public $id;
    public $cust_desire_name;
    public $cust_your_name;
    public $LastName;
    public $cust_business_name;
    public $cust_odr_address;
    public $cust_ord_city;
    public $cust_ord_state;
    public $cust_ord_zip;
    public $cust_phone1;
    public $cust_phone2;
    public $cust_email;
    public $delivery_address1;
    public $deivery1_zip;
    
    public $delivery_city1;
    public $delivery_state1;
    public $cust_room;
    public $resturant_id;
    public $gateway_token;
    public $stree1;
    public $stree2;
    public $delivery_street1;
    public $delivery_street2;
    public $password;
    public $epassword;
    public $salt;
    
    public $valuetec_card_number;
    public $approval_code;
    public $valuetec_points;
    public $valuetec_reward;
    public $valutec_registeration_date;
    
    //Gulfam - 19 December 2014 Follwing 6 billing variables are just to show billing information on 
    //Thank you page. Other than Thanyou page there is no use of these variables
    public $billing_fname;
    public $billing_lname;
    public $billing_address;
    public $billing_city;
    public $billing_state;
    public $billing_zip;
    
    public $delivery_address_choice;
    public $arrTokens;
    public $arrFavorites;
    
    function __construct()
    {
        $this->arrTokens = array();
    }
    
	/*
	*	Save user session
	*/
    public function saveToSession()
    {
        $this->destroyUserSession();
        $_SESSION['loggedinuser'] = serialize($this);
    }
    
	/*
	*	Load from session if session is already set
	*/
    public function loadFromSession()
    {
        return (isset($_SESSION['loggedinuser'])) ? unserializeData($_SESSION['loggedinuser']) : NULL;
    }
    
	/*
	*	Unset logged in user session
	*/
    public function destroyUserSession()
    {
        if (isset($_SESSION['loggedinuser'])) {
            unset($_SESSION['loggedinuser']);
        }
    }
    
    /*
	*	Save user valutec card
	*/
    public function saveUserValutecCard($InputCardNumber, $points, $balance)
    {
        dbAbstract::Update("update customer_registration set valuetec_card_number=" . $InputCardNumber . ",valutec_registeration_date='" . date("Y-m-d") . "' where id=" . $this->id);
        $this->valuetec_card_number = $InputCardNumber;
        $this->valuetec_points      = $points;
        $this->valuetec_reward      = $balance;
        $this->saveToSession();
    }
    
	/*
	*	Get user details by phone number and restaurant ID
	*/
    public function getUserDetailByPhone($phone, $restaurantId)
    {
        $mSQL = "SELECT * FROM customer_registration WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(cust_phone1,'(',''),')',''),'-',''),'',''),' ','')='" . trim($phone) . "' and resturant_id=" . $this->resturant_id . " and password!=''";
        $user = dbAbstract::ExecuteObject($mSQL, 0, "users");
        return $user;
    }
    
	/*
	*	Save user phone number
	*/
    public function saveUserPhone()
    {
        $qry = "update customer_registration set cust_phone1='" . prepareStringForMySQL($this->cust_phone1) . "' where id=" . $this->id . "";
        dbAbstract::Update($qry);
        $this->saveToSession();
    }
    
	/*
	*	Remint user password
	*/
    public function remindUserPassword($email, $objRestaurant, $objMail)
    {
        $mSQL = "SELECT id,cust_email, password,cust_your_name,LastName FROM customer_registration WHERE cust_email='" . prepareStringForMySQL($email) . "' and resturant_id=" . $objRestaurant->id . "   AND LENGTH(password)>0";
        
        $userQuery = dbAbstract::Execute($mSQL);
        $numrow    = dbAbstract::returnRowsCount($userQuery);
        
        if ($numrow == 0) {
            return false;
        } else {
            $user = dbAbstract::returnObject($userQuery);
            return $this->sendPasswordReminderEmail($user, $objRestaurant, $objMail);
        }
    }
    
	/*
	*	Send password reminder email to user email address
	*/
    private function sendPasswordReminderEmail($user, $objRestaurant, $objMail)
    {
        global $SiteUrl;
        $mObjFun      = new clsFunctions();
        $mEncryptedID = $mObjFun->encrypt($user->id, "53cr3t9455w0rd");
        $mLink        = $SiteUrl . $objRestaurant->url_name . "/?item=resetpassword&id=" . $mEncryptedID;
        $subject      = "easywayordering.com Account Password Reminder";
        $body         = "Greetings, " . $user->cust_your_name . " " . $user->LastName . "! <br/><br/>" . $body .= "Thank you for visiting www.easywayordering.com/" . $objRestaurant->url . "/. 
					 We hope that you will find our easywayordering system helpful for your work and home food delivery needs.<br/><br/>
					 Click following link to reset your password:<br/><a href='" . $mLink . "' target='_blank'>" . $mLink . "</a><br/><br/>
					 We thank you for your business and look forward to serving you!<br/><br/>
					 Kind regards,<br/><br/>
					 
					 www.easywayordering.com/" . $objRestaurant->url . "/<br/>
					 Phone: " . $objRestaurant->phone . "<br/>
					 Fax:  " . $objRestaurant->fax;
        
        $objMail->from = "info@easywayordering.com";
        $objMail->sendTo($body, $subject, $email);
        return true;
    }
    
	/*
	*	Login user
	*/
    public function loginUser($email, $password, $restaurant_id)
    {
        $mRow = dbAbstract::ExecuteObject("SELECT salt FROM customer_registration WHERE TRIM(LOWER(cust_email))='" . prepareStringForMySQL($email) . "' AND resturant_id=" . $restaurant_id . " AND TRIM(epassword)<>''");
        if ($mRow) {
            $mSalt    = $mRow->salt;
            $email    = prepareStringForMySQL($email);
            $password = hash('sha256', prepareStringForMySQL($password) . $mSalt);
            
            $mSQL     = "SELECT * FROM customer_registration WHERE cust_email='$email' AND epassword ='$password' AND resturant_id= '" . $restaurant_id . "'";
            $user_qry = dbAbstract::Execute($mSQL);
            return $this->login($user_qry, 0);
        } else {
            return NULL;
        }
    }
    
	/*
	*	Login sso user
	*/
    public function ssoUserLogin($email, $restaurant_id, $ssoUserId)
    {
        Log::write("--->Users.ssoUserLogin<----");
        $email    = prepareStringForMySQL($email);
        $user_qry = dbAbstract::Execute("select * from customer_registration where cust_email='$email' and resturant_id= '" . $restaurant_id . "'", 1);
        return $this->login($user_qry, 1, 0, $ssoUserId);
    }
    
	/*
	*	login user
	*/
    private function login($user_qry, $pC_Panel, $loginById = 0, $ssoUserId=0)
    {
        Log::write("--->Users.login<----");
        if (dbAbstract::returnRowsCount($user_qry) > 1 || dbAbstract::returnRowsCount($user_qry) == 0) {
            return NULL;
        }
        
        $user = dbAbstract::returnObject($user_qry, $pC_Panel, "users");
        if ($loginById == 0) {
            Log::write("--->Users.loginById equal to 0<----");
            $user->delivery_address_choice = 1;
            $user->getUserCCTokens($ssoUserId);
            $user->loadUserFavorites();
        }
        return $user;
    }
    
	/*
	*	login user by user id
	*/
    public function loginByUserId($id)
    {
        $user_qry = dbAbstract::Execute("SELECT * FROM customer_registration WHERE id='$id'");
        return $this->login($user_qry, 0, 1);
    }
    
	/*
	*	Load user favorites
	*/
    public function loadUserFavorites($whereStmt = '', $orderby = '', $limit = '')
    {
        $where = "customer_id=" . $this->id;
        $where .= ($whereStmt != '') ? ' AND ' . $whereStmt : '';
        $orderby = ($orderby != '') ? 'ORDER BY ' . $orderby : '';
        $limit   = ($limit != '') ? 'LIMIT ' . $limit : '';
        
        $this->arrFavorites = array();
        $favorites          = dbAbstract::Execute("SELECT * FROM customer_favorites WHERE $where $orderby $limit");
        while ($favorite = dbAbstract::returnObject($favorites)) {
            $favorite->food       = unserializeData($favorite->food);
            $this->arrFavorites[] = $favorite;
        }
    }
    
	/*
	*	Get favorites titles
	*/
    public function getFavoritesTitles()
    {
        $this->arrFavorites = array();
        $mSQL               = "SELECT title FROM customer_favorites WHERE customer_id=" . $this->id . " AND rapidreorder=1";
        $favorites          = dbAbstract::Execute($mSQL);
        while ($favorite = dbAbstract::returnObject($favorites)) {
            $this->arrFavorites[] = $favorite->title;
        }
    }
    
	/*
	*	Get favorites by title
	*/
    public function getFavoritesByTitle($title)
    {
        $this->loadUserFavorites("title='" . addslashes($title) . "' AND rapidreorder=1", 'id DESC', '0,1');
        return $this->arrFavorites;
    }
    
	/*
	*	get favorite by favorite id
	*/
    public function getFavoritesById($id)
    {
        $this->loadUserFavorites("id='$id'", 'id DESC', '0,1');
        return $this->arrFavorites;
    }
    
	/*
	*	remove favorite item
	*/
    public function removeUserFavoriteOrder($index)
    {
        $favorit_id = $this->arrFavorites[$index]->id;
        $mSQL       = "DELETE FROM customer_favorites WHERE id=" . $favorit_id;
        Log::write("Delete customer favorites - users.php", "QUERY -- " . $mSQL, 'order', 1, 'user');
        dbAbstract::Delete($mSQL);
        unset($this->arrFavorites[$index]);
        $this->arrFavorites = array_values($this->arrFavorites);
        $this->loadUserFavorites();
        $this->saveToSession();
    }
    
	/*
	*	Change user reordering status
	*/
    public function changeRepidReorderingStatus($index, $status)
    {
        $favorit_id = $this->arrFavorites[$index]->id;
        Log::write("Delete customer favorites - users.php", "QUERY -- update customer_favorites  set rapidreorder=$status where  id=" . $favorit_id . "", 'order', 1, 'user');
        $mSQL = "UPDATE customer_favorites SET rapidreorder=$status WHERE id=" . $favorit_id;
        dbAbstract::Update($mSQL);
        $this->arrFavorites[$index]->rapidreorder = $status;
        $this->saveToSession();
    }
    
	/*
	*	Get user cc tokens
	*/
    public function getUserCCTokens($ssoUserId=0)
    {
		global $loggedinuser;
        $this->arrTokens = array();
        $mSQL            = "SELECT * FROM general_detail WHERE id_2=" . $this->id . " ORDER BY data_3 DESC";
		if($ssoUserId > 0 || $loggedinuser->ssoUserId > 0){
			$ssoUserId = ($ssoUserId==0 && $loggedinuser->ssoUserId > 0)? $loggedinuser->ssoUserId:$ssoUserId;
			$mSQL            = "SELECT * FROM general_detail WHERE (id_2=" . $this->id . ") OR (sso_user_id='$ssoUserId') ORDER BY data_3 DESC";
		}
		$tokens          = dbAbstract::Execute($mSQL);
        while ($token = dbAbstract::returnObject($tokens)) {
            $this->arrTokens[] = $token;
        }
    }
    
	/*
	*	Get user default cc token
	*/
    public function getUserDefaultCCToken()
    {
        $this->arrTokens = array();
        $mSQL            = "SELECT * FROM general_detail WHERE id_2=" . $this->id . " ORDER BY data_3 DESC LIMIT 0 ,1";
        return dbAbstract::ExecuteObject($mSQL);
    }
    
	/*
	*	set user defaut card
	*/
    public function setUserDefaultCard($token)
    {
        $mSQL = "UPDATE general_detail SET data_3=0 WHERE id_2=" . $this->id;
        dbAbstract::Update($mSQL);
        $mSQL = "UPDATE general_detail SET data_3=1 WHERE id_2=" . $this->id . " AND data_2='" . $token . "'";
        dbAbstract::Update($mSQL);
        $this->getUserCCTokens();
        $this->saveToSession();
    }
    
	/*
	*	update customer registeration
	*/
    public function updateCustomerRegistration()
    {
		global $loggedinuser;
        $this->cust_odr_address  = $this->street1 . '~' . $this->street2;
        $this->delivery_address1 = $this->delivery_street1 . '~' . $this->delivery_street2;
        
        $qry = "update customer_registration set
                cust_email='" . prepareStringForMySQL($this->cust_email) . "'
               
	       , cust_your_name='" . prepareStringForMySQL($this->cust_your_name) . "'
               , LastName='" . prepareStringForMySQL($this->LastName) . "'
               , cust_odr_address='" . prepareStringForMySQL($this->street1 . '~' . $this->street2) . "'
               , cust_ord_city='" . prepareStringForMySQL($this->cust_ord_city) . "'
               , cust_ord_state='" . prepareStringForMySQL($this->cust_ord_state) . "'
               , cust_ord_zip='" . prepareStringForMySQL($this->cust_ord_zip) . "'
               , cust_phone1='" . prepareStringForMySQL($this->cust_phone1) . "'
               , delivery_address1='" . prepareStringForMySQL($this->delivery_street1 . '~' . $this->delivery_street2) . "'
               , delivery_city1='" . prepareStringForMySQL($this->delivery_city1) . "'
               , delivery_state1='" . prepareStringForMySQL($this->delivery_state1) . "'
               , deivery1_zip='" . prepareStringForMySQL($this->deivery1_zip) . "'
               , epassword='" . prepareStringForMySQL($this->epassword) . "'
               , salt='" . prepareStringForMySQL($this->salt) . "'
                 where id=" . $this->id . "";
        
        dbAbstract::Update($qry);
        $this->saveToSession();
        return true;
    }
	
	public function updateSSOUserProfile(){
		global $loggedinuser;
		$qry = "update bh_sso_user set
				firstName='" . prepareStringForMySQL($_POST[x_first_name]) . "', 
				lastName='" . prepareStringForMySQL($_POST[x_last_name]) . "', 
				address1='" . prepareStringForMySQL($_POST[x_address]) . "',
				city='" . prepareStringForMySQL($_POST[x_city]) . "', 
				state='" . prepareStringForMySQL($_POST[x_state]) . "', 
				zip='" . prepareStringForMySQL($_POST[x_zip]) . "', 
				phone='" . prepareStringForMySQL($_POST[x_phone]) . "'
				where id=" . $loggedinuser->ssoUserId . "";
		$resp = dbAbstract::Update($qry);
		
	}
    
	/*
	*	register new cutomer 
	*/
    public function customerRegistration(&$objRestaurant, &$objMail, $ssoUserId=0)
    {
        $this->resturant_id = $objRestaurant->id;
        
        $mSQL     = "SELECT cust_email FROM customer_registration WHERE cust_email='" . prepareStringForMySQL($this->cust_email) . "' AND resturant_id='" . $objRestaurant->id . "' AND epassword !='NULL' AND as_guest!=1";
        $user_qry = dbAbstract::Execute($mSQL);
        
        if (dbAbstract::returnRowsCount($user_qry) > 0) {
            return 0;
        }
        
        $this->createNewUser();
        if (is_numeric($this->id)) {
			$this->delivery_address_choice = 1;
			if($ssoUserId > 0){
				$this->getUserCCTokens($ssoUserId);
			}
            $this->saveToSession();
            $this->sendUserRegisterationEmail($objRestaurant, $objMail);
            return true;
        } else {
            return false;
        }
    }
    
	/*
	*	create new user
	*/
    public function createNewUser($is_guest=0)
    {
        $mFBID = '';
        if (isset($this->facebook_id)) {
            $mFBID = " , facebook_id='" . dbAbstract::returnRealEscapedString($this->facebook_id) . "' ";
        }
        $this->cust_odr_address  = $this->street1 . '~' . $this->street2;
        $this->delivery_address1 = $this->delivery_street1 . '~' . $this->delivery_street2;
        
        $as_guest = 0;
        if($is_guest == 1)
            $as_guest=1;
        $qry = "insert into customer_registration set
                 cust_email='" . prepareStringForMySQL($this->cust_email) . "'
                
		, cust_your_name='" . prepareStringForMySQL($this->cust_your_name) . "'
                , LastName='" . prepareStringForMySQL($this->LastName) . "'
                , cust_odr_address='" . prepareStringForMySQL($this->street1 . '~' . $this->street2) . "'
                , cust_ord_city='" . prepareStringForMySQL($this->cust_ord_city) . "'
                , cust_ord_state='" . prepareStringForMySQL($this->cust_ord_state) . "'
                , cust_ord_zip='" . prepareStringForMySQL($this->cust_ord_zip) . "'
                , cust_phone1='" . prepareStringForMySQL($this->cust_phone1) . "'
                , delivery_address1='" . prepareStringForMySQL($this->delivery_street1 . '~' . $this->delivery_street2) . "'
                , delivery_city1='" . prepareStringForMySQL($this->delivery_city1) . "'
                , delivery_state1='" . prepareStringForMySQL($this->delivery_state1) . "'
                , deivery1_zip='" . prepareStringForMySQL($this->deivery1_zip) . "'" . $mFBID . "
                ,resturant_id=" . $this->resturant_id . "
                ,as_guest=" . $as_guest . "
                ,epassword='" . $this->epassword . "'
                ,salt='" . $this->salt . "'";
        
        $this->arrTokens = array();
        $this->id        = dbAbstract::Insert($qry, 0, 2);
        $this->saveToSession();
    }
    
    /*
	*	Log out user
	*/
    public function logoutUser()
    {
        $this->destroyUserSession();
    }
    
	/*
	*	set user delivery address
	*/
    public function setUserDeliveryAddress($option)
    {
        $this->delivery_address_choice = $option;
        $this->saveToSession();
    }
    
	/*
	*	get user delivery address
	*/
    public function getUserDeliveryAddress($formated = 1)
    {
        $char = ($formated == 1) ? '<br/>' : ', ';
        
        if ($this->delivery_address_choice == 1) {
            $this->delivery_address = trim(trim(trim($this->street1) . " " . trim($this->street2)) . $char . $this->cust_ord_city . $char . $this->cust_ord_state . (($formated == 1) ? $char . $this->cust_ord_zip : ""));
        } else if ($this->delivery_address_choice > 1) {
            $this->delivery_address = trim(trim(trim($this->delivery_street1) . " " . trim($this->delivery_street2)) . $char . $this->delivery_city1 . $char . $this->delivery_state1 . (($formated == 1) ? $char . $this->deivery1_zip : ""));
        } else if (!is_numeric($this->id)) {
            $this->delivery_address = trim(trim(trim($this->street1) . " " . trim($this->street2)) . $char . $this->cust_ord_city . $char . $this->cust_ord_state . (($formated == 1) ? $char . $this->cust_ord_zip : ""));
        } else {
            $this->delivery_address = trim(trim(trim($this->street1) . " " . trim($this->street2)) . $char . $this->cust_ord_city . $char . $this->cust_ord_state . (($formated == 1) ? $char . $this->cust_ord_zip : ""));
        }
        return $this->delivery_address;
    }
    
	/*
	*	get user delivery zip code
	*/
    public function getUserDeliveryZipCode()
    {
        if ($this->delivery_address_choice == 1) {
            return $this->cust_ord_zip;
        } else if (!is_numeric($this->id)) {
            
            return $this->cust_ord_zip;
        } else {
            return $this->deivery1_zip;
        }
        
    }
    
	/*
	*	Send user registeration email
	*/
    private function sendUserRegisterationEmail(&$objRestaurant, &$objMail)
    {
        /*Below Code (From Start Comment 27 Aug 2015 Till End Comment 27 Aug 2015 
        is working fine but as there is no need for registration emails so we are 
        commenting this. Gulfam - QualityClix 27 August 2015
        */
        
        /*Start Comment 27 Aug 2015 
        $mail_body="";
        $mail_body=$mail_body."Greetings, ".trim($this->cust_your_name ." ".$this->LastName)."!"."<br><br>";
        $mail_body=$mail_body."Thank you for visiting <a href='http://www.easywayordering.com/". $objRestaurant->url ."/'>www.easywayordering.com/". $objRestaurant->url ."/</a>. We hope that you will find our easywayordering system helpful for your work and home food delivery needs."."<br><br>";
        $mail_body=$mail_body."For your convenience, please store the following information:"."<br><br>";
        $mail_body=$mail_body."Your login: ".$this->cust_email."<br>";
        $mail_body=$mail_body."Your password: [The password you set while creating account]<br><br>";
        $mail_body=$mail_body."Your account makes it very easy for you to place orders in the future; simply enter in your Username and Password on the main order page, and you're on your way to receiving your favorite restaurant food, delivered right to your door. With our easywayordering system you will be able to plan ahead and relax."."<br><br>";
        $mail_body=$mail_body."We thank you for your business and look forward to serving you!"."<br><br>";
        $mail_body=$mail_body."Kind regards,"."<br><br>";
        $mail_body=$mail_body."<a href='http://www.easywayordering.com/". $objRestaurant->url ."/'>www.easywayordering.com/". $objRestaurant->url ."/</a>"."<br>";
        $mail_body=$mail_body."Phone: ". $objRestaurant->phone ."<br>";
        $mail_body=$mail_body."Fax: ". $objRestaurant->fax ."<br>";
        
        $objMail->from = "info@easywayordering.com";
        $objMail->sendTo($mail_body,"Thank you for register at ". $objRestaurant->name ."",$this->cust_email);
        * End Comment 27 Aug 2015 */
    }
    
	/*
	*	Add menu to customer favorites
	*/
    public function addMenuToCustomerFavorites($title, $food, $repidreodering, $order_receiving_method, $pTip)
    {
        $mSQL = "insert into customer_favorites(restaurant_id,customer_id,title,food,rapidreorder,order_receiving_method,driver_tip) values(" . $this->resturant_id . "," . $this->id . ",'" . prepareStringForMySQL($title) . "','" . prepareStringForMySQL($food) . "',$repidreodering,$order_receiving_method,$pTip)";
        dbAbstract::Insert($mSQL);
        $this->loadUserFavorites();
        $this->saveToSession();
        
    }
    
    /*
	*	update customer favorite menu
	*/
    public function updateCustomerFavoriteMenu($pFavoriteID, $pFood, $pTip, $pDeliveryMethod)
    {
        if ($pTip == -1) {
            if ($pDeliveryMethod == -1) {
                $mSQL = "UPDATE customer_favorites SET food='" . addslashes($pFood) . "' WHERE id=" . $pFavoriteID;
            } else {
                $mSQL = "UPDATE customer_favorites SET food='" . addslashes($pFood) . "', order_receiving_method=" . $pDeliveryMethod . " WHERE id=" . $pFavoriteID;
            }
        } else if ($pTip >= 0) {
            if ($pDeliveryMethod == -1) {
                $mSQL = "UPDATE customer_favorites SET food='" . addslashes($pFood) . "', driver_tip=" . $pTip . " WHERE id=" . $pFavoriteID;
            } else {
                $mSQL = "UPDATE customer_favorites SET food='" . addslashes($pFood) . "', driver_tip=" . $pTip . ", order_receiving_method=" . $pDeliveryMethod . " WHERE id=" . $pFavoriteID;
                
            }
        }
        Log::write("Update customer favorites - users.php", "QUERY -- $mSQL", 'order', 0, 'user');
        dbAbstract::Update($mSQL);
        
        $this->loadUserFavorites();
        $this->saveToSession();
    }
    
	/*
	*	update favorite top amount and deliver method
	*/
    public function updateFavoriteTipAmountDeliveryMethod($pFavoriteID, $pTip, $pDeliveryMethod)
    {
        $mSQL = "UPDATE customer_favorites SET driver_tip=" . $pTip . ", order_receiving_method=" . $pDeliveryMethod . " WHERE id=" . $pFavoriteID;
        Log::write("Update customer favorites - users.php", "QUERY -- $mSQL", 'order', 0, 'user');
        dbAbstract::Update($mSQL);
        $this->loadUserFavorites();
        $this->saveToSession();
    }
    
	/*
	*	select payment menthod by favorite id
	*/
    public function selectPaymentMethodByFavoriteID($pFavoriteID)
    {
        $mSQL = "SELECT IFNULL(order_receiving_method, 1) AS PM from customer_favorites WHERE id=" . $pFavoriteID;
        $row  = dbAbstract::ExecuteObject($mSQL);
        return $row->PM;
    }
    
	/*
	*	update favorite delivery method
	*/
    public function updateFavoritesDeliveryMethod($pFavoriteID, $pDeliveryMethod)
    {
        Log::write("Update customer favorites - users.php", "QUERY -- UPDATE customer_favorites SET order_receiving_method=" . $pDeliveryMethod . " WHERE id=" . $pFavoriteID, 'order', 0, 'user');
        $mSQL = "UPDATE customer_favorites SET order_receiving_method=" . $pDeliveryMethod . " WHERE id=" . $pFavoriteID;
        dbAbstract::Update($mSQL);
    }
    
	/*
	*	save cc token with expiry date
	*/
    public function saveCCTokenWithExpiry($secure_data, $token, $default, $pCardExpiry)
    {
		global $loggedinuser;
        $type = substr($secure_data, 0, 1);
        $cc   = substr($secure_data, -4, 4);
        
        $mSQL   = "SELECT COUNT(*) AS total FROM general_detail WHERE id_2=" . $this->id . " AND data_type=$type AND data_1=$cc";
        
        $result = dbAbstract::ExecuteObject($mSQL);
        if (($result->total == 0) && ($type != 0) && ($cc != 0)) {
            $mSQL = "INSERT INTO general_detail (id_2,data_type,data_1,data_2,card_expiry) VALUES(" . $this->id . " ,'$type' ,'$cc','$token'," . $pCardExpiry . ")";
	
            if($loggedinuser->ssoUserId > 0){
                    $mSQL = "insert into general_detail(sso_user_id, id_2,data_type,data_1,data_2) values('".$loggedinuser->ssoUserId."' $userId ,'$type' ,'$cc','$data_2')";
            }
			
            dbAbstract::Insert($mSQL);
            if ($default == 1) {
                $this->setUserDefaultCard($token);
            }
            return true;
        } else {
            return false;
        }
    }
    
	/*
	*	select cc token details by user id
	*/
    public function selectCCTokenDetailsByUserID($pUserID, $pToken)
    {
        $mSQL = "SELECT * from general_detail WHERE id_2=" . $pUserID . " AND data_2=" . $pToken;
        return dbAbstract::ExecuteObject($mSQL);
    }
    
	/*
	*	select user id by facebook id and restaurant id
	*/
    public function selectUserIDByFBIDRestaurantID($pFBID, $pRID)
    {
        $mSQL   = "SELECT IFNULL(id, 0) AS UserID, IFNULL(cust_email, '') AS Email FROM customer_registration WHERE facebook_id='" . $pFBID . "' AND resturant_id=" . $pRID;
        $mQuery = dbAbstract::Execute($mSQL);
        if (dbAbstract::returnRowsCount($mQuery) > 0) {
            return dbAbstract::returnObject($mQuery);
        } else {
            return 0;
        }
    }
    
	/*
	*	select user id by twitter id and restaurant id
	*/
    public function selectUserIDByTwittwerIDRestaurantID($pFBID, $pRID)
    {
        $mSQL   = "SELECT IFNULL(id, 0) AS UserID, IFNULL(cust_email, '') AS Email FROM customer_registration WHERE twitter_id='" . $pFBID . "' AND resturant_id=" . $pRID;
        $mQuery = dbAbstract::Execute($mSQL);
        if (dbAbstract::returnRowsCount($mQuery) > 0) {
            return dbAbstract::returnObject($mQuery);
        } else {
            return 0;
        }
    }
    
	/*
	*	select user id by email and restaurant id
	*/
    public function selectUserIdByEmailRestaurantId($pEmail, $pRID)
    {
        $mSQL   = "SELECT IFNULL(id, 0) AS UserID, IFNULL(cust_email, '') AS Email FROM customer_registration WHERE cust_email='" . $pEmail . "' AND resturant_id=" . $pRID;
        $mQuery = dbAbstract::Execute($mSQL);
        if (dbAbstract::returnRowsCount($mQuery) > 0) {
            return dbAbstract::returnObject($mQuery);
        } else {
            return 0;
        }
    }
    
	/*
	*	update customer facebook id
	*/
    public function updateCustomerFacebookID($pUID, $pFBID)
    {
        $mSQL = "UPDATE customer_registration SET facebook_id='" . $pFBID . "' WHERE id=" . $pUID;
        dbAbstract::Update($mSQL);
    }
    
	/*
	*	update customer twitter id
	*/
    public function updateCustomerTwitterID($pUID, $pFBID)
    {
        $mSQL = "UPDATE customer_registration SET twitter_id='" . $pFBID . "' WHERE id=" . $pUID;
        dbAbstract::Update($mSQL);
    }
    
	/*
	*	get loggedin user email id
	*/
    public static function getLoggedinUserEmailID()
    {
        if (isset($_SESSION['loggedinuser'])) {
            $user = unserialize($_SESSION['loggedinuser']);
            return $user->cust_email;
        } else {
            return "";
        }
    }
    
    public function saveCCTokenForMobile($secure_data, $token, $default, $pCardExpiry,$x_card_name)
    {
		global $loggedinuser;
        $type = substr($secure_data, 0, 1);
        $cc   = substr($secure_data, -4, 4);
        
        $mSQL   = "SELECT COUNT(*) AS total FROM general_detail WHERE id_2=" . $this->id . " AND data_type=$type AND data_1=$cc";
        
        $result = dbAbstract::ExecuteObject($mSQL);
        if (($result->total == 0) && ($type != 0) && ($cc != 0)) {
            $mSQL = "INSERT INTO general_detail (id_2,data_type,data_1,data_2,card_expiry,card_name) VALUES(" . $this->id . " ,'$type' ,'$cc','$token'," . $pCardExpiry . ",'".$x_card_name."')";
	
            if($loggedinuser->ssoUserId > 0){
                    $mSQL = "insert into general_detail(sso_user_id, id_2,data_type,data_1,data_2,card_expiry,card_name) values('".$loggedinuser->ssoUserId."', '".$this->id."' ,'$type' ,'$cc','$token', '$pCardExpiry', '".$x_card_name."')";
            }
			
            $insertID = dbAbstract::Insert($mSQL,0,2);
            if ($default == 1) {
                $this->setUserDefaultCard($token);
            }
            return $insertID;
        } else {
            return false;
        }
    }
}


?>