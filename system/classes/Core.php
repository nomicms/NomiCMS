<?php
Class Core {
	private static $config = array();
	
	public static function config($var)
	{
		global $db;
		if (empty(self::$config)) {
			self::$config = $db->fass("SELECT * FROM `settings`");
			return self::$config[$var];
		} else {
			return self::$config[$var];
		}
	}


	public static function real_IP(){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return filter_var($ip,FILTER_VALIDATE_IP);
    }

}
?>