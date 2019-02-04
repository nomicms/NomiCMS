<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');
$tmp->title('title', Language::config('forum'));
User::panel();

$id = my_int($_GET['id']);
$section =  my_int($_GET['section']);
$razdel = my_int($_GET['razdel']);

$p=$db->fass("select * from `forum_topic` where `id` = '".$id."' ");
if (!$p) $tmp->show_error();

$s=$db->fass("select * from `forum_section` where `id` = '".$section."'");
if (!$s) $tmp->show_error();

$r=$db->fass("select * from `forum_razdel` where `id` = '".$razdel."'");
if (!$r) $tmp->show_error();

if(!User::aut()){
	go_exit();
}

if(User::aut() && User::level() >= 2){
	$message = $db->query("select * from `forum_message` where `topic` = '".$id."' ");
	
	while($a=$message->fetch_assoc()){
		$db->query("UPDATE `forum_message` set  `razdel` = '".$razdel."', `section` = '".$section."' where `topic` = '".$id."' ");
	}

	$db->query("update `forum_topic` set `razdel` = '".$razdel."', `section` = '".$section."' where  `id` = '".$id."'");
	$db->query("insert into `forum_message` set `razdel` = '".$razdel."', `section` = '".$section."', `topic` = '".$id."', `kto` = '".User::ID()."', `message` = '".Language::config('move_topic').": [blue]".$s['name']."[/blue]', `time` = '".time()."' ");
	header('location: /forum/topic'.$id);
}

$tmp->footer();
?>