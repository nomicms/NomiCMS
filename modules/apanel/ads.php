<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('ads');
$tmp->title('title', Language::config('ads'));
User::panel();

if(User::level()>=2){

if (isset($_REQUEST['submit'])) {
	$name = $db->guard($_POST['name']);
	$link = $db->guard($_POST['link']);
	$local = $db->guard($_POST['local']);
	$time_end = $db->guard($_POST['time_end']);

	Security::verify_str();  

    if(empty($name)) $error .= Language::config('no_empty').'!<br/>';
	if(empty($link)) $error .= Language::config('no_empty').'!<br/>';
	if(empty($time_end)) $error .= Language::config('no_empty').'!<br/>';

	if(!isset($error)) {
	    $db->query("insert into `ads` set `kto` = '".User::ID()."',  `name` = '".$name."',  `link` = '".$link."', `local` = '".$local."', `time` = '".time()."',`time_end` = '".(time()  + ($time_end * (60 * 60*24)))."'");
	    header('Location: ./ads_list');
	}
}

$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').': <br/>
<input type="text" name="name"  /><br/>'.
Language::config('link').' http:// <br/>
<input type="text" name="link"  /><br/>'.
Language::config('strok').' (дней)<br/>
<input type="number" name="time_end" style="width: 40px" /><br/>'.
Language::config('local').'<br/>
<select name="local"><option value="1">'.Language::config('head').'</option><option value="0">'.Language::config('foot').'</option></select><br/>

<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
} else {
	go_exit();
}

$tmp->back('apanel/ads_list');
?>