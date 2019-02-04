<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('ads');
$tmp->title('title', Language::config('ads'));
User::panel();

if(User::level() < 2){
	go_exit();
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `ads`");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$n=$db->query("select * from `ads` ORDER BY time DESC LIMIT ".$start.", ".$num."");

if(isset($_GET['del'])) {
	$del = $db->guard($_GET['del']);
	if(User::profile('level') >=3)
		$db->query("DELETE FROM `ads` where `id` ='".$del."'");
	header('location: /apanel/ads_list');
}

$tmp->div('menu', '<a class="items" href="./ads">'.img('link.png').' '.Language::config('ads_add').'</a>');

if(!$posts) {
	$tmp->div('main', Language::config('no_ads'));
} else {
	while($ads=$n->fetch_assoc()) {
		echo '<hr><div class="main">'.Language::config('add_name').': '.nick_new($ads['kto']).' '.((User::level() >=3) ? ' <a class="de" href="./ads_list/del'.$ads['id'].'">'.img('delete.png').'</a>' : NULL).' <br>Ссылка: '.'<a target="_blank" class="link_visual" href="http://'.$ads['link'].'">'. $ads['name'].' </a><br>'.Language::config('time_end').': '.times($ads['time_end']).'</div>';
	}
}

page('?');

$tmp->back('apanel');
?>