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

$count = $db->fass_c("SELECT COUNT(*) as count FROM `friends` where `kto` = '".$id."' and `status` = '1' ORDER BY `id` DESC");

if ($count==0) {
	$tmp->friends_menu($id, 3);
	$tmp->div('main', Language::config('no_friends_on'));
	$tmp->footer();
	exit();
}

$row = $db->query("SELECT * FROM `friends` where `kto` = '".$id."' and `status` = '1' ORDER BY `id` DESC");

$tmp->friends_menu($id, 3);

echo '<div class="main">';

while($friends = $row->fetch_assoc()) {
	$online_user = $db->query("SELECT * FROM `users` WHERE `date_last_entry` > '".(time() - 360)."' and `id` ='".$friends['komy']."' ");
	while($a = $online_user->fetch_assoc()) {
		$s .= nick_new($a['id']);
	}
}

if (!empty($s)) { echo $s; } else { echo Language::config('no_friends_on'); }

echo '</div>';


} else {
	header('location: /');
}

$tmp->footer();
?>