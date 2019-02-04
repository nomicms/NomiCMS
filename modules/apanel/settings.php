<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('settings');
$tmp->title('title', Language::config('settings'));
User::panel();

if(User::level() < 3){
	go_exit();
}

if(isset($_GET['smtp'])) {
	$a = Core::config('smtp');

	if (!empty($a)) {
		$ex = explode('#', $a);
		$email = $ex[0];
		$uid = $ex[1];
		$secret = $ex[2];
	}

	if (isset($_REQUEST['submit'])) {
		$email = $db->guard($_POST['email']);
		$uid = $db->guard($_POST['uid']);
		$secret = $db->guard($_POST['secret']);

		$smtp = (empty($email) || empty($uid) || empty($secret) ? NULL : implode('#', array($email, $uid, $secret)));

		$db->query("UPDATE `settings` SET `smtp` = '".$db->escape($smtp)."' WHERE `id`='1' ");
		$tmp->div('success', Language::config('ok_save'));
	}

	echo '<div class="main"># Settings <br>
# <a class="link_visual" target="_blank" href="https://login.sendpulse.com/settings/#api">https://login.sendpulse.com/settings/#api</a> </div><hr>
<div class="main">
<form method="POST" action="">
Email: <br/>
<input type="text" name="email" value = "'.$email.'" /><br/>
ID: <br/>
<input type="text" name="uid" value = "'.$uid.'" /><br/>
Secret: <br/>
<input type="text" name="secret" value = "'.$secret.'" /><br/>
<input type="submit" name="submit" value="'.Language::config('save').'" /></form></div>';

	$tmp->back('apanel/settings');
}


if (isset($_REQUEST['submit'])) {

	Security::verify_str();  

	$language = $db->guard($_POST['language']);
	$keywords = $db->guard($_POST['keywords']);
	$description = $db->guard($_POST['description']);
	$num = $db->guard($_POST['num']);
	$theme = $db->guard($_POST['theme']);
	$close = ($db->guard($_POST['open_site']) ? 1 : 0);
	$counters = $_POST['counters'];

	if (empty($num)) $error .= Language::config('error');

	if(!isset($error)){
		$db->query("UPDATE `settings` set `language` = '".$db->escape($language)."', `keywords` = '".$db->escape($keywords)."', `description` = '".$db->escape($description)."', `num` = '".$db->escape($num)."', `theme` = '".$db->escape($theme)."', `close` = '".$db->escape($close)."', `counters` = '".$db->escape($counters)."' WHERE `id`='1' ");
		$tmp->div('success', Language::config('ok_save'));
	}
}

error($error);

$a=$db->fass("select * from `settings` ");

echo '<div class="main"><form method="POST" action="">
'.Language::config('language').': <br/><select name="language" size="1">';

$lang_dir = opendir(S .'/lang');
	while ($lang = readdir($lang_dir)) {
		if ($lang == '.' || $lang == '..') 
			continue;
	$langs = parse_ini_file(S .'/lang/'.$lang.'/lang.ini');
	echo '<option value="'. $lang .'" '.($a['language'] == $lang ? 'selected="selected"':NULL).'>'. $langs['lang_name'] .'</option>'; //выбираем язык
}

echo '</select><br/>'.Language::config('num').':<br/>
<input type="number" name="num" value="'.out($a['num']).'" style="width: 50px" /><br/>
'.Language::config('theme').': <br/><select name="theme" size="1">';

$themes_dir = opendir(R .'/design/styles');
	while ($themes = readdir($themes_dir)) {
		if ($themes == '.' || $themes == '..') 
			continue;
	$thems = parse_ini_file(R .'/design/styles/'.$themes.'/config.ini');
	echo '<option value="'. $themes .'" '. ($a['theme'] == $themes ? 'selected="selected"':NULL) .'>'. $thems['name'] .'</option>'; //выбираем тему
}

echo '</select><br/>
<input id="open_s" type="checkbox" name="open_site" value="yes" '.($a['close'] ? 'checked' : NULL).'>
<label for="open_s">'.Language::config('acssec_site').'</label><br>
Keywords: <br/>
<input type="text" name="keywords" value = "'.$a['keywords'].'" /><br/>
Description: <br/>
<input type="text" name="description" value = "'.$a['description'].'" /><br/>

'.Language::config('counters').': <br/>
<textarea name="counters" style="width: 194px;height: 32px;min-height: 32px" />'.out($a['counters']).'</textarea><br />

<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('save').'" /></form></div>';

if (file_exists(R.'/__API-SMTP__'))
	$tmp->div('menu', '<hr><a href="?smtp">'.img('send_mail.png').' '.Language::config('settings_smtp').' </a>');

$tmp->back('apanel');
?>