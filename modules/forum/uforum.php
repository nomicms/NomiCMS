<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');
$tmp->title('title', Language::config('forum'));
User::panel();

$id = isset($_GET['id']) ? my_int($db->guard($_GET['id'])) : NULL;

if (isset($_GET['topic'])) {
	$posts=$db->fass_c("SELECT COUNT(*) as count FROM `forum_topic` where `kto` = '".$id."' ");
	$lng_n = Language::config('no_topics');
} elseif (isset($_GET['posts'])) {
	$posts=$db->fass_c("SELECT COUNT(*) as count FROM `forum_message` where `kto` = '".$id."' ");
	$lng_n = Language::config('no_posts');
}

if(!$posts){
	$tmp->div('main', $lng_n);
	$tmp->back('us'.$id);
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

if (isset($_GET['topic'])) {
	$topic = $db->query("SELECT * FROM `forum_topic` where `kto` = '".$id."' ORDER BY time DESC LIMIT ".$start.", ".$num." ");
	
	echo '<div class="menu">';
	while($fo=$topic->fetch_assoc()) {
		if($fo['is_close_topic'] == 1){
			$icon = img('forum_close.png');
		}elseif($fo['is_top_topic'] == 1){
			$icon = img('forum_pin.png');
		} else {
			$icon = img('forum_topic.png');
		}

		$c=$db->fass_c("SELECT COUNT(*) as count FROM `forum_message` where `topic` = '".$fo['id']."'");
		echo '<hr><a href="/forum/topic'.$fo['id'].'">'.$icon.' '.$fo['name'].' <span>'.$c.'</span></a>';
	}
	echo '</div>';
} elseif (isset($_GET['posts'])) {
	$topic = $db->query("SELECT * FROM `forum_message` where `kto` = '".$id."' ORDER BY time DESC LIMIT ".$start.", ".$num." ");

	echo '<hr><div class="messages">';
	while($fo=$topic->fetch_assoc()){
		$t=$db->fass("SELECT * FROM `forum_topic` where `id` = '".$fo['topic']."'");
		echo '<hr><div>'.nick_new($fo['kto']).''.Language::config('theme').': '.$t['name'].'<span class="times">'.times($fo['time']).'</span><br><a href="/forum/topic'.$fo['topic'].'">'.bb(smile($fo['message'])).'</a></div>';
	}
	echo '</div>';
}

page('?');

$tmp->back('us'.$id);
?>