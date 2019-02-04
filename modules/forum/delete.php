<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');
$tmp->title('title', Language::config('delet'));
User::panel();

if(!User::aut()){
	go_exit();
}

$id=my_int($db->guard($_GET['id']));
$p=$db->fass("select * from `forum_topic` where `id` = '".$id."' "); 

if (!$p) $tmp->show_error();

if(User::aut()){
	$del = $db->guard($_GET['delete']);
	$c=$db->fass("SELECT * FROM `forum_message` where `id` ='".$del."'");
	
	if (!$c) $tmp->show_error();

	if(User::profile('level') >=3) {
		$db->query("UPDATE `forum_message` set `message` = '[red]( сообщение удалено )[/red]' where `id` = '".$del."'");
		// $db->query("DELETE FROM `forum_message` where `id` ='".$del."'");	// DEL. MESS BD
		$db->query("DELETE FROM `forum_file` where `post_id` ='".$del."'");
	}
	header('location: /forum/topic'.$id);
}

$tmp->footer();
?>