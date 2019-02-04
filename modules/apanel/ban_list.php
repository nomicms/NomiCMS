<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('ban');
$tmp->title('title', Language::config('ban'));
User::panel();

if(User::level() < 2){
	go_exit();
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `ban`");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$n=$db->query("select * from `ban` ORDER BY time DESC LIMIT ".$start.", ".$num."");

if(isset($_GET['del'])) {
	$del = $db->guard($_GET['del']);
	if(User::profile('level') >=3)
		$db->query("DELETE FROM `ban` where `id` ='".$del."'");
	header('location: /apanel/ban_list');
}

if(!$posts) {
	$tmp->div('main', Language::config('no_ban'));
} else {
	while($ban=$n->fetch_assoc()) {
   		echo '<hr><div class="main">'.nick_new($ban['kto']).'забанил &nbsp;'.nick_new($ban['komy']).' '.((User::profile('level') >=3) ? ' <a class="de" href="/apanel/ban_list/del'.$ban['id'].'">'.img('delete.png').'</a>' : NULL).'<br>Бан выдан: '.times($ban['time']).'<br>Истекает: '.((time() >= $ban['time_end']) ? '-' : times($ban['time_end'])).'<br>Причина: '.bb(smile($ban['message'])).'</div>';
	}
}

page('?');

$tmp->back('apanel');
?>