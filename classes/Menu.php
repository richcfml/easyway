<?php
/**
 * This class contains function to fetch menu details and menu hours.
 *
 * 
 */
class Menu
{
    public $restaurant_id;
    public $id;
    public $arr_hours_list;
    public $openTime, $closeTime;
    
    /**
     * Get the all enable menus of specific restaurant
     * @return type array list of menus
     */
    public function getMenusByRestaurantId() {
        $qry = "SELECT * FROM menus WHERE rest_id = " . $this->restaurant_id . " AND `status` = '1' ORDER BY menu_ordering";
        $menu_qry = dbAbstract::Execute($qry);
        
        $arr_menu_list = array();
        while ($menu = dbAbstract::returnObject($menu_qry, 0, "menu")) {
            $menu->getMenuHoursByMenuId();
            $arr_menu_list[] = $menu;
        }
        return $arr_menu_list;
    }
    
    /**
     * get menu hours and Set menus buisness hours in arr_hours_list
     */
    public function getMenuHoursByMenuId() {
        $qry = "SELECT * FROM menu_hours WHERE menu_id=" . $this->id . " ORDER BY day ASC";
        $menu_hours = dbAbstract::Execute($qry);
        
        $this->arr_hours_list = array();
        
        while ($menu_hour = dbAbstract::returnObject($menu_hours)) {
            $this->arr_hours_list[$menu_hour->day] = $menu_hour;
        }
    }
    
    /**
     * 
     * @return int 1 if menu hours is open else return 0
     */
    public function isMenuOpen() {
        $OpenHour    = 1;
        $day_of_week = $this->getDayOfWeek();
        
        if (isset($this->arr_hours_list[$day_of_week])) {
            $OpenHour   = 0;
            $menu_hours = $this->arr_hours_list[$day_of_week];
            
            $current_time = date("Hi", time());
            
            if ($current_time >= $menu_hours->open && $current_time <= $menu_hours->close) {
                $OpenHour = 1;
            }
            $this->openTime  = date("g:i A", strtotime($menu_hours->open));
            $this->closeTime = date("g:i A", strtotime($menu_hours->close));
        }
        return $OpenHour;
    }
    
    public function getDayOfWeek() {
        $day_name = date('l');
        if ($day_name == 'Monday') {
            $day_of_week = 0;
        } else if ($day_name == 'Tuesday') {
            $day_of_week = 1;
        } else if ($day_name == 'Wednesday') {
            $day_of_week = 2;
        } else if ($day_name == 'Thursday') {
            $day_of_week = 3;
        } else if ($day_name == 'Friday') {
            $day_of_week = 4;
        } else if ($day_name == 'Saturday') {
            $day_of_week = 5;
        } else if ($day_name == 'Sunday') {
            $day_of_week = 6;
        }
        return $day_of_week;
    }
    
    /*	End of class Menu	*/
}

?>