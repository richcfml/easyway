<?php 
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
	public $srid;

	public $active_menu_link_color, 
		$appearence, 
		$cell_bg_color, 
		$cell_bg_image, 
		$cell_bg_image_strech_or_tile, 
		$inactive_menu_link_color, 
		$items_and_prices_font_size, 
		$menu_bg_color, 
		$min_width_of_the_container, 
		$order_online_button_image, 
		$restaurant_slug, 
		$show_loyalty_box_about_the_cart, 
		$sub_menu_descriptions_color, 
		$sub_menu_headings_background_color, 
		$sub_menu_headings_background_image,
		$sub_menu_headings_color, 
		$titles_font_family, 
		$titles_font_size, 
		$your_order_summary_color, 
		$your_order_summary_font_size,
		$show_item_pictures_and_description,
		$iframe_height,
		$iframe_height_infinite,
		$cell_border_color,
		$cell_border_thickness,
		$vip_progress_bar_color,
		$general_font_size,
		$general_text_color,
		$secondary_text_color,
		$items_title_color,
		$items_price_color,
		$items_description_color,
		$easyway_url,
		$chargify_subscription_id,
		$chargify_subscription_status;
	
	public function setemailformat($rest_order_email_fromat) {
		$this->rest_order_email_fromat =  ($rest_order_email_fromat=="" ?  "pdf" : $rest_order_email_fromat);
	}
	public function setorderdestination($order_destination) {
		$this->order_destination =  ($order_destination=="" ?  "fax" : $order_destination);
	}	

	public function saveValutec() {
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
									,terminalUserName='". $this->terminalUserName ."'
									where  id=". $this->id ."";
		mysql_query($query);
		$this->saveToSession();
	}
	
	public function getRestaurantDesignSettings($host) {
		$qry = mysql_query("select * from wp_restaurent_design_settings where restaurant_id=".$this->id . " AND easyway_url='$host' ORDER BY date_added desc");
		@$objRestaurant	=	mysql_fetch_object($qry, "restaurant");
		return $objRestaurant;
	}
	
	public function getDetail($id) {

		$qry	=	mysql_query("select *,url_name as url,facebookLink as facebook_link	  from resturants where id = ".$id);
		@$objRestaurant	=	mysql_fetch_object($qry,"restaurant");
		@$objRestaurant->setRestaurantTimeZone();
			if($objRestaurant->did_number=='0') $objRestaurant->did_number='';
		$objRestaurant->saveToSession();
	
		return $objRestaurant;

	} 
	public function getResturantIdbyUrl($url) {
		$qry	=	mysql_query("select id from resturants where url_name = '".$url ."'");
		@$objRestaurant	=	mysql_fetch_object($qry, "restaurant");
		return $objRestaurant->id;
	}
	
	public function getDetailbyUrl($url) {
 
		$qry	=	mysql_query("select *,url_name as url,facebookLink as facebook_link	  from resturants where url_name = '".$url ."'");
		@$objRestaurant	=	mysql_fetch_object($qry,"restaurant");
		@$objRestaurant->setRestaurantTimeZone();
		
		$objRestaurant->credit_card=0;
		$objRestaurant->cash=0;
		
		if(trim($objRestaurant->authoriseLoginID)=='') {
			$objRestaurant->payment_method='cash';
			
		}
		 
		if($objRestaurant->payment_method == 'credit' || $objRestaurant->payment_method == 'both' && trim($objRestaurant->authoriseLoginID) <>'')
			$objRestaurant->credit_card=1;	
		
		 if($objRestaurant->payment_method == 'cash' || $objRestaurant->payment_method == 'both') 
        		$objRestaurant->cash=1;	
		
		if(empty($objRestaurant->header_image)){
			$objRestaurant->header_image = "../images/default_200_by_200.jpg";
		} else {
			$objRestaurant->header_image = "../images/resturant_headers/" . $objRestaurant->header_image;
		}
	 	
		
		 
		if($objRestaurant->isDoubleReward==true) {
			$objRestaurant->VIPMessage='Join our V.I.P. Card program and earn 1 point for every $1 you spend in the restaurant
	and Double Points for every $1 you spend online!';
		  $objRestaurant->rewardPoints=2;

		} else {
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
		//--------------------------------------
		define('TUNAME', $objRestaurant->terminalUserName);
		
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
                         while($business_hours=mysql_fetch_object($businessHrQry)){
			 $current_time=date("Hi",time());
			 if($current_time >= $business_hours->open && $current_time <= $business_hours->close) {
						$this->isOpenHour=1;
			}
                        
                        if(strrpos($business_hours->open,"-") !== FALSE){
                            $business_hours->open = "0000";
                        }
                        
                        if(strrpos($business_hours->close,"-") !== FALSE){
                            $business_hours->close = "0100";
                        }

			$this->openTime=strtotime($business_hours->open);
			$this->closeTime=strtotime($business_hours->close);
                        if($this->isOpenHour==1){
                            break;
                        }
                       }
					
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

                                if(strrpos($day->open,"-") !== FALSE){
                                    $day->open = "0000";
                                }

                                if(strrpos($day->close,"-") !== FALSE){
                                    $day->close = "0100";
                                }

				 $day->close=date("g:i A",strtotime($day->close));
				 $day->open=date("g:i A",strtotime($day->open));
				$arr_days[]=$day;
			 }
			 return $arr_days;
			
		 }

                 public function DayBusinessHours($pDayNumber){
			$qry= mysql_query("SELECT *,'' as dayName FROM business_hours WHERE rest_id='". $this->id."' AND day=".$pDayNumber." ORDER BY open ASC");
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

                                if ((strrpos($day->open,"-") === FALSE) && (strrpos($day->close,"-") === FALSE))
                                {
                                    $day->open=date("g:i A",strtotime($day->open));
                                    $day->close=date("g:i A",strtotime($day->close));
                                }
                                else if ((strrpos($day->open,"-") !== FALSE) && (strrpos($day->close,"-") !== FALSE))
                                {
                                    $day->open = "Closed";
                                    $day->close = "Closed";
                                }
                                else if (strrpos($day->open,"-") !== FALSE)
                                {
                                    $day->open = "0000";
                                    $day->open=date("g:i A",strtotime($day->open));
                                    $day->close=date("g:i A",strtotime($day->close));
                                }
                                else if (strrpos($day->close,"-") !== FALSE)
                                {
                                    $day->close = "2359";
                                    $day->open=date("g:i A",strtotime($day->open));
                                    $day->close=date("g:i A",strtotime($day->close));
                                }
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
	/* Gulfam Added Functions Start */
	function SelectCountIframeSettingsByRestaurantID($pRestaurantID)
	{
		$mSQLQuery = "SELECT COUNT(*) AS SettingsCount FROM iframe_settings WHERE RestaurantID=".$pRestaurantID; 
		$mResult = mysql_query($mSQLQuery);
		if (mysql_num_rows($mResult)>0)
		{
			$mRow = mysql_fetch_object($mResult);
			return $mRow->SettingsCount;
		}
		else
		{
			return -1;
		}
	}
	
	function SelectIframeDetailsByRestaurantID($pRestaurantID)
	{
		$mSQLQuery = "SELECT IFNULL(ShowLoyaltyBox, 0) AS ShowLoyaltyBox, IFNULL(LayoutStyle, 0) AS LayoutStyle, IFNULL(ShowPicturesDescription, 0) AS ShowPicturesDescription, IFNULL(CellBGImageStretchTile, 0) AS CellBGImageStretchTile, IFNULL(OrderOnlineButtonImage, '') AS OrderOnlineButtonImage, IFNULL(CellBGImage, '') AS CellBGImage, IFNULL(GeneralFontSize, '12') AS GeneralFontSize, IFNULL(GeneralTextColor, '000000') AS GeneralTextColor, IFNULL(SecondaryTextColor, '000000') AS SecondaryTextColor, IFNULL(MenuBGColor, 'F4F4F4') AS MenuBGColor, IFNULL(MenuLinkColorOnActive, 'CC0000') AS MenuLinkColorOnActive, IFNULL(MenuLinkColorOnInactive, '333333') AS MenuLinkColorOnInactive, IFNULL(SubMenuHeadingsColor, '585858') AS SubMenuHeadingsColor, IFNULL(SubMenuDescriptionsColor, '585858') AS SubMenuDescriptionsColor, IFNULL(ItemsTitleColor, '000000') AS ItemsTitleColor, IFNULL(ItemsPriceColor, '000000') AS ItemsPriceColor, IFNULL(ItemsDscriptionColor, '000000') AS ItemsDscriptionColor, ";
		$mSQLQuery = $mSQLQuery." IFNULL(ItemsPricesFontSize, '14') AS ItemsPricesFontSize, IFNULL(YourOrderSummaryColor, '5F5F5F') AS YourOrderSummaryColor, IFNULL(YourOrderSummaryFontSize, '18') AS YourOrderSummaryFontSize, IFNULL(CellBGColor, 'fff') AS CellBGColor, IFNULL(CellBorderColor, 'e4e4e4') AS CellBorderColor, IFNULL(CellBorderThickness, '1') AS CellBorderThickness, IFNULL(TitlesFontSize, '12') AS TitlesFontSize, IFNULL(TitlesFont, 'Arial,Helvetica,sans-serif') AS TitlesFont, IFNULL(MinWidthOfTheContainer, '0') AS MinWidthOfTheContainer, IFNULL(ColorForVIPProgressBar, '00CCFF') AS ColorForVIPProgressBar FROM iframe_settings WHERE RestaurantID=".$pRestaurantID; 
		$mResult = mysql_query($mSQLQuery);
		if (mysql_num_rows($mResult)>0)
		{
			return mysql_fetch_object($mResult);
		}
		else
		{
			return 0;
		}
	}
	
	function SelectOOBICBIByRestaurantID($pRestaurantID)
	{
		$mSQLQuery = "SELECT IFNULL(OrderOnlineButtonImage, '') AS OrderOnlineButtonImage, IFNULL(CellBGImage, '') AS CellBGImage FROM iframe_settings WHERE RestaurantID=".$pRestaurantID; 

		$mResult = mysql_query($mSQLQuery);
		if (mysql_num_rows($mResult)>0)
		{
			return mysql_fetch_object($mResult);
		}
		else
		{
			return 0;
		}
	}
	
	function InsertIframeDetailsByRestaurantID($pRestaurantID, $pGeneralFontSize, $pGeneralTextColor, $pSecondaryTextColor, $pMenuBGColor, $pMenuLinkColorOnActive, $pMenuLinkColorOnInactive, $pSubMenuHeadingsColor, $pSubMenuDescriptionsColor, $pItemsTitleColor, $pItemsPriceColor, $pItemsDscriptionColor, $pItemsPricesFontSize, $pYourOrderSummaryColor, $pYourOrderSummaryFontSize, $pCellBGcolor, $pCellBorderColor, $pCellBorderThickness, $pTitlesFontSize, $pTitlesFont, $pMinWidthOfTheContainer, $pColorForVIPProgressBar, $pOrderOnlineButtonImage, $pCellBGImage, $pCellBGImageStretchTile, $pShowLoyaltyBox, $pLayoutStyle, $pShowPicturesDescription)
	{
		$mSQLQuery = "INSERT INTO iframe_settings (RestaurantID, GeneralFontSize, GeneralTextColor, SecondaryTextColor, MenuBGColor, MenuLinkColorOnActive, MenuLinkColorOnInactive, SubMenuHeadingsColor, SubMenuDescriptionsColor, ItemsTitleColor, ItemsPriceColor, ItemsDscriptionColor, ItemsPricesFontSize, YourOrderSummaryColor, YourOrderSummaryFontSize, CellBGcolor, CellBorderColor, CellBorderThickness, TitlesFontSize, TitlesFont, MinWidthOfTheContainer, ColorForVIPProgressBar, OrderOnlineButtonImage, CellBGImage, CellBGImageStretchTile, ShowLoyaltyBox, LayoutStyle, ShowPicturesDescription) ";
		$mSQLQuery = $mSQLQuery."VALUES (".$pRestaurantID.", ".$pGeneralFontSize.", '".$pGeneralTextColor."', '".$pSecondaryTextColor."', '".$pMenuBGColor."', '".$pMenuLinkColorOnActive."', '".$pMenuLinkColorOnInactive."', '".$pSubMenuHeadingsColor."', '".$pSubMenuDescriptionsColor."', '".$pItemsTitleColor."', '".$pItemsPriceColor."', '".$pItemsDscriptionColor."', ".$pItemsPricesFontSize.", '".$pYourOrderSummaryColor."', ".$pYourOrderSummaryFontSize.", '".$pCellBGcolor."', '".$pCellBorderColor."', ".$pCellBorderThickness.", ".$pTitlesFontSize.", '".$pTitlesFont."', ";
		$mSQLQuery = $mSQLQuery.$pMinWidthOfTheContainer.", '".$pColorForVIPProgressBar."', '".$pOrderOnlineButtonImage."', '".$pCellBGImage."', ".$pCellBGImageStretchTile.", ".$pShowLoyaltyBox.", ".$pLayoutStyle.", ".$pShowPicturesDescription.")";
		return mysql_query($mSQLQuery);
	}
	
	function UpdateIframeDetailsByRestaurantID($pRestaurantID, $pGeneralFontSize, $pGeneralTextColor, $pSecondaryTextColor, $pMenuBGColor, $pMenuLinkColorOnActive, $pMenuLinkColorOnInactive, $pSubMenuHeadingsColor, $pSubMenuDescriptionsColor, $pItemsTitleColor, $pItemsPriceColor, $pItemsDscriptionColor, $pItemsPricesFontSize, $pYourOrderSummaryColor, $pYourOrderSummaryFontSize, $pCellBGcolor, $pCellBorderColor, $pCellBorderThickness, $pTitlesFontSize, $pTitlesFont, $pMinWidthOfTheContainer, $pColorForVIPProgressBar, $pOrderOnlineButtonImage, $pCellBGImage, $pCellBGImageStretchTile, $pShowLoyaltyBox, $pLayoutStyle, $pShowPicturesDescription)
	{
		$mSQLQuery = "UPDATE iframe_settings SET GeneralFontSize=".$pGeneralFontSize.", GeneralTextColor='".$pGeneralTextColor."', SecondaryTextColor='".$pSecondaryTextColor."', MenuBGColor='".$pMenuBGColor."', MenuLinkColorOnActive='".$pMenuLinkColorOnActive."', MenuLinkColorOnInactive='".$pMenuLinkColorOnInactive."', SubMenuHeadingsColor='".$pSubMenuHeadingsColor."', SubMenuDescriptionsColor='".$pSubMenuDescriptionsColor."', ItemsTitleColor='".$pItemsTitleColor."', ItemsPriceColor='".$pItemsPriceColor."', ItemsDscriptionColor='".$pItemsDscriptionColor."', ItemsPricesFontSize=".$pItemsPricesFontSize.", YourOrderSummaryColor='".$pYourOrderSummaryColor."', YourOrderSummaryFontSize=".$pYourOrderSummaryFontSize.", CellBGcolor='".$pCellBGcolor."', CellBorderColor='".$pCellBorderColor."', CellBorderThickness=".$pCellBorderThickness.", TitlesFontSize=".$pTitlesFontSize.", TitlesFont='".$pTitlesFont."', MinWidthOfTheContainer=".$pMinWidthOfTheContainer.", ";
		$mSQLQuery = $mSQLQuery."ColorForVIPProgressBar='".$pColorForVIPProgressBar."', OrderOnlineButtonImage='".$pOrderOnlineButtonImage."', CellBGImage='".$pCellBGImage."', CellBGImageStretchTile=".$pCellBGImageStretchTile.", ShowLoyaltyBox=".$pShowLoyaltyBox.", LayoutStyle=".$pLayoutStyle.", ShowPicturesDescription=".$pShowPicturesDescription." WHERE RestaurantID=".$pRestaurantID;
		return mysql_query($mSQLQuery);
	}
	
	function UpdateOOBIByRestaurantID($pRestaurantID, $pOrderOnlineButtonImage)
	{
		$mSQLQuery = "UPDATE iframe_settings SET OrderOnlineButtonImage='".$pOrderOnlineButtonImage."' WHERE RestaurantID=".$pRestaurantID;
		return mysql_query($mSQLQuery);
	}
	
	function UpdateCBIByRestaurantID($pRestaurantID, $pCellBGImage)
	{
		$mSQLQuery = "UPDATE iframe_settings SET CellBGImage='".$pCellBGImage."' WHERE RestaurantID=".$pRestaurantID;
		return mysql_query($mSQLQuery);
	}
	/* Gulfam Added Functions End */
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