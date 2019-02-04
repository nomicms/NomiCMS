<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$id=my_int($db->guard($_GET['id']));
$s=$db->fass("select * from `lib_r` where `id` = '".$id."'");

$tmp->header('comments');
$tmp->title('title', '<a href="/lib/c/l'.$id.'">'.$s['name'].'</a>' . ' / ' . Language::config('comments'));
User::panel();

if(!User::aut()){
	$tmp->need_auth('lib/c/l'.$id);
}

if (!$s) $tmp->show_error();

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `lib_comments` where `lib_r` = '".$id."'");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$n=$db->query("select * from `lib_comments` where `lib_r` = '".$id."' ORDER BY time DESC LIMIT ".$start.", ".$num."");


if(User::ID() == $p['kto'] || User::level() >=3){
	if(isset($_GET['del'])) {
		$cid = my_int($_GET['cid']);
		$p=$db->fass("select * from `lib_comments` where `id` = '".$cid."' ");

		if (!$p) $tmp->show_error();

		if(User::ID() == $p['kto'] || User::level() >=3)
			$db->query("DELETE FROM `lib_comments` where `id` ='".$cid."'");
		header('location: /lib/comment'.$id);
	}
}


reply_user('lib_comments', 'lib/comment', $id, 'lib_r');


if(isset($_REQUEST['submit'])) {
	Security::verify_str();

	$message = $db->guard($_POST['messages']);

	if(mb_strlen($message, 'UTF-8')<2) $error .= Language::config('no_message');

	if(!isset($error)) {
		if (User::ID() != $s['kto'])
			User::new_notify($s['kto'], 'comment_add||'.$s['name'], '/lib/comment'.$id);

		$db->query("INSERT INTO `lib_comments` set `kto` = '".User::ID()."', `lib_r` = '".$id."', `message` = '".$message."', `time` = '".time()."' ");
		header('location: /lib/comment'.$id);
	}
}

error($error);
bbcode();
$_POST['message'] = (empty($_POST['message']) ? null : $_POST['message']);

$tmp->div('main', '<form method="POST" name="message" action="">
'.Language::config('message').':<br/>
<textarea name="messages">'.out($_POST['message']) .'</textarea><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('send').'" /></form>');

if(!$posts){
	$tmp->div('main', Language::config('no_comments'));
} else {

	echo '<div class="comments">';
	while($lib=$n->fetch_assoc()) {
		echo '<hr><div>'.nick_new($lib['kto']).' '.((User::ID() == $lib['kto'] || User::level() >=3) ? ' <a class="de" href="/lib/comment'.$id.'/del'.$lib['id'].'">'.img('delete.png').'</a>' : NULL).'<span class="times">'.times($lib['time']).'</span>'.($lib['kto'] != User::ID() ? '<a class="answer" href="comment'.$id.'/otv'.$lib['id'].'">'.img('answer.png').'</a>' : NULL ).' <br/>'.bb(smile($lib['message'])).'</div>';
	}
	echo '</div>';

page('?');
}

$tmp->back('lib/c/l'.$id);
?>