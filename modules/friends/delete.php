<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('friends');
$tmp->title('title', Language::config('friends'));
User::panel();

if(User::aut()){

	if(isset($_GET['id'])) $id = my_int($_GET['id']);

	if (User::ID() == $id) go_exit();

	$pr=$db->fass("SELECT * FROM `friends` where `kto` = '".User::ID()."' and `komy` = '".$id."' or `kto` = '".$id."' and `komy` = '".User::ID()."'");

	if (!$pr) {
		$tmp->show_error();
	} else {
		if ($pr['status'])
			User::new_notify($id, 'friend_del', '/us'.User::ID());

		$db->query("DELETE FROM `friends` where `kto` = '".User::ID()."' and `komy` = '".$id."' LIMIT 1");
		$db->query("DELETE FROM `friends` where `kto` = '".$id."' and `komy` = '".User::ID()."' LIMIT 1");
		go_exit('/us'.$id);
	}

} else {
	go_exit();
}

$tmp->footer();
?>