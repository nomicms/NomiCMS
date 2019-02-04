<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('add_ban');
$tmp->title('title', Language::config('add_ban'));

$id = my_int($_GET['id']);
User::panel();

if (User::level()>=2) {
	$prov=$db->fass("SELECT * FROM `users` where `id` = '".$id."'");

	if (!$prov) {
		$tmp->show_error();
	} else {

		if (isset($_REQUEST['submit'])) {

	      Security::verify_str();  

			$message = $db->guard($_POST['message']);
			$time_end = $db->guard($_POST['time_end']);

			if (empty($message)) $error .= Language::config('no_empty').'!<br/>';
			if (empty($time_end)) $error .= Language::config('no_empty').'!<br/>';

			if (!isset($error)) {
				$db->query("insert into `ban` set `kto` = '".User::ID()."', `komy` = '".$id."', `time` = '".time()."',`time_end` = '".(time()  + ($time_end * (60 * 60)))."', `message` = '".$message."' ");
				header('Location: /us'.$id);
			}
		}

		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('prich').': <br/>
<input type="text" name="message" /><br/>'.Language::config('strok').':<br/>
<select name="time_end">
<option value="0.09">5 '.Language::config('minut').'</option>
<option value="1">1 '.Language::config('hour').'</option>
<option value="24">1 '.Language::config('sytki').'</option>
<option value="72">3 '.Language::config('day').'</option>
<option value="168">'.Language::config('ned').'</option>
<option value="720">'.Language::config('mes').'</option>
<option value="8640">'.Language::config('god').'</option>
</select><br/>

<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('add_ban').'" /></form>'); 

	}
} else {
	go_exit();
}

$tmp->back('us'.$id);
?>