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


	public static function real_IP()
	{
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return filter_var($ip, FILTER_VALIDATE_IP);
    }


	public static function email($email, $title, $text)
	{
		$template = email_template($title, $text);
		$enable_smtp = self::config('smtp');

		if (empty($enable_smtp)) {
			$headers = "From: admin@".$_SERVER['SERVER_NAME']."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html;charset=UTF-8\r\n";
			$headers .= "X-Mailer: PHP". phpversion() ."\r\n";

		    mail($email, $title, $template, $headers);
		} else {
			require_once($_SERVER['DOCUMENT_ROOT'].'/__API-SMTP__/ApiMail.php');

			$smtp = new ApiMail(...explode('#', $enable_smtp));
			$smtp->send_mail($email, $title, $template, $text);
			
			if (!$smtp->get_status())
				error(Language::config('error_send_email'));
		}

	}

}
?>