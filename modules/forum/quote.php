<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');
$tmp->title('title', Language::config('quote'));
User::panel();

$id=my_int($db->guard($_GET['id']));
$p=$db->fass("select * from `forum_topic` where `id` = '".$id."' "); 
$s=$db->fass("select * from `forum_section` where `id` = '".$p['section']."'");
$r=$db->fass("select * from `forum_razdel` where `id` = '".$p['razdel']."'");

if(!User::aut()){
	go_exit();
}

if(User::aut()){
	$q = my_int($_GET['quote']);
	$qu=$db->fass("SELECT * FROM `forum_message` where `id` = '".$q."'");
	
	if (!$qu) $tmp->show_error();

	if($qu['kto'] != User::ID()){
		if(isset($_REQUEST['submit'])){
			$message = $db->guard($_POST['messages']);
		
			if(empty($message) || mb_strlen($message, 'UTF-8')<2) $error .= Language::config('no_message');

			$filename = $db->guard($_FILES['file']['name']);

			if (!empty($filename)) {
				$whitelist = array('jpg','gif','png','jpeg', 'bmp','zip','rar','mp4','mp3','amr','3gp','avi','flv','apk','txt');
				$maxsize = 10;
				$dir = R.'/files/forum';
				$ext = strtolower(strrchr($filename, '.'));
				$size = $_FILES['file']['size'];

				if (!in_array(substr($ext, 1), $whitelist)) $error .= Language::config('error_ext').'<br />';
				if ($size > (1048576 * $maxsize)) $error .= Language::config('max_size').'. [Max. '.$maxsize.'mb.]<br />';

				$file = rand(1,999).'_NOMICMS_'.rand(1,999). $ext;
			}

			if(!isset($error)) {
				if($p['is_close_topic'] != 1){
					$db->query("INSERT INTO `forum_message` set `razdel` = '".$p['razdel']."', `section` = '".$p['section']."', `topic` = '".$id."', `kto` = '".User::ID()."', `message` = '".$message."', `user_quote` = '".nickname($qu['kto'])."', `quote` = '".$qu['message']."', `time` = '".time()."' ");
					$lid=$db->insert_id();
			
					if(!empty($filename)){
					    copy($_FILES['file']['tmp_name'], $dir . '/' . $file );
						$db->query("INSERT INTO `forum_file` set `kto` = '".User::ID()."', `post_id` = '".$lid."', `thema` = '".$id."', `name` = '".$file."' ");
					}
					
					$fc=$db->fass_c("SELECT COUNT(*) as count FROM `forum_message` where `topic` = '".$id."' ");
					$num_us = User::settings('num', $ot['kto']);
					if ($fc > $num_us) {
						$pg = ceil($fc / $num_us);
						$st = '?page='.$pg;
					}else{
						$st = '';
					}

					User::new_notify($qu['kto'], 'replay_quote||'.$p['name'], '/forum/topic'.$id.$st);

					$db->query("UPDATE `users` set `money` = money + 5 where `id` = '".User::ID()."' ");
					$db->query("UPDATE `forum_topic` set `last_message_time` = '".time()."' where `id` = '".$id."' ");
					header('location: /forum/topic'.$id.$st);
				}
			}
	}
	
		if($p['is_close_topic'] != 1){
			error($error);
			$tmp->div('messages', '<div>'.bb(smile($qu['message'])).'</div><hr>' );
			bbcode();

			$tmp->div('main', '<form method="POST" name="message" action="" enctype="multipart/form-data">
'.Language::config('message').':<br/>
<textarea name="messages"></textarea><br />
<input name="file" type="file" id="file" onchange="uploadFile(this)">
<label class="select_file" for="file">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
<input type="submit" name="submit" value="'.Language::config('send').'" /></form>');
			$tmp->back('forum/topic'.$id);
		}
	}

} else {
	header('location: /forum/topic'.$id);
}

$tmp->footer();
?>