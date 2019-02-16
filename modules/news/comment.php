<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('comments');

$id=my_int($db->guard($_GET['id']));
$s=$db->fass("select * from `news` where `id` = '".$id."'");

if (!$s) $tmp->show_error();

$tmp->title('title', Language::config('comments'));
User::panel();

if(!User::aut()){
	$tmp->need_auth('news');
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `news_comments` where `news` = '".$id."'");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;
$n=$db->query("select * from `news_comments` where `news` = '".$id."' ORDER BY time DESC LIMIT ".$start.", ".$num."");


if(User::ID() == $p['kto'] || User::level() >=3) {
	if(isset($_GET['del'])) {
		$cid = my_int($_GET['cid']);
		$p=$db->fass("select * from `news_comments` where `id` = '".$cid."' ");

		if (!$p) $tmp->show_error();

		if($p['kto'] == User::ID() || User::level() >=3)
			$db->query("DELETE FROM `news_comments` where `id` ='".$cid."'");
		header('location: /news/comment'.$id);
	}
}


reply_user('news_comments', 'news/comment', $id, 'news');


if(isset($_REQUEST['submit'])) {
	Security::verify_str();

	$message = $db->guard($_POST['messages']);
	
	if (empty($message) || mb_strlen($message, 'UTF-8')<2) $error .= Language::config('no_message');
	
	if(!isset($error)) {
		if (User::ID() != $s['kto'])
			User::new_notify($s['kto'], 'news_comm_add||'.$s['name'], '/news/comment'.$id);

		$db->query("INSERT INTO `news_comments` set `kto` = '".User::ID()."', `news` = '".$id."', `message` = '".$message."', `time` = '".time()."' ");
		header('location: /news/comment'.$id);
	}
}

echo '<div class="news"><div><span class="news_title">'.bb(smile($s['name'])).' <span class="nt">'.times($s['time']).'</span></span>'.bb(smile($s['message'])).'<br/></div></div>';

error($error);
bbcode();
$_POST['message'] = (empty($_POST['message']) ? null : $_POST['message']);

$tmp->div('main', '<form method="POST" name="message" action="">
'.Language::config('message').':<br/>
<textarea name="messages">'.out($_POST['message']).'</textarea><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('send').'" />
</form>');

if($posts==0) {
	$tmp->div('main', Language::config('no_comments'));
} else {
	echo '<div class="comments">';
	while($news=$n->fetch_assoc()) {
		echo '<hr><div>'.nick_new($news['kto']).' '.((User::ID() == $news['kto'] || User::level() >=3) ? ' <a class="de" href="comment'.$id.'/del'.$news['id'].'">'.img('delete.png').'</a>' : NULL).'<span class="times">'.times($news['time']).'</span>'.($news['kto'] != User::ID() ? '<a class="answer" href="comment'.$id.'/otv'.$news['id'].'">'.img('answer.png').'</a>' : NULL ).' <br/>'.bb(smile($news['message'])).'</div>';
	}
	echo '</div>';

page('?');
}

$tmp->back('news');
?>
