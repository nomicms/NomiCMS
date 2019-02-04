<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('news');
$tmp->title('title', Language::config('news'));
User::panel();

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `news`");

if(!$posts){
   $tmp->div('main', Language::config('no_news'));
   $tmp->home();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$news= $db->query("SELECT * FROM `news` ORDER BY id DESC LIMIT ".$start.", ".$num." ");

while($n=$news->fetch_assoc()) {
	$count=$db->fass_c("SELECT COUNT(*) as count FROM `news_comments` where `news` = '".$n['id']."'");
	echo '<hr><div class="news"><div><span class="news_title">'.bb(smile($n['name'])).' <span class="nt">'.times($n['time']).'</span></span>'.bb(smile($n['message'])).'<br/></div></div><div class="menu"><a href="/news/comment'.$n['id'].'">'.img('com.png').' '.Language::config('comments').' <span>'.$count.'</span></a></div>';
}

page('?');

$tmp->home();
?>