<? 
class restaurant  {
	public $id;
	public $name;
	public $url;
	public  $owner_id;
	public $license_id;
	public $status;
	public $phone;
	public $fax;
	public $email;
	public $rest_order_email_fromat;
	public $voice_phone;
	public $payment_gateway;
	public $authoriseLoginID;
	public $transKey;
	public $order_destination;
	public $phone_notification;

	public $rest_address;
	public $rest_city;
	public $rest_state;
	public $rest_zip;
	public $facebook_link;

	public $useValutec;
	public $locationName;
	public $merchantID;
	public $locationID;
	public $terminalID;
	public $clientKey;
	public $isDoubleReward;
	public $numberofPoints;
	public $rewardAmount;
	public $rewardLevel;
	public $isOpenHour;
	public $openTime,$closeTime;
	public $reseller,$did_number;
	
	public function setemailformat($rest_order_email_fromat) {
		$this->rest_order_email_fromat =  ($rest_order_email_fromat=="" ?  "pdf" : $rest_order_email_fromat);
	}
	public function setorderdestination($order_destination) {
		$this->order_destination =  ($order_destination=="" ?  "fax" : $order_destination);
	}	

	public function saveValutec()   {
		$query="update  resturants set useValutec=". $this->useValutec ." 
									,locationName='". $this->locationName ."'
									,merchantID='". $this->merchantID ."'
									,locationID='". $this->locationID ."'
									,terminalID='". $this->terminalID ."'
									,clientKey='". $this->clientKey ."'
									,isDoubleReward='". $this->isDoubleReward ."'
									,numberofPoints='". $this->numberofPoints ."'
									,rewardAmount='". $this->rewardAmount ."'
									,rewardLevel='". $this->rewardLevel ."'
									where  id=". $this->id ."";

		mysql_query($query);

		$this->saveToSession();

	}
	
	public function getDetail($id) {

		$qry	=	mysql_query("select *,url_name as url,facebookLink as facebook_link	  from resturants where id = ".$id);
		@$objRestaurant	=	mysql_fetch_object($qry,"restaurant");
		@$objRestaurant->setRestaurantTimeZone();
			if($objRestaurant->did_number=='0') $objRestaurant->did_number='';
		$objRestaurant->saveToSession();
	
		return $objRestaurant;

	} 

