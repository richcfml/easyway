<?php 
class menu  
{
    public $restaurant_id;
    public $id;
    public $arr_hours_list;

    public $openTime,$closeTime;
    public $submenu_openTime,$submenu_closeTime;
	
    public function getmenu($activeMenuOnly=0) 
    {
        $qry="SELECT * FROM menus WHERE rest_id = ".$this->restaurant_id;
        if($activeMenuOnly==1)
        {
            $qry .=" AND `status` = '1' ";
        }
        $qry = $qry ." ORDER BY menu_ordering ";
        $menu_qry = dbAbstract::Execute($qry);
        
        $arr_menu_list = array();
        while($menu = dbAbstract::returnObject($menu_qry, 0 , "menu")) 
        {
            if($activeMenuOnly==1) 
            {
                $menu->getBusinessHours();
            }
            $arr_menu_list[] = $menu;
        }
        
        return $arr_menu_list;		
    }
    
    public function getBusinessHours ()  
    {	
        $qry="SELECT * FROM menu_hours WHERE menu_id=".$this->id." ORDER BY day ASC";
        $menu_hours = dbAbstract::Execute($qry);

        $this->arr_hours_list = array();

        while($menu_hour = dbAbstract::returnObject($menu_hours)) 
        {
            $this->arr_hours_list[$menu_hour->day] = $menu_hour;
        }        
		
    }
    
    public function isAvailable() 
    {
        $OpenHour=1;
        $day_name=date('l');
        if($day_name == 'Monday') 
        {
            $day_of_week = 0;
        } 
        else if($day_name == 'Tuesday') 
        {
            $day_of_week = 1; 
        } 
        else if($day_name == 'Wednesday') 
        {
            $day_of_week = 2; 
        } 
        else if($day_name == 'Thursday') 
        {
            $day_of_week = 3;
        } 
        else if($day_name == 'Friday') 
        {
            $day_of_week = 4; 
        } 
        else if($day_name == 'Saturday') 
        {
            $day_of_week = 5;
        } 
        else if($day_name == 'Sunday') 
        {
            $day_of_week = 6;
        }
		 
        if(isset($this->arr_hours_list[$day_of_week])) 
        {
            $OpenHour=0;
            $menu_hours=$this->arr_hours_list[$day_of_week];

            $current_time=date("Hi",time());

            if($current_time >= $menu_hours->open && $current_time <= $menu_hours->close) 
            {
                $OpenHour=1;
            }
            $this->openTime=date("g:i A",strtotime($menu_hours->open));
            $this->closeTime=date("g:i A",strtotime($menu_hours->close));
        }
       return $OpenHour;			
    }
	 	
    public function submenu_isAvailable($subcat_id) 
    {
        $submenu_OpenHour=1;

        $day_name=date('l');

        if($day_name == 'Monday') 
        {
            $day_of_week = 0;
        } 
        else if($day_name == 'Tuesday') 
        {
            $day_of_week = 1; 
        } 
        else if($day_name == 'Wednesday') 
        {
            $day_of_week = 2; 
        } 
        else if($day_name == 'Thursday') 
        {
            $day_of_week = 3;
        } 
        else if($day_name == 'Friday') 
        {
            $day_of_week = 4; 
        } 
        else if($day_name == 'Saturday') 
        {
            $day_of_week = 5;
        } 
        else if($day_name == 'Sunday') 
        {
            $day_of_week = 6;
        } 

        $qry = "SELECT open, close FROM sub_menu_hours WHERE sub_menu_id=".$subcat_id." AND day=".$day_of_week." ORDER BY day ASC";

        $_arr_hours_list = dbAbstract::ExecuteArray($qry);

        $submenu_opentime= $_arr_hours_list['open'];
        $submenu_closetime=$_arr_hours_list['close'];


        if(dbAbstract::returnRowsCount($sub_menu_hours) > 0) 
        {
            $submenu_OpenHour=0;
            $current_time=date("Hi",time());

            if($current_time >= $submenu_opentime && $current_time <= $submenu_closetime) 
            {
                $submenu_OpenHour=1;
            }    
            $this->submenu_openTime=date("g:i A",strtotime($submenu_opentime));
            $this->submenu_closeTime=date("g:i A",strtotime($submenu_closetime));
        }
        return $submenu_OpenHour;			
    }    
}

?>