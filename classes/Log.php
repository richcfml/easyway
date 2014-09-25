<?php
class Log {

    public static function write($message_name, $message, $log_name , $c_panel = 0 , $menu = '') {
        
        if($menu != ''){
            @define('LOGFILEDIR', dirname(dirname(__FILE__)) . '/logs/' . $log_name . "/".$menu);
        } else {
            @define('LOGFILEDIR', dirname(dirname(__FILE__)) . '/logs/' . $log_name);
        }
        if (!file_exists(LOGFILEDIR)) {
            mkdir(LOGFILEDIR, 0777, true);
        }
        define('LOGFILENAME',LOGFILEDIR."/". @date('d-m-y') . '-log-file.log');
        $handle = fopen(LOGFILENAME, 'a');
        if (!$handle){
            $handle = fopen(LOGFILENAME, 'w');
        }

        if($c_panel == 0 && class_exists('users')){
            $user = users::loggedinUserEmail();
        } else {
            if(isset($_SESSION['admin_session_pass']) && isset($_SESSION['admin_session_user_name'])){
                $user = $_SESSION['admin_session_user_name'];
            } else {
                $user = "";
            }
        }
        
        $ip = '[' . $_SERVER['REMOTE_ADDR'] . ']';
        $browser = '[' . $_SERVER['HTTP_USER_AGENT'] . ']';
        $time = '[' . @date('d-m-Y h:i:s a', @time()) . ']';
        $user = '['.$user.']';
        
        $content = '';
        $content .= "\n-------------------------------------------------------------------------------------------------------------------------------";
        $content .= "\nTime:" . $time;
        $content .= " - IP:" . $ip;
        $content .= " - User:" . $user;
        $content .= " - Browser info: " . $browser;
        $content .= "\n-------------------------------------------------------------------------------------------------------------------------------";
        $content .= "\n\n" . $message_name;
        $content .= "\n\n" . $message;

        fwrite($handle, $content);
        fclose($handle);
    }

}