<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$id = my_int($db->guard($_GET['id']));

$p=$db->fass("select * from `users` where `id` = '".$id."' "); 

if(!$p) go_exit();

if(User::aut()){
	$del = $db->guard($_GET['delete']);
	$c=$db->fass("SELECT * FROM `wall` where `id` ='".$del."'");
	
	if(!$c) go_exit();

	if($c['kto'] == User::ID() || User::profile('level') >=2) {
		$db->query("DELETE FROM `wall` where `id` ='".$del."'");
		header('location: /us'.$id);
	}
} else {
	go_exit();
}
?>