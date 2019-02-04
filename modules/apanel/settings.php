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

if (isset($_REQUEST['submit'])) {

	Security::verify_str();  

	$language = $db->guard($_POST['language']);
	$keywords = $db->guard($_POST['keywords']);
	$description = $db->guard($_POST['description']);
	$num = $db->guard($_POST['num']);
	$theme = $db->guard($_POST['theme']);
	$close = ($db->guard($_POST['open_site']) ? 1 : 0);

	if (empty($num)) $error .= Language::config('error');

	if(!isset($error)){
		$db->query("UPDATE `settings` set `language` = '".$db->escape($language)."', `keywords` = '".$db->escape($keywords)."', `description` = '".$db->escape($description)."', `num` = '".$db->escape($num)."', `theme` = '".$db->escape($theme)."', `close` = '".$db->escape($close)."' WHERE `id`='1' ");
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
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">

<input type="submit" name="submit" value="'.Language::config('save').'" /></form></div>';

$tmp->div('menu', '<hr><a href="/apanel">'.img('link.png').' '.Language::config('back').'</a>');
$tmp->footer();
?>