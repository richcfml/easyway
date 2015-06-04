<? 
class menu  {
	public $restaurant_id;
	public $id;
	public $arr_hours_list;
	 
	public $openTime,$closeTime;
        public $submenu_openTime,$submenu_closeTime;
	
	public function getmenu($activeMenuOnly=0) {
		$qry="select * from menus where rest_id = ".$this->restaurant_id."";
		 if($activeMenuOnly==1)
		 $qry .=" AND status = '1' ";
		$menu_qry = mysql_query( $qry ."   order by menu_ordering ");
		 
		$arr_menu_list = array();
		while($menu = mysql_fetch_object($menu_qry, 'menu') ) {
				if($activeMenuOnly==1) {
					$menu->getBusinessHours();
				}
			$arr_menu_list[] = $menu;
		
		}
		return $arr_menu_list;		
	}
	public function getBusinessHours ()  {
		$qry="select * from menu_hours where menu_id=".$this->id." order by day asc ";
		$menu_hours = mysql_query($qry);
	 
		$this->arr_hours_list = array();
		while($menu_hour = mysql_fetch_object($menu_hours) ) {
			$this->arr_hours_list[$menu_hour->day] = $menu_hour;
		}
		
	 }
	 	public function isAvailable() {
		 	$OpenHour=1;
			//echo $this->id;
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
		 
		 if(isset($this->arr_hours_list[$day_of_week])) {
			 $OpenHour=0;
			 $menu_hours=$this->arr_hours_list[$day_of_week];
			 
			 $current_time=date("Hi",time());
			 //print_r($menu_hours);
			 //echo " >> ";
			 //echo $current_time;
			 //echo " >> ";
			 //var_dump($current_time >= $menu_hours->open);
			 //echo " >> ";
			 //var_dump($current_time <= $menu_hours->close);
			 //echo " >> ";
			 if($current_time >= $menu_hours->open && $current_time <= $menu_hours->close) {
			 
			 	$OpenHour=1;
			}
				$this->openTime=date("g:i A",strtotime($menu_hours->open));
				$this->closeTime=date("g:i A",strtotime($menu_hours->close));
		 
		 }
	 
		return $OpenHour;			
	 }

         public function submenu_isAvailable($subcat_id) {
		 	$submenu_OpenHour=1;
			
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
                           
                           
                        $qry="select open,close from sub_menu_hours where sub_menu_id=".$subcat_id." AND day=".$day_of_week." order by day asc ";
                        $sub_menu_hours = mysql_query($qry);

                        $_arr_hours_list = mysql_fetch_array($sub_menu_hours);

                        $submenu_opentime= $_arr_hours_list['open'];
                        $submenu_closetime=$_arr_hours_list['close'];
   
                           
		 if(mysql_num_rows($sub_menu_hours) > 0) {
      
			 $submenu_OpenHour=0;
			 $current_time=date("Hi",time());
		
			 if($current_time >= $submenu_opentime && $current_time <= $submenu_closetime) {
			 
			 	$submenu_OpenHour=1;
			}
				$this->submenu_openTime=date("g:i A",strtotime($submenu_opentime));
				$this->submenu_closeTime=date("g:i A",strtotime($submenu_closetime));
		 
		 }
	 
		return $submenu_OpenHour;			
	 }
	
}

?>