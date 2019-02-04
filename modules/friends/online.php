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

$posts = $db->fass_c("SELECT COUNT(*) as count FROM friends, users WHERE friends.kto = '".$id."' and friends.status = 1 and friends.komy = users.id and users.date_last_entry > '".(time() - 360)."' ORDER BY users.id DESC");

if ($posts==0) {
	$tmp->friends_menu($id, 3);
	$tmp->div('main', Language::config('no_friends_on'));
	$tmp->footer();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;


$tmp->friends_menu($id, 3);

$row = $db->query("SELECT * FROM friends, users WHERE friends.kto = '".$id."' and friends.status = 1 and friends.komy = users.id and users.date_last_entry > '".(time() - 360)."' ORDER BY users.id DESC LIMIT ".$start.", ".$num."");

echo '<div class="main">';
while($friend = $row->fetch_assoc()) {
	echo nick_new($friend['id']);
}
echo '</div>';

page('?');
$tmp->footer();

} else {
	go_exit();
}
?>