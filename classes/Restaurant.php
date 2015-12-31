<?php 
class Restaurant  
{
    public $id;
    public $name;
    public $url;
    public $owner_id;
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
    public $bh_restaurant;
    public $bh_featured;
    public $URL;
    public $bh_banner_image;
    public $logo;
    public $optionl_logo;
    public $header_image;
    public $header_vip_image;
    public $meta_keywords;
    public $meta_description;

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
    
    /**
     * save loyality advance setting
     */
    public function saveLoyalitySetting() {
        $query="update resturants set useValutec=". $this->useValutec ." 
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
        dbAbstract::Update($query);
        $this->saveToSession();
    }
    
    /**
     * 
     * @param type $host
     * @return type return restaurant design seeting for word press
     */
    public function getRestaurantDesignSettings($host) {
        $mSQL = "select * from wp_restaurent_design_settings where restaurant_id=".$this->id . " AND easyway_url='$host' ORDER BY date_added desc";
        $objRestaurant = dbAbstract::ExecuteObject($mSQL, 0, "restaurant");
        return $objRestaurant;
    }
	
    /**
     * 
     * @param type $id
     * @return type return restauarnt details
     */
    public function getDetailByRestaurantID($id) {
        $mSQL = "select *,url_name as url,facebookLink as facebook_link	  from resturants where id = ".$id;
        $objRestaurant = dbAbstract::ExecuteObject($mSQL, 0, "restaurant");
        $objRestaurant->setRestaurantTimeZone();
        if($objRestaurant->did_number=='0') {
            $objRestaurant->did_number='';
        }
        $objRestaurant->saveToSession();
        return $objRestaurant;

    } 
	
    /**
     * 
     * @param type $url
     * @return type return restauarnt details
     */
    public function getDetailByRestaurantUrl($url) {
        $mSQL = "select *,url_name as url,facebookLink as facebook_link	  from resturants where url_name = '".$url ."'";
        $objRestaurant = dbAbstract::ExecuteObject($mSQL, 0, "restaurant");
        $objRestaurant->setRestaurantTimeZone();

        $objRestaurant->credit_card=0;
        $objRestaurant->cash=0;

        if(trim($objRestaurant->authoriseLoginID)=='') {
                $objRestaurant->payment_method='cash';
        }

        if($objRestaurant->payment_method == 'credit' || $objRestaurant->payment_method == 'both' && trim($objRestaurant->authoriseLoginID) <>''){
            $objRestaurant->credit_card=1;	
        }

        if($objRestaurant->payment_method == 'cash' || $objRestaurant->payment_method == 'both') {
            $objRestaurant->cash=1;	
        }	

        if(empty($objRestaurant->header_image)) {
            $objRestaurant->header_image = "../images/default_200_by_200.jpg";
        } 
        else {
            $objRestaurant->header_image = "../images/resturant_headers/" . $objRestaurant->header_image;
        }
        
        if(empty($objRestaurant->bh_banner_image)) {
            $objRestaurant->bh_banner_image = "../images/default_200_by_200.jpg";
        } 
        else {
            $objRestaurant->bh_banner_image = "../images/resturant_bh_banner/" . $objRestaurant->bh_banner_image;
        }

        if($objRestaurant->isDoubleReward==true) {
            $objRestaurant->VIPMessage='Join our V.I.P. Card program and earn 1 point for every $1 you spend in the restaurant and Double Points for every $1 you spend online!';
            $objRestaurant->rewardPoints=2;

        } 
        else {
            $objRestaurant->VIPMessage='Join our V.I.P. Card program and earn 1 point for every $1 you spend in the restaurant or online!';
            $objRestaurant->rewardPoints=1;
        }

        define('ClientKey', $objRestaurant->clientKey);
        //TERMINAL ID
        define('TID', $objRestaurant->terminalID);
        define('ServerId', $objRestaurant->merchantID);
        define('TUNAME', $objRestaurant->terminalUserName);

        $format=array("(",")","-"," ");
        $relpaces=array("","","","");

        $objRestaurant->voice_phone=str_replace($format,$relpaces,$objRestaurant->voice_phone);
        $objRestaurant->fax=str_replace($format,$relpaces,$objRestaurant->fax);

        $objRestaurant->isOpen();

        if($objRestaurant->rest_open_close=='0') {
            $objRestaurant->isOpenHour=0;
        }

        if($objRestaurant->status==1) {
            $objRestaurant->getResellerByOwnerID();
        }
        if($objRestaurant->did_number=='0') {
            $objRestaurant->did_number='';
        }

        return $objRestaurant;
    } 
    
