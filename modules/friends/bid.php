<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');
#
require_once(R.'/system/kernel.php');

$tmp->header('friends');
$tmp->title('title', Language::config('friends'));
User::panel();

if(User::aut()) {

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `friends` where `komy` = '".User::ID()."' and `status` = '0' or `kto` = '".User::ID()."' and `status` = '0'");

if ($posts==0) {
	$tmp->friends_menu(User::ID(), 2);
	$tmp->div('main', Language::config('no_bid'));
	$tmp->footer();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;


$friends = $db->query("SELECT * FROM `friends` where `komy` = '".User::ID()."' and `status` = '0' or `kto` = '".User::ID()."' and `status` = '0' ORDER BY id DESC LIMIT ".$start.", ".$num." ");

$tmp->friends_menu(User::ID(), 2);


while($f=$friends->fetch_assoc()) {
	echo '<div class="main">';
	if ($f['kto'] == User::ID()) {
		echo nick_new($f['komy']).''.Language::config('bid_friend').'</a>';
	} else {
		echo nick_new($f['kto']).'<a class="friends_btn green" href="/friends/bid_yes'.$f['kto'].'/'.$f['id'].'">'.Language::config('add_friend').'</a> <a class="friends_btn red" href="/friends/bid_no'.$f['kto'].'/'.$f['id'].'">'.Language::config('no_add_friend').'</a>';
	}
	echo '</div><hr>';
}

page('?');
$tmp->footer();

} else {
	header('location: /');
}
?>