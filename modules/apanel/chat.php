<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('admin_chat');

$ms = (empty($ms) ? null : my_int($_GET['ms']));

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `admin_chat`");

$tmp->title('title', Language::config('admin_chat'). ' ('.$posts.')');
User::panel();

if (User::level()>=3) {

	$total = intval((($posts-1)/$num)+1);
	if(empty($page) or $page<0) $page=1;
	if($page>$total) $page=$total;
	$start=$page*$num-$num;
	$ch=$db->query("select * from `admin_chat` ORDER BY time DESC LIMIT ".$start.", ".$num."");

	if(isset($_GET['del'])) {
		$del = $db->guard($_GET['del']);
		$db->query("DELETE FROM `admin_chat` where `id` ='".$del."'");
		header('location: /apanel/admin_chat');
	}

	if(isset($_GET['otv'])){
		$o=$db->guard($_GET['otv']);
		$ot=$db->fass("SELECT * FROM `admin_chat` where `id` = '".$o."'");
		
		if($ot['kto'] != User::ID() && !empty($ot)){

			if(isset($_REQUEST['submit'])) {
				$message = $db->guard($_POST['messages']);
				
				if(empty($message) || mb_strlen($_POST['messages'], 'UTF-8')<2) $error .= Language::config('no_message');

				if(!isset($error)) {
					$db->query("INSERT INTO `admin_chat` set `kto` = '".User::ID()."', `message` = '[rep]".nickname($ot['kto'])."[/rep] ".$message."', `time` = '".time()."' ");
					$lid = $db->insert_id();
					
					User::new_notify($ot['kto'], 'rep_admin_chat', '/apanel/admin_chat/'.$lid);

					$db->query("UPDATE `users` set `money` = money + 5 where `id` = '".User::ID()."'");
					header('location: /apanel/admin_chat');
				}
			}

			$tmp->div('messages', '<div>'.bb(smile($ot['message'])).'</div><hr>' );
			
			bbcode();
			error($error);

			$tmp->div('main', '<form method="POST" name="message" action="">'.Language::config('message').':<br/><textarea name="messages"></textarea><br /><input type="submit" name="submit" value="'.Language::config('send').'" /></form>');
			$tmp->div('menu', '<hr><a href="/apanel/admin_chat">'.img('link.png').' '.Language::config('back').'</a>');
			$tmp->footer();
		}
	}

	if(isset($_REQUEST['submit'])) {
		$message = $db->guard($_POST['messages']);

		if (empty($message) || mb_strlen($_POST['messages'], 'UTF-8')<2) $error .= Language::config('no_message');

		if(!isset($error)) {
			$db->query("INSERT INTO `admin_chat` set `kto` = '".User::ID()."', `message` = '".$message."', `time` = '".time()."' ");
			header('location: /apanel/admin_chat');
		}
	}

	if(isset($_GET['ms'])) {
		$msg=$db->fass("select * from `admin_chat` where `id` = '".$ms."'");
		if (!empty($msg)) {
			echo '<div class="messages no_read"><div>'.nick_new($msg['kto']).'<span class="times">'. times($msg['time']).'</span>'.(($msg['kto'] != User::ID()) ? '<a class="answer" href="/apanel/admin_chat/otv'.$msg['id'].'">'.img('answer.png').'</a>' : NULL ).' <br/>'.bb(smile($msg['message'])).'</div></div><hr>';
		}
	}


	error($error);

	$tmp->div('menu', '<a href="/apanel/admin_chat?'.rand(101, 999).' ">'.img('refresh.png').' '.Language::config('refresh').'</a>');
	bbcode();
	$tmp->div('main', '<form method="POST" name="message" action="">'.Language::config('message').':<br/><textarea name="messages"></textarea><br /><input type="submit" name="submit" value="'.Language::config('send').'" /></form>');


	if(!$posts) {
		$tmp->div('main', Language::config('no_messages'));
	} else {
		echo '<div class="messages">';
		while($chat=$ch->fetch_assoc()) {
			echo '<hr><div>'.nick_new($chat['kto']).'<a class="de" href="/apanel/admin_chat/del'.$chat['id'].'">'.img('delete.png').'</a><span class="times">'. times($chat['time']).'</span>'.(($chat['kto'] != User::ID() && User::aut()) ? '<a class="answer" href="/apanel/admin_chat/otv'.$chat['id'].'">'.img('answer.png').'</a>' : NULL ).' <br/>'.bb(smile($chat['message'])).'</div>';
		}
		echo '</div>';

	page('?');	
	}

} else {
  go_exit();
}

$tmp->back('apanel');
?>