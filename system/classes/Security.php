<?php 

abstract class Security {

    public static function code()
    {
        return str_shuffle(md5(rand()));
    }


    public static function email_code()
    {
        return substr(self::code(), 17);
    }


    public static function rand_str()
    {
    	$code = self::code();
    	$_SESSION['S_Code'] = $code;
    	return $code;
    }


    public static function verify_str()
    {
    	global $tmp;
    	if($_SESSION['S_Code'] != $_REQUEST['S_Code']){
    		error('Попытка подмены токена, повторите попытку отправки формы!');
    		$tmp->footer();
    	}
    }


    public static function make_email($mod, $code, $us, $text, $tema, $email)
    {
        global $db;
        $db->query("INSERT INTO `users_emails` SET `text` = '".$db->escape($text)."', `code` = '".$db->escape($code)."', `us` = '".$db->escape($us)."', `valid` = '1', `time` = '".time()."', `time_end` = '".(time()+3600)."', `module` = '".$mod."'");
        Core::email($email, $tema, $text);
        return null;
    }

}