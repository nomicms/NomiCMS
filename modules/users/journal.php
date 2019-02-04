<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('journal');
$tmp->title('title', Language::config('journal'));

User::panel();

if(!User::aut()){
	go_exit();
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `journal` where `komy` = '".User::ID()."'");

if($posts==0){
   $tmp->div('main', Language::config('no_journal'));
   $tmp->footer();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$journal = $db->query("SELECT * FROM `journal` where `komy` = '".User::ID()."' ORDER BY id DESC LIMIT ".$start.", ".$num." ");

echo '<div class="notify">';

while($j=$journal->fetch_assoc()) {
	$s = explode('||', $j['message']);
	echo '<a href="'.$j['url'].'">'.img(($j['readln'] ? 'notify_i.png' : 'notify_n.png')).' <span class="times">'.times($j['time']).'</span> '.nick_new($j['kto'], true).' <div> '.($s[1] ? Language::config($s[0]).': '.$s[1] : Language::config($s[0])).' </div></a>';
	
	$db->query("UPDATE `journal` set `readln` = '1' where `id` = '".$j['id']."'");
}

echo '</div>';

page('?');
$tmp->footer();
?>