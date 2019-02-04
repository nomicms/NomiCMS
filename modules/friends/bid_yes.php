<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');
#
require_once(R.'/system/kernel.php');

$tmp->header('friends');
$tmp->title('title', Language::config('friends'));
User::panel();

if(User::aut()){
	if(isset($_GET['id'])){
		$id = my_int($_GET['id']);
	}

	if(isset($_GET['id2'])){
		$id2 = my_int($_GET['id2']);
	}

    $pr=$db->fass("SELECT * FROM `friends` where `id` = '".$id2."' and `komy` = '".User::ID()."'");
    
    if(!$pr){
		$error .= Language::config('error');
    }

	if(!isset($error)){
		User::new_notify($id, 'friend_yes', '/us'.User::ID());

   		$db->query("INSERT INTO `friends` set `kto`=  '".User::ID()."', `komy` = '".$id."', `status` = '1' ");
   		$db->query("UPDATE `friends` set `status` = '1' where `id` = '".$id2."' ");
        go_exit('/friends/bid');
    }

	error($error);
} else {
	go_exit();
}

$tmp->footer();
?>