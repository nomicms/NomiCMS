<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('blogs');
$tmp->title('title', Language::config('blogs'));
User::panel();

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `blogs`");

if(!$posts){
	if(User::aut())
		$tmp->div('menu', '<a href="/blogs/add">'.img('blog.png').' '.Language::config('new_blog').'</a>');
	else
		$tmp->div('main', Language::config('no_blogs'));
	$tmp->footer();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$blogs=$db->query("SELECT * FROM `blogs` ORDER BY time DESC LIMIT ".$start.", ".$num." ");

echo '<div class="blog">';
while($b=$blogs->fetch_assoc()) {
	echo '<hr><a href="/blogs/view'.$b['id'].'"><span class="blog_title">'.img('blog_title.png').' '.bb(smile($b['name'])).'</span>
<span class="times">'.times($b['time']).'</span><br>'.bb(smile(mb_strimwidth($b['text'], 0, 110, "..."))).'</a>';
}
echo '</div>';

page('?');

if(User::aut()){
	$tmp->div('menu', '<hr><a href="/blogs/add">'.img('blog.png').' '.Language::config('new_blog').'</a>');
}

$tmp->footer();
?>