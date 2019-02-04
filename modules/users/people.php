<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('users');
$tmp->title('title', Language::config('users'));
User::panel();

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `users`");

if(!$posts){
   $tmp->div('main', Language::config('no_users'));
   $tmp->home();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$u=$db->query("SELECT * FROM `users` ORDER BY id DESC LIMIT ".$start.", ".$num." ");

while($user=$u->fetch_assoc()) {
	echo '<hr><div class="main">'.nick_new($user['id']).'<span class="times">'. times($user['date_last_entry']).'</span>' .(User::level() == 4 ? '<span class="nt">IP: '.$user['ip'].'</span>' : NULL).'</div>';
}

page('?');

$tmp->home();
?>