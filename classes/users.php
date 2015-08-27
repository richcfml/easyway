<?php 
class users 
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
    public $ePassword;
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
        $this->arrTokens=array();
    }
    public function savetosession() 
    {
        $this->destroysession();
        $_SESSION['loggedinuser']=serialize($this);
    }

    public function loadfromsession() 
    {
        if(isset($_SESSION['loggedinuser']))
        {
		 	return unserializeData($_SESSION['loggedinuser']); 
        }
        else 
        {
            return NULL;
        }
    }

    public function destroysession() 
    {
        if(isset($_SESSION['loggedinuser']))
        {
            unset($_SESSION['loggedinuser']);
        }   
    }


    public function saveCard($InputCardNumber,$points,$balance) 
    {
        $mSQL = "update customer_registration set valuetec_card_number=". $InputCardNumber .",valutec_registeration_date='".  date("Y-m-d") . "' where id=". $this->id  .""; 
        dbAbstract::Update($mSQL); 
        $this->valuetec_card_number=$InputCardNumber;
        $this->valuetec_points=$points;
        $this->valuetec_reward=$balance;	
        $this->savetosession();

     }

    public function getDetailbyPhone($phone,$restaurantId)
    { 
        $mSQL = "SELECT * FROM customer_registration WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(cust_phone1,'(',''),')',''),'-',''),'',''),' ','')='". trim($phone)."' and resturant_id=". $this->resturant_id  ." and password!=''";								
        $user = dbAbstract::ExecuteObject($mSQL, 0, "users");
        return $user;						
    }

    public function savePhone() 
    {
        $qry="update customer_registration set cust_phone1='". prepareStringForMySQL($this->cust_phone1 ) ."' where id=". $this->id ."";
        dbAbstract::Update($qry);        
        $this->savetosession();
    }
        
    public function remindPassword($email,$objRestaurant,$objMail)
    {
            global $SiteUrl;
        $mSQL = "SELECT id,cust_email, password,cust_your_name,LastName FROM customer_registration WHERE cust_email='".prepareStringForMySQL($email)."' and resturant_id=". $objRestaurant->id  ."   AND LENGTH(password)>0";
        $userQuery = dbAbstract::Execute($mSQL);
        $numrow=dbAbstract::returnRowsCount($userQuery);		
        if ($numrow==0)
        { 
            return false;
        } 
        else 
        {
            $user = dbAbstract::returnObject($userQuery);
                $mObjFun=new clsFunctions();
                $mEncryptedID = $mObjFun->encrypt($user->id, "53cr3t9455w0rd");
                $mLink = $SiteUrl.$objRestaurant->url_name."/?item=resetpassword&id=".$mEncryptedID;
                $subject = "easywayordering.com Account Password Reminder";
                $body="Greetings, ". $user->cust_your_name . " ". $user->LastName ."! <br/><br/>".			
                $body .="Thank you for visiting www.easywayordering.com/".$objRestaurant->url  ."/. We hope that you will find our easywayordering system helpful for
								 your work and home food delivery needs.<br/><br/>
					
					Click following link to reset your password:<br/><a href='".$mLink."' target='_blank'>".$mLink."</a><br/><br/>
				 					
					We thank you for your business and look forward to serving you!<br/><br/>
					
					Kind regards,<br/><br/>
					
					www.easywayordering.com/".$objRestaurant->url  ."/<br/>
					Phone: ". $objRestaurant->phone ."<br/>
					Fax:  ". $objRestaurant->fax ;


            $objMail->from="info@easywayordering.com";
            $objMail->sendTo($body,$subject,$email);
            return true;
        }
    }

    public function login($email,$password,$restaurant_id) 
    {
        $mRow = dbAbstract::ExecuteObject("SELECT salt FROM customer_registration WHERE TRIM(LOWER(cust_email))='".prepareStringForMySQL($email)."' AND resturant_id=".$restaurant_id." AND TRIM(password)<>''");
        if ($mRow)
        {
            $mSalt = $mRow->salt;
            $email=prepareStringForMySQL($email);
            $password=hash('sha256', prepareStringForMySQL($password).$mSalt);
        
            $mSQL = "SELECT * FROM customer_registration WHERE cust_email='$email' AND epassword ='$password' AND resturant_id= '". $restaurant_id ."'";
            
            $user_qry  = dbAbstract::Execute($mSQL);
        
            if(dbAbstract::returnRowsCount($user_qry)>1 || dbAbstract::returnRowsCount($user_qry)==0) 
            {
            return NULL;
            }
        
            $user=dbAbstract::returnObject($user_qry, 0, "users");
            $user->delivery_address_choice=1;

            $user->getTokens();
            $user->loadfavorites();
            return $user;
        }
        else
        {
            return NULL;
        }
    }
    
    public function sso_login($email,$restaurant_id) 
    {
        $email=prepareStringForMySQL($email);

        $user_qry  = dbAbstract::Execute("select * from customer_registration where cust_email='$email' and resturant_id= '". $restaurant_id ."'", 1);

        if(dbAbstract::returnRowsCount($user_qry, 1)>1 || dbAbstract::returnRowsCount($user_qry, 1)==0) 
        {
            return NULL;
        }

        $user=dbAbstract::returnObject($user_qry, 1, "users");
        $user->delivery_address_choice=1;

        $user->getTokens();
        $user->loadfavorites();
        return $user;
    }
		
    public function loginbyid($id) 
    {
	$mSQL = "SELECT * FROM customer_registration WHERE id='$id'";
        $user_qry  = dbAbstract::Execute($mSQL);
        if(dbAbstract::returnRowsCount($user_qry)>1 || dbAbstract::returnRowsCount($user_qry)==0)
        {
            return NULL;
        }	

        $user=dbAbstract::returnObject($user_qry, 0, "users");
	return $user;	 	
    }
		
    public function loadfavorites() 
    {
        $this->arrFavorites=array();
        $mSQL = "SELECT * FROM customer_favorites WHERE customer_id=". $this->id;
        $favorites  = dbAbstract::Execute($mSQL);
        while($favorite = dbAbstract::returnObject($favorites)) 
        {
					$favorite->food=unserializeData($favorite->food);
            $this->arrFavorites[]=$favorite;
        }
    }
			
    public function getfavoritesTitles()
    {
           $this->arrFavorites=array();
            $mSQL = "SELECT title FROM customer_favorites WHERE customer_id=". $this->id ." AND rapidreorder=1";
            $favorites  = dbAbstract::Execute($mSQL);
            while($favorite = dbAbstract::returnObject($favorites)) 
            {
                $this->arrFavorites[]=$favorite->title;
            }      
    }
			
    public function getfavoritesbyTitle($title) 
    {
        $this->arrFavorites=array();
        $mSQL = "SELECT * FROM customer_favorites WHERE customer_id=". $this->id ." AND title='". addslashes($title) ."' AND rapidreorder=1 ORDER BY id DESC LIMIT 0,1";
        $favorites  = dbAbstract::Execute($mSQL);
        while($favorite = dbAbstract::returnObject($favorites)) 
        {
					$favorite->food=unserializeData($favorite->food);
            $this->arrFavorites[]=$favorite;
        }
        return $this->arrFavorites;
    }
			
    public function getfavoritesbyId($id) 
    {
        $this->arrFavorites=array();
        $mSQL = "SELECT * FROM customer_favorites WHERE customer_id=". $this->id ." AND id=". $id ." ORDER BY id DESC LIMIT 0,1";
        $favorites  = dbAbstract::Execute($mSQL);
        while($favorite = dbAbstract::returnObject($favorites)) 
        {
            $favorite->food=unserialize($favorite->food);
            $this->arrFavorites[]=$favorite;
        }
        return $this->arrFavorites;
    }
    public function removefavoriteOrder($index) 
    {
        $favorit_id=$this->arrFavorites[$index]->id;
        $mSQL = "DELETE FROM customer_favorites WHERE id=".$favorit_id;
        Log::write("Delete customer favorites - users.php", "QUERY -- ".$mSQL, 'order', 1 , 'user');
        dbAbstract::Delete($mSQL);
        unset($this->arrFavorites[$index]);
        $this->arrFavorites=array_values($this->arrFavorites);
        $this->loadfavorites();
        $this->savetosession();
    }
			
	public function changerepidreorderingstatus($index,$status) 
    {
        $favorit_id=$this->arrFavorites[$index]->id;
                                Log::write("Delete customer favorites - users.php", "QUERY -- update customer_favorites  set rapidreorder=$status where  id=". $favorit_id ."", 'order', 1 , 'user');
        $mSQL = "UPDATE customer_favorites SET rapidreorder=$status WHERE id=". $favorit_id;
        dbAbstract::Update($mSQL);
        $this->arrFavorites[$index]->rapidreorder=$status;
        $this->savetosession();
    }

    public function getTokens() 
    {
        $this->arrTokens=array();
        $mSQL = "SELECT * FROM general_detail WHERE id_2=". $this->id ." ORDER BY data_3 DESC";
        $tokens  =dbAbstract::Execute($mSQL);
        while($token = dbAbstract::returnObject($tokens)) 
        {
            $this->arrTokens[]=$token;
        }
    }

    public function getDefaultToken() 
    {
        $this->arrTokens=array();
        $mSQL = "SELECT * FROM general_detail WHERE id_2=". $this->id ." ORDER BY data_3 DESC LIMIT 0 ,1";
        return dbAbstract::ExecuteObject($mSQL);
    } 	
		 //data_type=card type
		 //data_1=last 4 digits
		 //data_2=card token
		 //data_3=3 default
    public function saveToken($secure_data,$token,$default)
    {
        $type=  substr($secure_data, 0,1);
        $cc=  substr($secure_data, -4, 4);
        $mSQL = "SELECT COUNT(*) AS total FROM general_detail WHERE id_2=". $this->id ." AND data_type=$type AND data_1=$cc";
        $result = dbAbstract::ExecuteObject($mSQL);
        if (($result->total==0) && ($type!=0) && ($cc!=0))
        {
            $mSQL = "INSERT INTO general_detail(id_2,data_type,data_1,data_2) VALUES(". $this->id  ." ,'$type' ,'$cc','$token')";
            dbAbstract::Insert($mSQL);
            if($default==1) 
            {
                $this->setDefaultCard($token);
            }
            return true;
        }
        else 
        {
            return false;
        }
    }	 
		
    public function setDefaultCard($token) 
    {
        $mSQL = "UPDATE general_detail SET data_3=0 WHERE id_2=".$this->id;
        dbAbstract::Update($mSQL);
        $mSQL = "UPDATE general_detail SET data_3=1 WHERE id_2=".$this->id ." AND data_2='". $token ."'";
        dbAbstract::Update($mSQL);
        $this->getTokens();
        $this->savetosession();	
    }
    
    public function update()  
    {
        $this->cust_odr_address=$this->street1.'~'.$this->street2 ;
        $this->delivery_address1=$this->delivery_street1.'~'.$this->delivery_street2 ;

        $qry="update customer_registration set
                cust_email='". prepareStringForMySQL($this->cust_email ) ."'
               , password='". prepareStringForMySQL($this->password ) ."'
               , cust_your_name='". prepareStringForMySQL($this->cust_your_name ) ."'
               , LastName='". prepareStringForMySQL($this->LastName ) ."'
               , cust_odr_address='". prepareStringForMySQL($this->street1 .'~'.$this->street2 ) ."'
               , cust_ord_city='". prepareStringForMySQL($this->cust_ord_city ) ."'
               , cust_ord_state='". prepareStringForMySQL($this->cust_ord_state ) ."'
               , cust_ord_zip='". prepareStringForMySQL($this->cust_ord_zip ) ."'
               , cust_phone1='". prepareStringForMySQL($this->cust_phone1 ) ."'
               , delivery_address1='". prepareStringForMySQL($this->delivery_street1.'~'.$this->delivery_street2 ) ."'
               , delivery_city1='". prepareStringForMySQL($this->delivery_city1 ) ."'
               , delivery_state1='". prepareStringForMySQL($this->delivery_state1 ) ."'
               , deivery1_zip='". prepareStringForMySQL($this->deivery1_zip ) ."'
               , ePassword='". prepareStringForMySQL($this->ePassword) ."'
               , salt='". prepareStringForMySQL($this->salt ) ."'
                 where id=". $this->id ."";
        
        dbAbstract::Update($qry);
        $this->savetosession();
        return true;

    }
			
    public function register(&$objRestaurant,&$objMail)  
    {
        $this->resturant_id= $objRestaurant->id;
	$mSQL = "SELECT cust_email FROM customer_registration WHERE cust_email='".prepareStringForMySQL($this->cust_email )."' AND resturant_id='".$objRestaurant->id."' AND password !='NULL'";
        $user_qry  = dbAbstract::Execute($mSQL);

        if(dbAbstract::returnRowsCount($user_qry)>0 ) 
        {
            return 0;
        }	
		
        $this->createNewUser();
        if(is_numeric ($this->id))
        {
                $this->delivery_address_choice=1;
                $this->savetosession();
                $this->sendRegisterationEmail($objRestaurant,$objMail);
                return true;
        }
        else
        {
                return false;
        }
    }
			
    public function createNewUser() 
    {
        $mFBID='';
        if (isset($this->facebook_id))
        {
                $mFBID= " , facebook_id='". dbAbstract::returnRealEscapedString($this->facebook_id) ."' ";
        }
        $this->cust_odr_address=$this->street1.'~'.$this->street2 ;
        $this->delivery_address1=$this->delivery_street1.'~'.$this->delivery_street2 ;

        $qry="insert into customer_registration set
                 cust_email='". prepareStringForMySQL($this->cust_email ) ."'
                , password='". prepareStringForMySQL($this->password ) ."'
                , cust_your_name='". prepareStringForMySQL($this->cust_your_name ) ."'
                , LastName='". prepareStringForMySQL($this->LastName ) ."'
                , cust_odr_address='". prepareStringForMySQL($this->street1 .'~'.$this->street2 ) ."'
                , cust_ord_city='". prepareStringForMySQL($this->cust_ord_city ) ."'
                , cust_ord_state='". prepareStringForMySQL($this->cust_ord_state ) ."'
                , cust_ord_zip='". prepareStringForMySQL($this->cust_ord_zip ) ."'
                , cust_phone1='". prepareStringForMySQL($this->cust_phone1 ) ."'
                , delivery_address1='". prepareStringForMySQL($this->delivery_street1.'~'.$this->delivery_street2 ) ."'
                , delivery_city1='". prepareStringForMySQL($this->delivery_city1 ) ."'
                , delivery_state1='". prepareStringForMySQL($this->delivery_state1 ) ."'
                , deivery1_zip='". prepareStringForMySQL($this->deivery1_zip) ."'".$mFBID."
                ,resturant_id=". $this->resturant_id."
                ,ePassword='". $this->ePassword ."'
                ,salt='". $this->salt ."'";

        $this->arrTokens=array();
        $this->id=dbAbstract::Insert($qry, 0, 2);
        $this->savetosession();
    }
		
		
    public function logout() 
    { 
        $this->destroysession();
    }
		
    public function set_delivery_address($option) 
    {
        $this->delivery_address_choice=$option;
        $this->savetosession();
    }
			 
    public function get_delivery_address($formated=1)
    {
	if($formated==1)
	{
		$char='<br/>'; 
	}
	else 
	{
		$char=', ';
	}

	if($this->delivery_address_choice==1)
	{
		$this->delivery_address=trim(trim(trim($this->street1) ." ". trim($this->street2)) .$char. $this->cust_ord_city .$char. $this->cust_ord_state.(($formated == 1)? $char.$this->cust_ord_zip : ""));
	}
	else if($this->delivery_address_choice>1)
	{
		$this->delivery_address=trim(trim(trim($this->delivery_street1) ." ". trim($this->delivery_street2)) .$char. $this->delivery_city1.$char.$this->delivery_state1 .(($formated ==1)? $char.$this->deivery1_zip : ""));
	}
	else if(!is_numeric($this->id))
	{
		$this->delivery_address=trim(trim(trim($this->street1) ." ". trim($this->street2)) .$char. $this->cust_ord_city .$char. $this->cust_ord_state.(($formated == 1)? $char.$this->cust_ord_zip : ""));
	}
	else
	{
		$this->delivery_address=trim(trim(trim($this->street1) ." ". trim($this->street2)) .$char. $this->cust_ord_city .$char. $this->cust_ord_state.(($formated == 1)? $char.$this->cust_ord_zip : ""));
	}
	
	return 	$this->delivery_address;
    }
			
    public function get_delivery_zip()
    {
        if($this->delivery_address_choice==1)
        {
                return $this->cust_ord_zip;
        }
        else if(!is_numeric($this->id))
        {

            return $this->cust_ord_zip;
        }
        else
        {
            return $this->deivery1_zip;
        }

    }
				 
    private function sendRegisterationEmail(&$objRestaurant,&$objMail) 
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
		 
    public function addtoMyFavorites($title,$food,$repidreodering,$order_receiving_method,$pTip) 
    {
        $mSQL = "insert into customer_favorites(restaurant_id,customer_id,title,food,rapidreorder,order_receiving_method,driver_tip) values(". $this->resturant_id.",". $this->id .",'". prepareStringForMySQL($title) ."','". prepareStringForMySQL($food) ."',$repidreodering,$order_receiving_method,$pTip)";	
        dbAbstract::Insert($mSQL);
        $this->loadfavorites();
        $this->savetosession();

    }	
				
    public function createclone()
    {
        if(isset($_SESSION['loggedinuser']))
        {
            $_SESSION['loggedinuserCLONE']=$this;
        }
    }
    
    public function myclone()
    {
        return $_SESSION['loggedinuserCLONE'] ;
    }

    public function destroyclone()
    {
        if(isset($_SESSION['loggedinuserCLONE']))
        {
            unset($_SESSION['loggedinuserCLONE']);
        }
    }
	
	
    //Following function updates the Favorite Item of Customer 
    public function UpdateFavorite($pFavoriteID, $pFood, $pTip, $pDeliveryMethod) 
    {
            if ($pTip==-1)
            {
                    if ($pDeliveryMethod==-1)
                    {
                                Log::write("Update customer favorites - users.php", "QUERY -- UPDATE customer_favorites SET food='".addslashes($pFood)."' WHERE id=".$pFavoriteID, 'order', 0 , 'user');
                        $mSQL = "UPDATE customer_favorites SET food='".addslashes($pFood)."' WHERE id=".$pFavoriteID;
                        dbAbstract::Update($mSQL);
                    }
                    else
                    {
                                Log::write("Update customer favorites - users.php", "QUERY -- UPDATE customer_favorites SET food='".addslashes($pFood)."', order_receiving_method=".$pDeliveryMethod." WHERE id=".$pFavoriteID, 'order', 0 , 'user');
                        $mSQL = "UPDATE customer_favorites SET food='".addslashes($pFood)."', order_receiving_method=".$pDeliveryMethod." WHERE id=".$pFavoriteID;
                        dbAbstract::Update($mSQL);
                    }
            }
            else if ($pTip>=0)
            {
                    if ($pDeliveryMethod==-1)
                    {
                                Log::write("Update customer favorites - users.php", "QUERY -- UPDATE customer_favorites SET food='".addslashes($pFood)."', driver_tip=".$pTip." WHERE id=".$pFavoriteID, 'order', 0 , 'user');
                        $mSQL = "UPDATE customer_favorites SET food='".addslashes($pFood)."', driver_tip=".$pTip." WHERE id=".$pFavoriteID;
                        dbAbstract::Update($mSQL);
                    }
                    else
                    {
                                Log::write("Update customer favorites - users.php", "QUERY -- UPDATE customer_favorites SET food='".addslashes($pFood)."', driver_tip=".$pTip.", order_receiving_method=".$pDeliveryMethod." WHERE id=".$pFavoriteID, 'order', 0 , 'user');
                        $mSQL = "UPDATE customer_favorites SET food='".addslashes($pFood)."', driver_tip=".$pTip.", order_receiving_method=".$pDeliveryMethod." WHERE id=".$pFavoriteID;
                        dbAbstract::Update($mSQL);
                    }
            }
            $this->loadfavorites();
            $this->savetosession();
    }

    public function UpdateFavoriteTipDelMethod($pFavoriteID, $pTip, $pDeliveryMethod) 
    {
                Log::write("Update customer favorites - users.php", "QUERY -- UPDATE customer_favorites SET driver_tip=".$pTip.", order_receiving_method=".$pDeliveryMethod." WHERE id=".$pFavoriteID, 'order', 0 , 'user');
        $mSQL = "UPDATE customer_favorites SET driver_tip=".$pTip.", order_receiving_method=".$pDeliveryMethod." WHERE id=".$pFavoriteID;	
        dbAbstract::Update($mSQL);
        $this->loadfavorites();
        $this->savetosession();
    }

    public function SelectCountFavorite($pFavoriteTitle, $pUserID) 
    {
        $mSQL = "SELECT COUNT(*) AS favCount from customer_favorites WHERE TRIM(LOWER(title))='".strtolower(trim($pFavoriteTitle))."' AND customer_id=".$pUserID;
        $row = dbAbstract::ExecuteObject($mSQL);
        return $row->favCount;
    }

    public function SelectPaymentMethodByFavoriteID($pFavoriteID) 
    {
        $mSQL = "SELECT IFNULL(order_receiving_method, 1) AS PM from customer_favorites WHERE id=".$pFavoriteID;
        $row = dbAbstract::ExecuteObject($mSQL);
        return $row->PM;
    }

    public function UpdateDeliveryMethod($pFavoriteID, $pDeliveryMethod) 
    {
                Log::write("Update customer favorites - users.php", "QUERY -- UPDATE customer_favorites SET order_receiving_method=".$pDeliveryMethod." WHERE id=".$pFavoriteID, 'order', 0 , 'user');
        $mSQL = "UPDATE customer_favorites SET order_receiving_method=".$pDeliveryMethod." WHERE id=".$pFavoriteID;
        dbAbstract::Update($mSQL);		
    }

    public function SelectDataTypeByTokenUserID($pUserID, $pToken) 
    {
        $mSQL = "SELECT IFNULL(data_type, 4) AS DataType from general_detail WHERE id_2=".$pUserID." AND data_2=".$pToken;
        $row = dbAbstract::ExecuteObject($mSQL);
        return $row->DataType;
    }

    public function SelectCardExpiryByTokenUserID($pUserID, $pToken) 
    {
        $mSQL = "SELECT IFNULL(card_expiry, 0) AS CardExpiry from general_detail WHERE id_2=".$pUserID." AND data_2=".$pToken;
        $row = dbAbstract::ExecuteObject($mSQL);
        return $row->CardExpiry;
    }

    public function saveTokenWithExpiry($secure_data,$token,$default,$pCardExpiry)
    {
        $type=  substr($secure_data, 0,1);
        $cc=  substr($secure_data, -4, 4);

        $mSQL = "SELECT COUNT(*) AS total FROM general_detail WHERE id_2=". $this->id ." AND data_type=$type AND data_1=$cc";
        $result = dbAbstract::ExecuteObject($mSQL);	 
        if (($result->total==0) && ($type!=0) && ($cc!=0))
        {
            $mSQL = "INSERT INTO general_detail (id_2,data_type,data_1,data_2,card_expiry) VALUES(". $this->id  ." ,'$type' ,'$cc','$token',".$pCardExpiry.")";
            dbAbstract::Insert($mSQL);
            if($default==1) 
            {
                $this->setDefaultCard($token);
            }
            return true;
        }
        else 
        {
            return false;
        }
    }

    public function SelectTokenDetailsByTokenUserID($pUserID, $pToken) 
    {
        $mSQL = "SELECT * from general_detail WHERE id_2=".$pUserID." AND data_2=".$pToken;
        return dbAbstract::ExecuteObject($mSQL);		 
    }

    public function SelectUserIDByFBIDRestaurantID($pFBID, $pRID)
    {
        $mSQL = "SELECT IFNULL(id, 0) AS UserID, IFNULL(password, '') AS Password, IFNULL(cust_email, '') AS Email FROM customer_registration WHERE facebook_id='".$pFBID."' AND resturant_id=".$pRID;
        $mQuery = dbAbstract::Execute($mSQL);
        if (dbAbstract::returnRowsCount($mQuery)>0)
        {
            return dbAbstract::returnObject($mQuery);
        }
        else
        {
            return 0;
        }
    }

    public function SelectUserIDByEmailRestaurantID($pEmail, $pRID)
    {
        $mSQL = "SELECT IFNULL(id, 0) AS UserID, IFNULL(password, '') AS Password, IFNULL(cust_email, '') AS Email FROM customer_registration WHERE cust_email='".$pEmail."' AND resturant_id=".$pRID;
        $mQuery = dbAbstract::Execute($mSQL);
        if (dbAbstract::returnRowsCount($mQuery)>0)
        {
            return dbAbstract::returnObject($mQuery);
        }
        else
        {
            return 0;
        }
    }

    public function UpdateFaceBookID($pUID, $pFBID) 
    {
        $mSQL = "UPDATE customer_registration SET facebook_id='".$pFBID."' WHERE id=".$pUID;
        dbAbstract::Update($mSQL);
    }

    public static function loggedinUserEmail()
    {
        if(isset($_SESSION['loggedinuser']))
        {
            $user = unserialize($_SESSION['loggedinuser']); 
            return $user->cust_email;
        } 
        else 
        {
            return "";
        }            
    }	
}
	

?>