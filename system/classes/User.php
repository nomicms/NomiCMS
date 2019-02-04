<?php
Class User {
	
	private static $id = false;
	private static $settings = array();
	private static $profile = array();

	public static function aut()
	{
		global $db;

		if (!empty(self::$id)) return true;

		if (isset($_SESSION['login']) && isset($_SESSION['password'])) {
			$us_l = $_SESSION['login'];
			$us_p = $_SESSION['password'];
		} elseif (isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
			$us_l = $_COOKIE['login'];
			$us_p = $_COOKIE['password'];
		}

		if (isset($us_l) && isset($us_p)) {
			$idi = $db->fass("SELECT `id` FROM `users` where `login` = '".$db->guard($us_l)."' and `password` = '".$db->guard($us_p)."' LIMIT 1");

			if (!empty($idi)) {
				self::$id = $idi['id'];
				return true;
			} else {
				return false;
			}
			
		} else {
			return false;
		}
		
	}
	
	public static function ID()
	{
		return self::$id;
	}
	
	public static function settings($var, $uid=null)
	{
		global $db;
		if (self::aut()) {

			if (!empty($uid)) {
				return $db->fass("SELECT ".$var." FROM `user_settings` where `kto` = '".$uid."' LIMIT 1")[$var];
			}

			if (empty(self::$settings)) {
				self::$settings = $db->fass("SELECT * FROM `user_settings` where `kto` = '".self::ID()."' LIMIT 1");
	 	        return self::$settings[$var];
	 	    } else {
	 	    	return self::$settings[$var];
	 	    }

		} else {
			return Core::config($var);
		}
	}
	
	public static function profile($var)
	{
		global $db;
		if (self::aut()) {
			if (empty(self::$profile)) {
				self::$profile = $db->fass("SELECT * FROM `users` where `id` = '".self::ID()."' LIMIT 1");
				return self::$profile[$var];
			} else {
				return self::$profile[$var];
			}
		}	
	}
	
	public static function level()
	{
		return self::profile('level');
	}

	public static function banned($uid, $only_check=false)
	{
		global $db;
		$ban=$db->fass("select * from `ban` where `komy` = '".$uid."' and `time_end` > '".time()."' LIMIT 1");	
		if ($only_check) return $ban;
		if ($ban) {
			Tmp::div('error', Language::config('user_baned'));
			Tmp::div('main', Language::config('kto_add_ban').': '.nick_new($ban['kto']).'<br>'.Language::config('prich').': '.$ban['message'].'<br> '.Language::config('osvob').': '.times($ban['time_end']));
		}
	}

	public static function new_notify($komy, $mess, $url)
	{
		global $db;
		$db->query("INSERT INTO `journal` set `kto` = '".self::ID()."', `komy` = '".$komy."', `message` = '".$mess."', `url`= '".$url."', `time` = '".time()."', `readln` = '0' ");
	}
	
	public static function panel()
	{
		global $db;
		if(self::aut()) {
			$mes = $db->fass_c("SELECT COUNT(*) as count FROM `dialogs_message` WHERE `komy` = '".self::ID()."' and `readln` = '0'");
			$journal = $db->fass_c("SELECT COUNT(*) as count FROM `journal` WHERE `komy` = '".self::ID()."' and `readln` = '0'");
			$friends = $db->fass_c("SELECT COUNT(*) as count FROM `friends` WHERE `komy` = '".self::ID()."' and `status` = '0'");

			echo '<div class="panel flex">';
			if (self::level() >= 2) echo '<a class="apanel" href="/apanel">'.img('admin.png').'</a>';
			
			echo '<a href="/panel">'.img('panel.png').($journal || $friends ? NULL : ' &nbsp;'.Language::config('panel')).'</a>';
			echo '<a href="/dialogs">'.img('mail.png').($journal || $friends ? NULL : ' &nbsp;'.Language::config('dialogs')).' '.($mes > 0 ? '<span>+'.$mes.'</span>' : NULL).'</a>';

			if ($journal) echo '<a href="/journal">'.img('notify.png').'<span>+'.$journal.'</span></a>';
			if ($friends) echo '<a href="/friends/bid">'.img('friends.png').'<span>+'.$friends.'</span></a>';

			echo '</div>';
		} else {
			echo '<div class="panel flex"><a href="/login"><img src="/design/styles/default/img/login.png"> &nbsp;'.Language::config('aut').'</a><a href="/reg"><img src="/design/styles/default/img/reg.png"> &nbsp;'.Language::config('reg').'</a></div>';

			if($_SERVER['REQUEST_URI'] !== '/'){
				if(Core::config('close') == 0){
					go_exit();
				}
			}
		}
	}
	

}
?>