<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$id=isset($_GET['id']) ? $db->guard($_GET['id']) : NULL;
$s=$db->fass("select * from `blogs` where `id` = '" .$id."'");

$tmp->header('comments');
$tmp->title('title', '<a href="/blogs/view'.$id.'">'.Language::config('blogs').'</a>' . ' / ' . Language::config('comments'));
User::panel();

if(User::aut()){

if (!$s) $tmp->show_error();

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `blog_comms` where `blog_id` = '".$id."'");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;
$n=$db->query("select * from `blog_comms` where `blog_id` = '".$id."' ORDER BY time DESC LIMIT ".$start.", ".$num."");

$cid = (empty($cid) ? null : my_int($_GET['cid']));

$p=$db->fass("select * from `blog_comms` where `id` = '".$cid."' ");

if(User::ID() == $p['kto'] || User::level() >=3) {

	if(isset($_GET['del'])) {
		
		if (!$p) $tmp->show_error();

		if($p['kto'] == User::ID() || User::level() >=3)
			$db->query("DELETE FROM `blog_comms` where `id` ='".$cid."'");
		header('location: /blogs/comment'.$id);
	}
}


if(isset($_REQUEST['submit'])) {
	$message = $db->guard($_POST['messages']);
	
	if(mb_strlen($message, 'UTF-8')<2) $error .= Language::config('no_message');

	if(!isset($error)){
		if (User::ID() != $s['kto'])
			User::new_notify($s['kto'], 'blog_comm_add', '/blogs/comment'.$id);

		$db->query("INSERT INTO `blog_comms` set `kto` = '".User::ID()."', `blog_id` = '".$id."', `message` = '".$message."', `time` = '".time()."' ");
		header('location: /blogs/comment'.$id);
	}
}
	
error($error);
bbcode();

$tmp->div('main', '<form method="POST" name="message" action="">
'.Language::config('message').':<br/>
<textarea name="messages"></textarea><br />
<input type="submit" name="submit" value="'.Language::config('send').'" /></form>');

if($posts==0) {
	$tmp->div('main', Language::config('no_comments'));
} else {
	
	echo '<div class="comments">';
	while($blog_comms=$n->fetch_assoc()) {
		echo '<hr><div>'.nick_new($blog_comms['kto']).' '.((User::ID() == $blog_comms['kto'] || User::level() >=3) ? ' <a class="de" href="/blogs/comment'.$id.'?del&cid='.$blog_comms['id'].'">'.img('delete.png').'</a>' : NULL).'<span class="times">'.times($blog_comms['time']).'</span><br/>'.bb(smile($blog_comms['message'])).'</div>';
	}
	echo '</div>';

page('?');
}

} else {
	$tmp->div('error', Language::config('need_auth'));
}

$tmp->div('menu', '<hr><a href="/blogs/view'.$id.'">'.img('link.png').' '.Language::config('back').'</a>');
$tmp->footer();
?>