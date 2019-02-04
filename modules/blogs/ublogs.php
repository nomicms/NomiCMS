<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('blogs');
$tmp->title('title', Language::config('blogs'));
User::panel();

$id = isset($_GET['id']) ? my_int($db->guard($_GET['id'])) : NULL;

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `blogs` where `kto` = '".$id."' ");

if(!$posts){
	$tmp->div('main', Language::config('no_blogs'));
	$tmp->back('us'.$id);
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$blogs = $db->query("SELECT * FROM `blogs` where `kto` = '".$id."' ORDER BY time DESC LIMIT ".$start.", ".$num." ");

echo '<div class="blog">';
while($b=$blogs->fetch_assoc()) {
	echo '<a href="/blogs/view'.$b['id'].'"><span class="blog_title">'.img('blog_title.png').' '.bb(smile($b['name'])).'</span>
<span class="times">'.times($b['time']).'</span><br>'.bb(smile(mb_strimwidth($b['text'], 0, 110, "..."))).'</a><hr>';
}
echo '</div>';

$tmp->div('menu', '<a href="/us'.$id.'">'.img('link.png').' '.Language::config('back').'</a>');

page('?');
$tmp->footer();
?>