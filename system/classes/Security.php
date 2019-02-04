<?php 

abstract class Security {

    public static function rand_str()
    {
    	$code = md5(time().'NomiCMS');
    	$_SESSION['S_Code'] = $code;
    	return $code;
    }


    public static function verify_str()
    {
    	global $tmp;
    	if($_SESSION['S_Code'] != $_REQUEST['S_Code']){
    		error('Попытка подмены токена, повторите попытку отправки формы!');
    		$tmp->footer();
    		exit;
    	}
    } 

}