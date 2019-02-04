<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('my_settings');
$tmp->title('title', Language::config('my_settings'));

if(!User::aut()){
	go_exit();
}

User::panel();

if (isset($_REQUEST['submit'])) {
	Security::verify_str();

	$num = my_int($db->guard($_POST['num']));
	$language = $db->guard($_POST['language']);
	$theme = $db->guard($_POST['theme']);
	$pass = $db->guard($_POST['pass']);
	$pass2 = $db->guard($_POST['pass2']);

	if (!empty($pass) && !empty($pass2)) {
		if(empty($pass) || empty($pass2)) $error .= Language::config('no_empry_pass');
		if(!empty($pass) && (strlen($pass) < 5 || strlen($pass) > 32)) $error .= Language::config('strlen_reg_pass').". ". Language::config('dop_strlen_reg_pass')."<br/>";
		if(!empty($pass2) && $pass != $pass2) $error .= Language::config('no_pass_pass2')."<br/>";
	}

	if (!isset($error)) {
		if (!empty($pass) && !empty($pass2)) {
			$pass = encode($pass);
			$db->query("update `users` set `password` = '".$db->escape($pass)."' where `id` = '".$db->escape(User::ID())."' ");
			$db->query("UPDATE `user_settings` SET `num` = '".$db->escape($num)."', `language` = '".$language."', `theme` = '".$theme."' WHERE `kto`='".$db->escape(User::ID())."' ");

			$_SESSION['password'] = $pass;
			setcookie('password', $pass, time()+60*60*24*1024, '/');
			$tmp->div('success', Language::config('ok_save'));
		} else {
			$db->query("UPDATE `user_settings` SET `num` = '".$db->escape($num)."', `language` = '".$language."', `theme` = '".$theme."' WHERE `kto`='".$db->escape(User::ID())."' ");
			$tmp->div('success', Language::config('ok_save'));
			//header('location: /settings');
		}
	}
}

error($error);

$a=$db->fass("select * from `user_settings` where `kto` = '".User::ID()."' ");

echo '<form method="POST" action=""><div class="main">'.Language::config('language').': <br/>
<select name="language" size="1">';

$lang_dir = opendir(S .'/lang');
	while ($lang = readdir($lang_dir)) {
		if ($lang == '.' || $lang == '..') 
			continue;
		$langs = parse_ini_file(S .'/lang/'.$lang.'/lang.ini');
		echo '<option value="'. $lang .'" '. ($a['language'] == $lang ? 'selected="selected"':NULL) .'>'. $langs['lang_name'] .'</option>'; //выбираем язык
	}

echo '</select><br/>'.Language::config('num').': <br/>
<input type="number" name="num" value="'. out($a['num']) .'" style="width: 50px" /><br/>
'.Language::config('theme').': <br/><select name="theme" size="1">';

$themes_dir = opendir(R .'/design/styles');
	while ($themes = readdir($themes_dir)) {
		if ($themes == '.' || $themes == '..') 
			continue;
		$thems = parse_ini_file(R .'/design/styles/'.$themes.'/config.ini');

		echo '<option value="'. $themes .'" '.($a['theme'] == $themes ? 'selected="selected"' : NULL).'>'.$thems['name'].' '.($thems['autor'] ? ' by ('.$thems['autor'].')' : NULL).'</option>';
	}

echo '</select></div><hr><div class="main">
<span style="color: #F44336">'.Language::config('edit_pass').', '.Language::config('edit_pass2').'!</span><br/>
'.Language::config('new_pass').': <br/>
<input type="text" name="pass" /><br/>
'.Language::config('pass2').': <br/>
<input type="text" name="pass2" /><br/>
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('save').'" /></div></form>';

$tmp->back('panel');
?>