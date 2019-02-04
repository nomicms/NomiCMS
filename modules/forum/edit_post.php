<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');
$tmp->title('title', Language::config('edit'));
User::panel();

$id=my_int($db->guard($_GET['edit_post']));
$p=$db->fass("SELECT * FROM `forum_message` where `id` = '".$id."' "); 
$t=$db->fass("SELECT * FROM `forum_topic` where `id` = '".$p['topic']."'");

if(!User::aut()){
	go_exit();
}

if(User::aut() && $t['is_close_topic'] != 1){
	$ot=$db->fass("SELECT * FROM `forum_message` where `id` = '".$id."'");
	
	if (!$ot) $tmp->show_error();

	if ($p['time'] + 60 < time() && User::level() <= 2) {
		go_exit('/forum/topic'.$p['topic']);
	}

	if($ot['kto'] == User::ID() || User::level() >= 2){
		if(isset($_REQUEST['submit'])){
			$message = $db->guard($_POST['messages']);

			if(empty($_POST['messages']) || mb_strlen($_POST['messages'], 'UTF-8')<2) $error .= Language::config('no_message');

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
				$db->query("update `forum_message` set `message` = '".$message."' where `id` = '".$id."' ");
				
				if(!empty($filename)){
			    	copy($_FILES['file']['tmp_name'], $dir . '/' . $file );
					$db->query("insert into `forum_file` set `kto` = '".User::ID()."', `post_id` = '".$id."', `thema` = '".$p['topic']."', `name` = '".$file."' ");
				}

				header('location: /forum/topic'.$p['topic']);
			}
		}

		echo '<div class="messages"><div>'.bb(smile($ot['message'])).'';

		$filec = $db->n_r("select id from `forum_file` where `post_id` = '".$id."' limit 1");
		if($filec){
			$file = $db->query("select * from `forum_file` where `post_id` = '".$id."'");
			echo '<div class="files">';
			while($files = $file->fetch_assoc()){
				if($files['post_id'] == $id)
					echo  '<a href="/files/forum/'.$files['name'].'">'.img('down_s.png').' '.$files['name'].' | '.format_filesize(R.'/files/forum/'.$files['name']).'</a><br>';
			}
			echo '</div>';
		}

		echo '</div></div>';

		error($error);
		bbcode();

		$tmp->div('main', '<form method="POST" name = "message" action="" enctype="multipart/form-data">
'.Language::config('message').':<br/>
<textarea name="messages">'.$ot['message'].'</textarea><br />
<input name="file" type="file" id="file" onchange="uploadFile(this)">
<label class="select_file" for="file">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
<input type="submit" name="submit" value="'.Language::config('edit').'" /></form>');
		$tmp->back('forum/topic'.$p['topic']);
	}

} else {
	header('location: /forum/topic'.$p['topic']);
}

$tmp->footer();
?>