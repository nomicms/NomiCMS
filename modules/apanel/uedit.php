<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('edit');
$tmp->title('title', Language::config('edit_profile'));
User::panel();

if(User::level() >= 3){

	$id=my_int($db->guard($_GET['id']));
	if (!$id) go_exit();

	if (isset($_REQUEST['submit'])) {

        Security::verify_str();

        $name = $db->guard($_POST['name']);
		$fname = $db->guard($_POST['fname']);
		$strana = $db->guard($_POST['strana']);
		$gorod = $db->guard($_POST['gorod']);
		$osebe = $db->guard($_POST['osebe']);
		$tg = $db->guard($_POST['tg']);
		$sex = $db->guard($_POST['sex']);

		$a=$db->fass("select * from `users` where `id` = '".$id."'");
		$acssec = (($a['level'] > User::level() || User::level() == 3 && $db->guard($_POST['acssec']) > 2) ? $a['level'] : $db->guard($_POST['acssec']));

		$language = $db->guard($_POST['language']);

		$money = $db->guard($_POST['money']);
		$datareg = $db->guard($_POST['datareg']);
		$datalast = $db->guard($_POST['datalast']);
		$ava = $db->guard($_POST['ava']);

		if (!isset($error)) {
			$db->query("UPDATE `users` set `level` = '".$db->escape($acssec)."', `name` = '".$db->escape($name)."', `first_name` = '".$db->escape($fname)."', `sex` = '".$db->escape($sex)."', `country` = '".$db->escape($strana)."', `city` = '".$db->escape($gorod)."', `about` = '".$db->escape($osebe)."', `tg` = '".$db->escape($tg)."', `money` = '".$db->escape($money)."', `date_registration` = '".$db->escape($datareg)."', `date_last_entry` = '".$db->escape($datalast)."', `ava` = '".$db->escape($ava)."' WHERE `id`='".$id."' ");
			$db->query("UPDATE `user_settings` set  `language` = '".$language."' WHERE `id`='".$id."' ");
			$tmp->div('success', Language::config('ok_save'));
		}
	}

	$a=$db->fass("select * from `users` where `id` = '".$id."'");
	$as=$db->fass("select * from `user_settings` where `id` = '".$id."'");

	if(empty($a)) go_exit();

	error($error);

	echo '<form method="POST" action=""><div class="main">
'.Language::config('acssec').': <br/>
<select name="acssec">
<option value="1" '.(out($a['level']) == 1 ? 'selected="selected"':NULL).'>'.Language::config('user').'</option>
<option value="2" '.(out($a['level']) == 2 ? 'selected="selected"':NULL).'>'.Language::config('moderator').'</option>
<option value="3" '.(out($a['level']) == 3 ? 'selected="selected"':NULL).' '.(User::level() != 4 ? 'disabled' : NULL).'>'.Language::config('administrator').'</option>
<option value="4" '.(out($a['level']) == 4 ? 'selected="selected"':NULL).' '.(User::level() != 4 ? 'disabled' : NULL).'>'.Language::config('developer').'</option>
</select></div><hr><div class="main">
'.Language::config('name').': <br/>
<input type="text" name="name" value="'. out($a['name']) .'" /><br/>
'.Language::config('fname').'<br/>
<input type="text" name="fname" value="'. out($a['first_name']) .'"/><br/>
'.Language::config('country').':<br/>
<input type="text" name="strana" value="'. out($a['country']) .'"/><br/>
'.Language::config('city').':<br/>
<input type="text" name="gorod" value="'. out($a['city']) .'"/><br/>
'.Language::config('about').':<br/>
<input type="text" name="osebe" value="'. out($a['about']) .'"/><br/>
'.Language::config('sex').': <br/>
<select name="sex">
<option value="1" '.(out($a['sex']) == 1 ? 'selected="selected"':NULL).'>'.Language::config('men').'</option>
<option value="0" '.(out($a['sex']) == 0 ? 'selected="selected"':NULL).'>'.Language::config('wom').'</option>
</select><br/>
'.Language::config('tg').':<br/>
<input type="text" name="tg" value="'. out($a['tg']) .'"/><br/>
<div class="cit">
Money:<br/>
<input type="text" name="money" value="'.out($a['money']).'"/><br/>
Date Registration (t):<br/>
<input type="text" name="datareg" value="'.out($a['date_registration']).'"/><br/>
Date Last Entry (t):<br/>
<input type="text" name="datalast" value="'.out($a['date_last_entry']).'"/><br/>
Ava:<br/>
<input type="text" name="ava" value="'.out($a['ava']).'"/><br/>

</div>
'.Language::config('language').':<br/>
<select name="language" size="1">';

	$lang_dir = opendir(S .'/lang');
		while ($lang = readdir($lang_dir)) {
			if ($lang == '.' || $lang == '..') 
				continue;
	$langs = parse_ini_file(S .'/lang/'.$lang.'/lang.ini');
	echo '<option value="'. $lang .'" '.($as['language'] == $lang ? 'selected="selected"':NULL).'>'. $langs['lang_name'] .'</option>';
	}
	echo '</select><br/>
	<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
	<input type="submit" name="submit" value="'.Language::config('save').'" /></div>
	</form>';

} else {
	go_exit();
}

$tmp->back('us'.$id);
?>