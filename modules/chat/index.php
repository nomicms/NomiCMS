<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('chat');

$ms=my_int($db->guard($_GET['ms']));
$posts=$db->fass_c("SELECT COUNT(*) as count FROM `chat`");

$tmp->title('title', Language::config('chat'). ' ('.$posts.')');
User::panel();

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;
$ch=$db->query("select * from `chat` ORDER BY time DESC LIMIT ".$start.", ".$num."");

if(isset($_GET['del'])) {
	if(User::aut()){
		$del = $db->guard($_GET['del']);
		$c=$db->fass("SELECT * FROM `chat` where `id` ='".$del."'");
		if($c['kto'] == User::ID() || User::profile('level') >=3)
			$db->query("DELETE FROM `chat` where `id` ='".$del."'");
		header('location: /chat');
	}
}


reply_user('chat', 'chat', null, null, true);


if(isset($_REQUEST['submit'])) {
	if(User::aut()){
		Security::verify_str();

		$message = $db->guard($_POST['messages']);
		$ant=$db->fass("SELECT * FROM `chat` where `kto` = '".User::ID()."' and `message` = '".$message."' and `time` > '".(time() - 3)."' limit 1");
		
		if ($ant) $error .= Language::config('error');	
		if (empty($message) || mb_strlen($_POST['messages'], 'UTF-8')<2) $error .= Language::config('no_message');

		if(!isset($error)) {
			$db->query("INSERT INTO `chat` set `kto` = '".User::ID()."', `message` = '".$message."', `time` = '".time()."' ");
			header('location: /chat');
		}
	}
}

if(isset($_GET['ms'])) {
	if(User::aut()){
		$msg=$db->fass("select * from `chat` where `id` = '".$ms."' LIMIT 1");
		if (!empty($msg)) {
			echo '<div class="messages no_read"><div>'.nick_new($msg['kto']).'<span class="times">'. times($msg['time']).'</span>'.(($msg['kto'] != User::ID()) ? '<a class="answer" href="/chat/otv'.$msg['id'].'">'.img('answer.png').'</a>' : NULL ).' <br/>'.bb(smile($msg['message'])).'</div></div><hr>';
		}
	}
}

if(User::aut()){
	error($error);

	$tmp->div('menu', '<a href="/chat?'.rand(101, 999).' ">'.img('refresh.png').' '.Language::config('refresh').'</a>');
	bbcode();
	
	$tmp->div('main', '<form method="POST" name="message" action="">
'.Language::config('message').':<br/>
<textarea name="messages"></textarea><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('send').'" />
</form>');
}

if(!$posts) {
	$tmp->div('main', Language::config('no_messages'));
} else {
	echo '<div class="messages">';
	while($chat=$ch->fetch_assoc()) {
		echo '<hr><div>'.nick_new($chat['kto']).' '.(($chat['kto'] == User::ID() || User::profile('level') >=3) ? '<a class="de" href="/chat/del'.$chat['id'].'">'.img('delete.png').'</a>' : NULL).'<span class="times">'. times($chat['time']).'</span>'.(($chat['kto'] != User::ID() && User::aut()) ? '<a class="answer" href="/chat/otv'.$chat['id'].'">'.img('answer.png').'</a>' : NULL ).' <br/>'.bb(smile($chat['message'])).'</div>';
	}
	echo '</div>';

page('?');
}

$tmp->home();
?>