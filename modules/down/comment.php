<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$id=my_int($db->guard($_GET['id']));
$s=$db->fass("select * from `zc_file` where `id` = '".$id."'");

$tmp->header('comments');
$tmp->title('title', '<a href="/zc/file'.$id.'">'.$s['name'].'</a>' . ' / ' . Language::config('comments'));
User::panel();

if(!User::aut()){
	$tmp->need_auth('zc/file'.$id);
}

if (!$s) $tmp->show_error();

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `zc_comments` where `zc_file` = '".$id."'");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$n=$db->query("select * from `zc_comments` where `zc_file` = '".$id."' ORDER BY time DESC LIMIT ".$start.", ".$num."");

if(User::ID() == $p['kto'] || User::level() >=3){
	if(isset($_GET['del'])) {
		$cid = my_int($_GET['cid']);
		$p=$db->fass("select * from `zc_comments` where `id` = '".$cid."' ");

		if (!$p) $tmp->show_error();

		if(User::ID() == $p['kto'] || User::level() >=3)
			$db->query("DELETE FROM `zc_comments` where `id` ='".$cid."'");
		header('location: /zc/comment'.$id);
	}
}


reply_user('zc_comments', 'zc/comment', $id, 'zc_file');


if(isset($_REQUEST['submit'])){
	Security::verify_str();

	$message = $db->guard($_POST['messages']);
	
	if(mb_strlen($message, 'UTF-8')<2) $error .= Language::config('no_message');

	if(!isset($error)) {
		if (User::ID() != $s['kto'])
			User::new_notify($s['kto'], 'comment_add||'.$s['name'], '/zc/comment'.$id);

		$db->query("INSERT INTO `zc_comments` set `kto` = '".User::ID()."', `zc_file` = '".$id."', `message` = '".$message."', `time` = '".time()."' ");
		header('location: /zc/comment'.$id);
	}
}

	error($error);
	bbcode();

	$tmp->div('main', '<form method="POST" name="message" action="">
'.Language::config('message').':<br/>
<textarea name="messages"></textarea><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('send').'" /></form>');

if($posts==0){
	$tmp->div('main', Language::config('no_comments'));
} else {

	echo '<div class="comments">';
	while($zc_file=$n->fetch_assoc()) {
		echo '<hr><div>'.nick_new($zc_file['kto']).' '.((User::ID() == $zc_file['kto'] || User::level() >=3) ? ' <a class="de" href="/zc/comment'.$id.'/del'.$zc_file['id'].'">'.img('delete.png').'</a>' : NULL).'<span class="times">'.times($zc_file['time']).'</span>'.($zc_file['kto'] != User::ID() ? '<a class="answer" href="comment'.$id.'/otv'.$zc_file['id'].'">'.img('answer.png').'</a>' : NULL ).' <br/>'.bb(smile($zc_file['message'])).'</div>';
	}
	echo '</div>';

page('?');
}

$tmp->back('zc/file'.$id);
?>