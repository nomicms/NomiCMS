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

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `friends` where `komy` = '".$id."' and `status` ='1'");

if ($posts==0) {
	$tmp->friends_menu($id, 1);
	$tmp->div('main', Language::config('no_friends'));
	$tmp->footer();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$friends= $db->query("SELECT * FROM `friends` where `komy` = '".$id."' and `status` = '1' ORDER BY id DESC LIMIT ".$start.", ".$num." ");

$tmp->friends_menu($id, 1);

echo '<div class="main">';

while ($f=$friends->fetch_assoc()) {
	echo nick_new($f['kto']);
}

echo '</div>';

page('?');
$tmp->footer();

} else {
	go_exit();
}
?>