<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('content');
$tmp->title('title', Language::config('content'));
User::panel();

if(User::level() < 3){
	go_exit();
}


$ch = $db->fass_c("SELECT COUNT(*) as count FROM `chat`");
$gl = $db->fass_c("SELECT COUNT(*) as count FROM `guests`");


if(isset($_GET['clear_chat'])){
	if(isset($_GET['yes'])){
		$db->query("DELETE FROM `chat`");
		header('location: /apanel/content');
	}

	$tmp->del_sure(Language::config('clear_chat_ds').' ('.$ch.')', 'clear_chat&yes');
	$tmp->footer();
}


if(isset($_GET['clear_guests'])){
	if(isset($_GET['yes'])){
		$db->query("DELETE FROM `guests`");
		header('location: /apanel/content');
	}

	$tmp->del_sure(Language::config('clear_guests_ds').' ('.$gl.')', 'clear_guests&yes');
	$tmp->footer();
}


if(isset($_GET['clear_user'])){

	if(isset($_REQUEST['submit'])){
		$id = my_int($db->guard($_POST['uid']));

		if (empty($id)) go_exit('content?clear_user');

		if ($_REQUEST['submit'] == Language::config('clear_msg_prev')) {
			$u = $db->fass("select `login` from `users` where `id` = '".$id."' LIMIT 1");

			if (empty($u))
				go_exit('content?clear_user');

			$_POST['submit'] = Language::config('delet');

			$b = $db->fass_c("SELECT COUNT(*) as count FROM `blogs` WHERE `kto` = '".$id."'");
			$bc = $db->fass_c("SELECT COUNT(*) as count FROM `blog_comms` WHERE `kto` = '".$id."'");
			$c = $db->fass_c("SELECT COUNT(*) as count FROM `chat` WHERE `kto` = '".$id."'");
			$d = $db->fass_c("SELECT COUNT(*) as count FROM `dialogs_message` WHERE `kto` = '".$id."'");
			$ft = $db->fass_c("SELECT COUNT(*) as count FROM `forum_topic` WHERE `kto` = '".$id."'");
			$fm = $db->fass_c("SELECT COUNT(*) as count FROM `forum_message` WHERE `kto` = '".$id."'");
			$j = $db->fass_c("SELECT COUNT(*) as count FROM `journal` WHERE `kto` = '".$id."'");
			$l = $db->fass_c("SELECT COUNT(*) as count FROM `lib_r` WHERE `kto` = '".$id."'");
			$lc = $db->fass_c("SELECT COUNT(*) as count FROM `lib_comments` WHERE `kto` = '".$id."'");
			$nc = $db->fass_c("SELECT COUNT(*) as count FROM `news_comments` WHERE `kto` = '".$id."'");
			$wl = $db->fass_c("SELECT COUNT(*) as count FROM `wall` WHERE `kto` = '".$id."'");
			$z = $db->fass_c("SELECT COUNT(*) as count FROM `zc_file` WHERE `kto` = '".$id."'");
			$zc = $db->fass_c("SELECT COUNT(*) as count FROM `zc_comments` WHERE `kto` = '".$id."'");
			
			echo '<div class="main">
'.Language::config('clear_content_us').': <a class="link_visual" target="_blank" href="/us'.$id.'">'.$u['login'].'</a><br>
'.Language::config('blogs').' / ('.Language::config('comments').'): '.$b.' / '.$bc.'<br>
'.Language::config('chat').': '.$c.'<br>
'.Language::config('dialogs').': '.$d.'<br>
'.Language::config('forum').': '.$ft.' / '.$fm.'<br>
'.Language::config('journal').': '.$j.'<br>
'.Language::config('lib').' / ('.Language::config('comments').'): '.$l.' / '.$lc.'<br>
'.Language::config('news').' ('.Language::config('comments').'): '.$nc.'<br>
'.Language::config('wall').': '.$wl.'<br>
'.Language::config('zc').' / ('.Language::config('comments').'): '.$z.' / '.$zc.'<br></div>';

		} elseif ($_REQUEST['submit'] == Language::config('delet')) {
			Security::verify_str();

			$db->query("DELETE FROM `blogs` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `blog_comms` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `chat` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `dialogs_message` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `forum_topic` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `forum_message` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `journal` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `lib_r` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `lib_comments` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `news_comments` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `wall` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `zc_file` WHERE `kto` = '".$id."'");
			$db->query("DELETE FROM `zc_comments` WHERE `kto` = '".$id."'");

			go_exit('content');
		}

	}

	$tmp->div('main', '<form method="POST" action="">
'.Language::config('clear_msg_user_id').': <br/>
<input type="number" name="uid" value="'.out($_POST['uid']).'" style="width: 50px" /><br/>
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" id="sr" value="'.(empty($_POST['submit']) ? Language::config('clear_msg_prev') : out($_POST['submit'])).'" /></form>');

	$tmp->back('apanel/content');
}


echo '<div class="main">
'.(empty($ch) ? img('ok.png" class="ok_img') : NULL ).' '.Language::config('clear_chat_desc').':<br>
<a class="content_btn" href="content?clear_chat">'.Language::config('clear_chat').'</a>
</div>
<hr>
<div class="main">
'.Language::config('clear_msg_user').':<br>
<a class="content_btn" href="content?clear_user">'.Language::config('clear_msg_user_desc').'</a>
</div>
<hr>
<div class="main">
'.(empty($gl) ? img('ok.png" class="ok_img') : NULL ).' '.Language::config('clear_guests').':<br>
<a class="content_btn" href="content?clear_guests">'.Language::config('clear_guests_desc').'</a>
</div>
';

$tmp->back('apanel');
?>