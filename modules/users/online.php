<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('online');
$tmp->title('title', Language::config('online') . (User::level() == 4 ? '<span><a href="/online?guests">'.img('guests.png').'</a></span>' : NULL));
User::panel();


if (isset($_GET['guests'])) {
	$posts=$db->fass_c("SELECT COUNT(*) as count FROM `guests`");
} else {
	$posts=$db->fass_c("SELECT COUNT(*) as count FROM `users` WHERE `date_last_entry` > '".(time() - 360)."'");
}

if(!$posts){
    $tmp->footer();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;


if (isset($_GET['guests'])) {
	if (User::aut() && User::level() == 4) {
		$g=$db->query("SELECT * FROM `guests` ORDER BY time DESC LIMIT ".$start.", ".$num." ");

		while($guests=$g->fetch_assoc()) {
			echo '<hr><div class="main"><span class="times">'.times($guests['time']).'</span><span class="new'.($guests['time'] > (time() - 600) ? ' green' : NULL).'">IP: '.$guests['ip'].' - '.$guests['browser'].'</span></div>';
		}

		page('?guests&');
		$tmp->home();
	}
}

$u=$db->query("SELECT * FROM `users` WHERE `date_last_entry` > '".(time() - 360)."' ORDER BY date_last_entry DESC LIMIT ".$start.", ".$num." ");

while($user=$u->fetch_assoc()) {
	echo '<hr><div class="main">'.nick_new($user['id']).'<span class="times">'. times($user['date_last_entry']).'</span>' .(User::level() == 4 ? '<span class="nt">IP: '.$user['ip'].'</span>' : NULL).'</div>';
}


page('?');

$tmp->home();
?>