    private function setRestaurantTimeZone() {
        $mSQL = "SELECT  time_zone FROM times_zones WHERE id = ".$this->time_zone_id;
        $timezoneRs = dbAbstract::ExecuteArray($mSQL);
        date_default_timezone_set($timezoneRs['time_zone']);
    }
    
    /**
     * check restaurant is open
     */
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
        
        $mSQL =  "SELECT open, close FROM business_hours WHERE day = $day_of_week AND rest_id = ". $this->id ."";
        $businessHrQry =  dbAbstract::Execute($mSQL);
        
        while($business_hours=dbAbstract::returnObject($businessHrQry)) {
            $current_time=date("Hi",time());
            if($current_time >= $business_hours->open && $current_time <= $business_hours->close) {
                $this->isOpenHour=1;
            }

            if(strrpos($business_hours->open,"-") !== FALSE) {
                $business_hours->open = "0000";
            }

            if(strrpos($business_hours->close,"-") !== FALSE){
                $business_hours->close = "0100";
            }

            $this->openTime=strtotime($business_hours->open);
            $this->closeTime=strtotime($business_hours->close);
            if($this->isOpenHour==1) {
                break;
            }
       }
    }
    
    /**
     * 
     * @return type get restaurant buisness hours
     */
    public function getBusinessHoursByRestaurantID() {
        $mSQL= "select *,'' as dayName  from business_hours where rest_id='". $this->id."' order by day asc";
        $qry= dbAbstract::Execute($mSQL);
        $arr_days=array();
        while($day=dbAbstract::returnObject($qry)) {
            if($day->day == 0) {
                $day->dayName = 'Monday';
            } else if($day->day == 1) {
                $day->dayName= 'Tuesday';
            } else if($day->day == 2) {
                $day->dayName = 'Wednesday';
            } else if($day->day == 3) {
                $day->dayName = 'Thursday';
            } else if($day->day == 4) {
                $day->dayName = 'Friday';
            } else if($day->day == 5) {
                $day->dayName = 'Saturday';
            } else if($day->day == 6) {
                $day->dayName = 'Sunday';
            }

            if(strrpos($day->open,"-") !== FALSE) {
                $day->open = "0000";
            }

            if(strrpos($day->close,"-") !== FALSE) {
                $day->close = "0100";
            }

            $day->close=date("g:i A",strtotime($day->close));
            $day->open=date("g:i A",strtotime($day->open));
            $arr_days[]=$day;
        }
            return $arr_days;
    }

    public function getBusinessHoursByDay($pDayNumber) {
           $mSQL = "SELECT *,'' as dayName FROM business_hours WHERE rest_id='". $this->id."' AND day=".$pDayNumber." ORDER BY open ASC";
           $qry = dbAbstract::Execute($mSQL);
           $arr_days=array();
           while($day = dbAbstract::returnObject($qry)) {
                if($day->day == 0) {
                    $day->dayName = 'Monday';
                } else if($day->day == 1) {
                    $day->dayName= 'Tuesday';
                } else if($day->day == 2) {
                    $day->dayName = 'Wednesday';
                } else if($day->day == 3) {
                    $day->dayName = 'Thursday';
                } else if($day->day == 4) {
                    $day->dayName = 'Friday';
                } else if($day->day == 5) {
                    $day->dayName = 'Saturday';
                } else if($day->day == 6) {
                    $day->dayName = 'Sunday';
                }

                if ((strrpos($day->open,"-") === FALSE) && (strrpos($day->close,"-") === FALSE)) {
                    $day->open=date("g:i A",strtotime($day->open));
                    $day->close=date("g:i A",strtotime($day->close));
                }
                else if ((strrpos($day->open,"-") !== FALSE) && (strrpos($day->close,"-") !== FALSE)) {
                    $day->open = "Closed";
                    $day->close = "Closed";
                }
                else if (strrpos($day->open,"-") !== FALSE) {
                    $day->open = "0000";
                    $day->open=date("g:i A",strtotime($day->open));
                    $day->close=date("g:i A",strtotime($day->close));
                }
                else if (strrpos($day->close,"-") !== FALSE) {
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
    
    /*
     * get resseler details
     */
    public function getResellerByOwnerID() {
        $this->reseller=new reseller();
        $mSQL = "select reseller_id from reseller_client where client_id=".$this->owner_id;
        $ResellerInfoRs = dbAbstract::ExecuteArray($mSQL);

        if(is_array($ResellerInfoRs) && $ResellerInfoRs["reseller_id"]!="247") {
            $reseller_id=$ResellerInfoRs["reseller_id"];

            $licenseRS=dbAbstract::ExecuteArray("SELECT `status` FROM licenses WHERE id=".$this->license_id);

            if($licenseRS) {
                if(strtolower($licenseRS['status'])!='activated') {
                    $this->status=0;
                }
            }
            else {
                $this->status=0;
            }

            $reseller_info_sql = "SELECT id, company_name,company_logo,company_logo_link,pdf_image_header,plain_text_header FROM users WHERE id = ". $reseller_id ;
            $this->reseller	= dbAbstract::ExecuteObject($reseller_info_sql, 0 ,'reseller');	
        } 
    }
    
    /*
     * count iframe setting
     */
    function CountIframeSettingsByRestaurantID($pRestaurantID) 
    {
        $mSQLQuery = "SELECT COUNT(*) AS SettingsCount FROM iframe_settings WHERE RestaurantID=".$pRestaurantID; 
        $mResult = dbAbstract::Execute($mSQLQuery);
        if (dbAbstract::returnRowsCount($mResult)>0) 
        {
            $mRow = dbAbstract::returnObject($mResult);
            return $mRow->SettingsCount;
        } 
        else 
        {
            return -1;
        }
    }
    
    function CountWordPressSettingsByRestaurantID($pRestaurantID) 
    {
        $mSQLQuery = "SELECT COUNT(*) AS SettingsCount FROM wp_restaurent_design_settings WHERE restaurant_id=".$pRestaurantID;
        $mResult = dbAbstract::Execute($mSQLQuery);
        if (dbAbstract::returnRowsCount($mResult)>0) 
        {
            $mRow = dbAbstract::returnObject($mResult);
            return $mRow->SettingsCount;
        } 
        else 
        {
            return -1;
        }
    }
    /**
     * 
     * @param type $pRestaurantID
     * @return int return iframe details settings
     */
    function getIframeDetailsByRestaurantID($pRestaurantID) {
            $mSQLQuery = "SELECT IFNULL(ShowLoyaltyBox, 0) AS ShowLoyaltyBox, IFNULL(LayoutStyle, 0) AS LayoutStyle, 
                         IFNULL(ShowPicturesDescription, 0) AS ShowPicturesDescription, IFNULL(CellBGImageStretchTile, 0) AS CellBGImageStretchTile, 
                         IFNULL(OrderOnlineButtonImage, '') AS OrderOnlineButtonImage, IFNULL(CellBGImage, '') AS CellBGImage, 
                         IFNULL(GeneralFontSize, '12') AS GeneralFontSize, IFNULL(GeneralTextColor, '000000') AS GeneralTextColor, 
                         IFNULL(SecondaryTextColor, '000000') AS SecondaryTextColor, IFNULL(MenuBGColor, 'F4F4F4') AS MenuBGColor, 
                         IFNULL(MenuLinkColorOnActive, 'CC0000') AS MenuLinkColorOnActive, IFNULL(MenuLinkColorOnInactive, '333333') AS MenuLinkColorOnInactive, 
                         IFNULL(SubMenuHeadingsColor, '585858') AS SubMenuHeadingsColor, IFNULL(SubMenuDescriptionsColor, '585858') AS SubMenuDescriptionsColor, 
                         IFNULL(ItemsTitleColor, '000000') AS ItemsTitleColor, IFNULL(ItemsPriceColor, '000000') AS ItemsPriceColor, 
                         IFNULL(ItemsDscriptionColor, '000000') AS ItemsDscriptionColor,
                         IFNULL(ItemsPricesFontSize, '14') AS ItemsPricesFontSize, IFNULL(YourOrderSummaryColor, '5F5F5F') AS YourOrderSummaryColor, 
                         IFNULL(YourOrderSummaryFontSize, '18') AS YourOrderSummaryFontSize, IFNULL(CellBGColor, 'fff') AS CellBGColor, 
                         IFNULL(CellBorderColor, 'e4e4e4') AS CellBorderColor, IFNULL(CellBorderThickness, '1') AS CellBorderThickness, 
                         IFNULL(TitlesFontSize, '12') AS TitlesFontSize, IFNULL(TitlesFont, 'Arial,Helvetica,sans-serif') AS TitlesFont, 
                         IFNULL(MinWidthOfTheContainer, '0') AS MinWidthOfTheContainer, IFNULL(ColorForVIPProgressBar, '00CCFF') AS ColorForVIPProgressBar 
                         FROM iframe_settings WHERE RestaurantID=".$pRestaurantID; 
            $mResult = dbAbstract::Execute($mSQLQuery);
            
            if (dbAbstract::returnRowsCount($mResult)>0) {
                return dbAbstract::returnObject($mResult);
            } else {
                return 0;
            }
    }
    
    function getWordPressDetailsByRestaurantID($pRestaurantID) {
            $mSQLQuery = "SELECT IFNULL(show_loyalty_box_about_the_cart, 1) AS show_loyalty_box_about_the_cart, IFNULL(appearence, 1) AS appearence, 
                         IFNULL(show_item_pictures_and_description, 1) AS show_item_pictures_and_description, IFNULL(cell_bg_image_strech_or_tile, 2) AS cell_bg_image_strech_or_tile, 
                         IFNULL(order_online_button_image, '') AS order_online_button_image, IFNULL(cell_bg_image, '') AS cell_bg_image, 
                         IFNULL(general_font_size, '12px') AS general_font_size, IFNULL(general_text_color, '#000000') AS general_text_color, 
                         IFNULL(secondary_text_color, '#000000') AS secondary_text_color, IFNULL(menu_bg_color, '#F4F4F4') AS menu_bg_color, 
                         IFNULL(active_menu_link_color, '#CC0000') AS active_menu_link_color, IFNULL(inactive_menu_link_color, '#333333') AS inactive_menu_link_color, 
                         IFNULL(sub_menu_headings_color, '#585858') AS sub_menu_headings_color, IFNULL(sub_menu_descriptions_color, '#585858') AS sub_menu_descriptions_color, 
                         IFNULL(items_title_color, '#000000') AS items_title_color, IFNULL(items_price_color, '#000000') AS items_price_color, 
                         IFNULL(items_description_color, '#000000') AS items_description_color,
                         IFNULL(items_and_prices_font_size, '14px') AS items_and_prices_font_size, IFNULL(your_order_summary_color, '5F5F5F') AS your_order_summary_color, 
                         IFNULL(your_order_summary_font_size, '18px') AS your_order_summary_font_size, IFNULL(cell_bg_color, '#ffffff') AS cell_bg_color, 
                         IFNULL(cell_border_color, '#F4F4F4') AS cell_border_color, IFNULL(cell_border_thickness, '1px') AS cell_border_thickness, 
                         IFNULL(titles_font_size, '16px') AS titles_font_size, IFNULL(titles_font_family, 'Arial,Helvetica,sans-serif') AS titles_font_family, 
                         IFNULL(min_width_of_the_container, '1000px') AS min_width_of_the_container, IFNULL(vip_progress_bar_color, '#00CCFF') AS vip_progress_bar_color 
                         FROM wp_restaurent_design_settings WHERE restaurant_id=".$pRestaurantID; 
            $mResult = dbAbstract::Execute($mSQLQuery);
            
            if (dbAbstract::returnRowsCount($mResult)>0) 
            {
                return dbAbstract::returnObject($mResult);
            } 
            else 
            {
                return 0;
            }
    }
	
    function getOOBICBIByRestaurantID($pRestaurantID) {
        $mSQLQuery = "SELECT IFNULL(OrderOnlineButtonImage, '') AS OrderOnlineButtonImage, IFNULL(CellBGImage, '') AS CellBGImage FROM iframe_settings WHERE RestaurantID=".$pRestaurantID; 

        $mResult = dbAbstract::Execute($mSQLQuery);
        if (dbAbstract::returnRowsCount($mResult)>0) {
            return dbAbstract::returnObject($mResult);
        } else {
            return 0;
        }
    }
    
    function getOOBICBIByRestaurantIDWP($pRestaurantID) {
        $mSQLQuery = "SELECT IFNULL(order_online_button_image, '') AS order_online_button_image, IFNULL(cell_bg_image, '') AS cell_bg_image FROM wp_restaurent_design_settings WHERE restaurant_id=".$pRestaurantID; 

        $mResult = dbAbstract::Execute($mSQLQuery);
        if (dbAbstract::returnRowsCount($mResult)>0) {
            return dbAbstract::returnObject($mResult);
        } else {
            return 0;
        }
    }
    
    function InsertIframeDetailsByRestaurantID($pRestaurantID, $pGeneralFontSize, $pGeneralTextColor, $pSecondaryTextColor, $pMenuBGColor, $pMenuLinkColorOnActive, $pMenuLinkColorOnInactive, $pSubMenuHeadingsColor, $pSubMenuDescriptionsColor, $pItemsTitleColor, $pItemsPriceColor, $pItemsDscriptionColor, $pItemsPricesFontSize, $pYourOrderSummaryColor, $pYourOrderSummaryFontSize, $pCellBGcolor, $pCellBorderColor, $pCellBorderThickness, $pTitlesFontSize, $pTitlesFont, $pMinWidthOfTheContainer, $pColorForVIPProgressBar, $pOrderOnlineButtonImage, $pCellBGImage, $pCellBGImageStretchTile, $pShowLoyaltyBox, $pLayoutStyle, $pShowPicturesDescription)
    {
        $mSQLQuery = "INSERT INTO iframe_settings (RestaurantID, GeneralFontSize, GeneralTextColor, SecondaryTextColor, 
                                  MenuBGColor, MenuLinkColorOnActive, MenuLinkColorOnInactive, SubMenuHeadingsColor, SubMenuDescriptionsColor, 
                                  ItemsTitleColor, ItemsPriceColor, ItemsDscriptionColor, ItemsPricesFontSize, YourOrderSummaryColor, 
                                  YourOrderSummaryFontSize, CellBGcolor, CellBorderColor, CellBorderThickness, TitlesFontSize, TitlesFont, 
                                  MinWidthOfTheContainer, ColorForVIPProgressBar, OrderOnlineButtonImage, CellBGImage, CellBGImageStretchTile, 
                                  ShowLoyaltyBox, LayoutStyle, ShowPicturesDescription)
                                  VALUES (".$pRestaurantID.", ".$pGeneralFontSize.", '".$pGeneralTextColor."', '".$pSecondaryTextColor."', 
                                  '".$pMenuBGColor."', '".$pMenuLinkColorOnActive."', '".$pMenuLinkColorOnInactive."', '".$pSubMenuHeadingsColor."', 
                                  '".$pSubMenuDescriptionsColor."', '".$pItemsTitleColor."', '".$pItemsPriceColor."', '".$pItemsDscriptionColor."', 
                                  ".$pItemsPricesFontSize.", '".$pYourOrderSummaryColor."', ".$pYourOrderSummaryFontSize.", '".$pCellBGcolor."', 
                                  '".$pCellBorderColor."', ".$pCellBorderThickness.", ".$pTitlesFontSize.", '".$pTitlesFont."',
                                  $pMinWidthOfTheContainer, '".$pColorForVIPProgressBar."', '".$pOrderOnlineButtonImage."', '".$pCellBGImage."', 
                                  ".$pCellBGImageStretchTile.", ".$pShowLoyaltyBox.", ".$pLayoutStyle.", ".$pShowPicturesDescription.")";
        return dbAbstract::Insert($mSQLQuery);
    }
    
    function UpdateIframeDetailsByRestaurantID($pRestaurantID, $pGeneralFontSize, $pGeneralTextColor, $pSecondaryTextColor, $pMenuBGColor, $pMenuLinkColorOnActive, $pMenuLinkColorOnInactive, $pSubMenuHeadingsColor, $pSubMenuDescriptionsColor, $pItemsTitleColor, $pItemsPriceColor, $pItemsDscriptionColor, $pItemsPricesFontSize, $pYourOrderSummaryColor, $pYourOrderSummaryFontSize, $pCellBGcolor, $pCellBorderColor, $pCellBorderThickness, $pTitlesFontSize, $pTitlesFont, $pMinWidthOfTheContainer, $pColorForVIPProgressBar, $pOrderOnlineButtonImage, $pCellBGImage, $pCellBGImageStretchTile, $pShowLoyaltyBox, $pLayoutStyle, $pShowPicturesDescription)
    {
        $mSQLQuery = "UPDATE iframe_settings SET GeneralFontSize=".$pGeneralFontSize.", GeneralTextColor='".$pGeneralTextColor."', 
                                                 SecondaryTextColor='".$pSecondaryTextColor."',MenuBGColor='".$pMenuBGColor."', 
                                                 MenuLinkColorOnActive='".$pMenuLinkColorOnActive."',MenuLinkColorOnInactive='".$pMenuLinkColorOnInactive."', 
                                                 SubMenuHeadingsColor='".$pSubMenuHeadingsColor."',SubMenuDescriptionsColor='".$pSubMenuDescriptionsColor."', 
                                                 ItemsTitleColor='".$pItemsTitleColor."',ItemsPriceColor='".$pItemsPriceColor."', 
                                                 ItemsDscriptionColor='".$pItemsDscriptionColor."',ItemsPricesFontSize=".$pItemsPricesFontSize.", 
                                                 YourOrderSummaryColor='".$pYourOrderSummaryColor."',YourOrderSummaryFontSize=".$pYourOrderSummaryFontSize.", 
                                                 CellBGcolor='".$pCellBGcolor."', CellBorderColor='".$pCellBorderColor."', 
                                                 CellBorderThickness=".$pCellBorderThickness.", TitlesFontSize=".$pTitlesFontSize.", 
                                                 TitlesFont='".$pTitlesFont."',  MinWidthOfTheContainer=".$pMinWidthOfTheContainer.",
                                                 ColorForVIPProgressBar='".$pColorForVIPProgressBar."', OrderOnlineButtonImage='".$pOrderOnlineButtonImage."', 
                                                 CellBGImage='".$pCellBGImage."', CellBGImageStretchTile=".$pCellBGImageStretchTile.", 
                                                 ShowLoyaltyBox=".$pShowLoyaltyBox.", LayoutStyle=".$pLayoutStyle.", 
                                                 ShowPicturesDescription=".$pShowPicturesDescription." WHERE RestaurantID=".$pRestaurantID;
        return dbAbstract::Update($mSQLQuery);
    }
    
    function InsertWordPressDetailsByRestaurantID($pRestaurantID, $pGeneralFontSize, $pGeneralTextColor, $pSecondaryTextColor, $pMenuBGColor, $pMenuLinkColorOnActive, $pMenuLinkColorOnInactive, $pSubMenuHeadingsColor, $pSubMenuDescriptionsColor, $pItemsTitleColor, $pItemsPriceColor, $pItemsDscriptionColor, $pItemsPricesFontSize, $pYourOrderSummaryColor, $pYourOrderSummaryFontSize, $pCellBGcolor, $pCellBorderColor, $pCellBorderThickness, $pTitlesFontSize, $pTitlesFont, $pMinWidthOfTheContainer, $pColorForVIPProgressBar, $pOrderOnlineButtonImage, $pCellBGImage, $pCellBGImageStretchTile, $pShowLoyaltyBox, $pLayoutStyle, $pShowPicturesDescription, $pRestaurantSlug, $pStatus, $pIframeHeight, $pIframeHeightInfinite)
    {
        $mSQLQuery = "INSERT INTO wp_restaurent_design_settings (restaurant_id,	general_font_size, general_text_color, secondary_text_color, 
                    menu_bg_color, active_menu_link_color, inactive_menu_link_color, sub_menu_headings_color, sub_menu_descriptions_color, 
                    items_title_color, items_price_color, items_description_color, items_and_prices_font_size, your_order_summary_color, 
                    your_order_summary_font_size, cell_bg_color, cell_border_color, cell_border_thickness, titles_font_size, titles_font_family, 
                    min_width_of_the_container, vip_progress_bar_color, order_online_button_image, cell_bg_image, cell_bg_image_strech_or_tile, 
                    show_loyalty_box_about_the_cart, appearence, show_item_pictures_and_description, restaurant_slug, status, iframe_height, iframe_height_infinite)
                    VALUES (".$pRestaurantID.", '".$pGeneralFontSize."', '".$pGeneralTextColor."', '".$pSecondaryTextColor."', 
                    '".$pMenuBGColor."', '".$pMenuLinkColorOnActive."', '".$pMenuLinkColorOnInactive."', '".$pSubMenuHeadingsColor."', 
                    '".$pSubMenuDescriptionsColor."', '".$pItemsTitleColor."', '".$pItemsPriceColor."', '".$pItemsDscriptionColor."', 
                    '".$pItemsPricesFontSize."', '".$pYourOrderSummaryColor."', '".$pYourOrderSummaryFontSize."', '".$pCellBGcolor."', 
                    '".$pCellBorderColor."', '".$pCellBorderThickness."', '".$pTitlesFontSize."', '".$pTitlesFont."', 
                    '".$pMinWidthOfTheContainer."', '".$pColorForVIPProgressBar."', '".$pOrderOnlineButtonImage."', '".$pCellBGImage."', 
                    ".$pCellBGImageStretchTile.", ".$pShowLoyaltyBox.", ".$pLayoutStyle.", ".$pShowPicturesDescription.", '".$pRestaurantSlug."', '".$pStatus."', '".$pIframeHeight."', '".$pIframeHeightInfinite."')";
        return dbAbstract::Insert($mSQLQuery);
    }
    
    function UpdateWordPressDetailsByRestaurantID($pRestaurantID, $pGeneralFontSize, $pGeneralTextColor, $pSecondaryTextColor, $pMenuBGColor, $pMenuLinkColorOnActive, $pMenuLinkColorOnInactive, $pSubMenuHeadingsColor, $pSubMenuDescriptionsColor, $pItemsTitleColor, $pItemsPriceColor, $pItemsDscriptionColor, $pItemsPricesFontSize, $pYourOrderSummaryColor, $pYourOrderSummaryFontSize, $pCellBGcolor, $pCellBorderColor, $pCellBorderThickness, $pTitlesFontSize, $pTitlesFont, $pMinWidthOfTheContainer, $pColorForVIPProgressBar, $pOrderOnlineButtonImage, $pCellBGImage, $pCellBGImageStretchTile, $pShowLoyaltyBox, $pLayoutStyle, $pShowPicturesDescription, $pRestaurantSlug, $pStatus, $pIframeHeight, $pIframeHeightInfinite)
    {
        $mSQLQuery = "UPDATE wp_restaurent_design_settings SET general_font_size='".$pGeneralFontSize."', general_text_color='".$pGeneralTextColor."', 
                    secondary_text_color='".$pSecondaryTextColor."',menu_bg_color='".$pMenuBGColor."', 
                    active_menu_link_color='".$pMenuLinkColorOnActive."',inactive_menu_link_color='".$pMenuLinkColorOnInactive."', 
                    sub_menu_headings_color='".$pSubMenuHeadingsColor."',sub_menu_descriptions_color='".$pSubMenuDescriptionsColor."', 
                    items_title_color='".$pItemsTitleColor."',items_price_color='".$pItemsPriceColor."', 
                    items_description_color='".$pItemsDscriptionColor."',items_and_prices_font_size='".$pItemsPricesFontSize."', 
                    your_order_summary_color='".$pYourOrderSummaryColor."',your_order_summary_font_size='".$pYourOrderSummaryFontSize."', 
                    cell_bg_color='".$pCellBGcolor."', cell_border_color='".$pCellBorderColor."', 
                    cell_border_thickness='".$pCellBorderThickness."', titles_font_size='".$pTitlesFontSize."', 
                    titles_font_family='".$pTitlesFont."',  min_width_of_the_container='".$pMinWidthOfTheContainer."',
                    vip_progress_bar_color='".$pColorForVIPProgressBar."', order_online_button_image='".$pOrderOnlineButtonImage."', 
                    cell_bg_image='".$pCellBGImage."', cell_bg_image_strech_or_tile=".$pCellBGImageStretchTile.", 
                    show_loyalty_box_about_the_cart=".$pShowLoyaltyBox.", appearence=".$pLayoutStyle.", 
                    show_item_pictures_and_description=".$pShowPicturesDescription.", restaurant_slug='".$pRestaurantSlug."', status='".$pStatus."', iframe_height='".$pIframeHeight."', iframe_height_infinite='".$pIframeHeightInfinite."'  WHERE restaurant_id=".$pRestaurantID;
        return dbAbstract::Update($mSQLQuery);
    }
	
    function UpdateOOBIByRestaurantID($pRestaurantID, $pOrderOnlineButtonImage) {
        $mSQLQuery = "UPDATE iframe_settings SET OrderOnlineButtonImage='".$pOrderOnlineButtonImage."' WHERE RestaurantID=".$pRestaurantID;
        return dbAbstract::Update($mSQLQuery);
    }

    function UpdateCBIByRestaurantID($pRestaurantID, $pCellBGImage) {
        $mSQLQuery = "UPDATE iframe_settings SET CellBGImage='".$pCellBGImage."' WHERE RestaurantID=".$pRestaurantID;
        return dbAbstract::Update($mSQLQuery);
    }
    
    function UpdateOOBIByRestaurantIDWP($pRestaurantID, $pOrderOnlineButtonImage) 
    {
        $mSQLQuery = "UPDATE wp_restaurent_design_settings SET order_online_button_image='".$pOrderOnlineButtonImage."' WHERE restaurant_id=".$pRestaurantID;
        return dbAbstract::Update($mSQLQuery);
    }

    function UpdateCBIByRestaurantIDWP($pRestaurantID, $pCellBGImage) 
    {
        $mSQLQuery = "UPDATE wp_restaurent_design_settings SET cell_bg_image='".$pCellBGImage."' WHERE restaurant_id=".$pRestaurantID;
        return dbAbstract::Update($mSQLQuery);
    }
    
}

class reseller 
{
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