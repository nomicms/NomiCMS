<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');
#
require_once(R.'/system/kernel.php');

$tmp->header('friends');
$tmp->title('title', Language::config('friends'));
User::panel();

if(User::aut()) {

	if(isset($_GET['id'])){
		$id = my_int($_GET['id']);
	}

	if (User::ID() == $id) {
		go_exit();
	}

	$p = $db->fass("SELECT * FROM `users` WHERE `id` = '".$id."' ");
	if (!$p) $error .= Language::config('error');
	
	$pr = $db->fass_c("SELECT COUNT(*) as count FROM `friends` WHERE `kto` = '".User::ID()."' and `komy` = '".$id."'");
	$pr2 = $db->fass_c("SELECT COUNT(*) as count FROM `friends` WHERE `kto` = '".$id."' and `komy` = '".User::ID()."'");
	
	if ($pr2 != 0) {
		go_exit('/friends/bid');
	}
	
	if ($pr != 0) $error .= Language::config('error');

	if(!isset($error)){
		$db->query("INSERT INTO `friends` set `kto` = '".User::ID()."', `komy` = '".$id."', `status` = '0' ");
		go_exit('/friends/bid');
	}

	error($error);

} else {
	go_exit();
}

$tmp->footer();
?>