	public function getDetailbyUrl($url) {
 
		$qry	=	mysql_query("select *,url_name as url,facebookLink as facebook_link	  from resturants where url_name = '".$url ."'");
		@$objRestaurant	=	mysql_fetch_object($qry,"restaurant");
		@$objRestaurant->setRestaurantTimeZone();
		
		$objRestaurant->credit_card=0;
		$objRestaurant->cash=0;
		
		if(trim($objRestaurant->authoriseLoginID)=='')
		{
			$objRestaurant->payment_method='cash';
			
		 }
		 
		if($objRestaurant->payment_method == 'credit' || $objRestaurant->payment_method == 'both' && trim($objRestaurant->authoriseLoginID) <>'')
			$objRestaurant->credit_card=1;	
		
		 if($objRestaurant->payment_method == 'cash' || $objRestaurant->payment_method == 'both') 
        		$objRestaurant->cash=1;	
		
		if(empty($objRestaurant->logo)){
			$objRestaurant->header_image = "../images/resturant_logos/default_200_by_200.jpg";
		} else {
			$objRestaurant->header_image = "../images/resturant_headers/" . $objRestaurant->header_image;
		}
	 	
		
		 
		if($objRestaurant->isDoubleReward==true)
		{
		  $objRestaurant->VIPMessage='Join our V.I.P. Card program and earn 1 point for every $1 you spend in the restaurant
	and Double Points for every $1 you spend online!';
		  $objRestaurant->rewardPoints=2;

		}else {
			 $objRestaurant->VIPMessage='Join our V.I.P. Card program and earn 1 point for every $1 you spend in the restaurant or
	online!';
			 $objRestaurant->rewardPoints=1;
			}
		
		define('ClientKey', $objRestaurant->clientKey);
		//TERMINAL ID
		//--------------------------------------
		define('TID', $objRestaurant->terminalID);
		//--------------------------------------
		define('ServerId', $objRestaurant->merchantID);
		
		$format=array("(",")","-"," ");
		$relpaces=array("","","","");
		
		$objRestaurant->voice_phone=str_replace($format,$relpaces,$objRestaurant->voice_phone);
		$objRestaurant->fax=str_replace($format,$relpaces,$objRestaurant->fax);
	
		$objRestaurant->isOpen();
		
		if($objRestaurant->rest_open_close=='0')
				$objRestaurant->isOpenHour=0;
			
		if($objRestaurant->status==1)
			$objRestaurant->loadreseller();
		 if($objRestaurant->did_number=='0') $objRestaurant->did_number='';
			
		return $objRestaurant;

	} 
	private function setRestaurantTimeZone() {

		$timezoneQry = mysql_query("SELECT  time_zone FROM times_zones WHERE id = ".$this->time_zone_id );
		@$timezoneRs = mysql_fetch_array($timezoneQry);
		date_default_timezone_set($timezoneRs['time_zone']);

	}
	private function isOpen() {
			$this->isOpenHour=0;
			$day_name=date('l');
			  if($day_name == 'Monday') {
					  $day_of_week = 0;
			   } else if($day_name == 'Tuesday') {
					  $day_of_week = 1; 
			   } else if($day_name == 'Wednesday') {
					  $day_of_week = 2; 
			   } else if($day_name == 'Thursday') {
					  $day_of_week = 3;
			   } else if($day_name == 'Friday') {
					  $day_of_week = 4; 
			   } else if($day_name == 'Saturday') {
					  $day_of_week = 5;
			   } else if($day_name == 'Sunday') {
					  $day_of_week = 6;
			   }
			 $businessHrQry =  mysql_query("SELECT open, close FROM business_hours WHERE day = $day_of_week AND rest_id = ". $this->id ."");
			 $business_hours=mysql_fetch_object($businessHrQry);
			 $current_time=date("Hi",time());
			 if($current_time >= $business_hours->open && $current_time <= $business_hours->close) {
						$this->isOpenHour=1;
			}
			$this->openTime=strtotime($business_hours->open);
			$this->closeTime=strtotime($business_hours->close);

					
		}
		public function allBusinessHours(){
			$qry= mysql_query("select *,'' as dayName  from business_hours where rest_id='". $this->id."' order by day asc");
			$arr_days=array();
			while($day=mysql_fetch_object($qry)){
				
				  if($day->day == 0) {
				 	 $day->dayName = 'Monday';
				  }else if($day->day == 1) {
					   $day->dayName= 'Tuesday';
				  }else if($day->day == 2) {
					   $day->dayName = 'Wednesday';
				  }else if($day->day == 3) {
					   $day->dayName = 'Thursday';
				  }else if($day->day == 4) {
					   $day->dayName = 'Friday';
				  }else if($day->day == 5) {
					   $day->dayName = 'Saturday';
				  }else if($day->day == 6) {
					   $day->dayName = 'Sunday';
				  }
				 $day->close=date("g:i A",strtotime($day->close));
				 $day->open=date("g:i A",strtotime($day->open));
				$arr_days[]=$day;
			 }
			 return $arr_days;
			
		 }

	public function saveToSession() {
		$_SESSION['restaurant_detail']=serialize($this);
	}
	
	public function getSession() {
		return unserialize($_SESSION['restaurant_detail']); 
	}
	public function loadreseller() {
			$this->reseller=new reseller();
			$ResellerInfoQry=mysql_query("select reseller_id from reseller_client where client_id=".$this->owner_id);
			$ResellerInfoRs=@mysql_fetch_array($ResellerInfoQry);
	 
			if(is_array($ResellerInfoRs) && $ResellerInfoRs["reseller_id"]!="247") {
				$reseller_id=$ResellerInfoRs["reseller_id"];
				
				$licenseQry=mysql_query("select status from licenses where id=".$this->license_id);
				$licenseRS=@mysql_fetch_array($licenseQry);
				
				if($licenseRS){
					if(strtolower($licenseRS['status'])!='activated'){
					 	$this->status=0;
					}
				}else $this->status=0;;
				
				$reseller_info_sql = "SELECT id, company_name,company_logo,company_logo_link,pdf_image_header,plain_text_header FROM users WHERE id = ". $reseller_id ;
				
				$reseller_info_qry		= @mysql_query( $reseller_info_sql);
				$this->reseller	= @mysql_fetch_object( $reseller_info_qry ,'reseller');
			 
			
			} 
			
		 
		}
}

class reseller {
	public $id,$company_name,$company_logo,$company_logo_link,$pdf_image_header,$plain_text_header;	
	  function __construct() {
			  $id=0;
			  $company_name='';
			  $company_logo='';
			  $company_logo_link='';
			  $pdf_image_header='';
			  $plain_text_header='';
		  }
	
	}

?>