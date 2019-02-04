<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');

$id=my_int($db->guard($_GET['id']));
$p=$db->fass("select * from `forum_topic` where `id` = '".$id."' LIMIT 1");
$s=$db->fass("select * from `forum_section` where `id` = '".$p['section']."' LIMIT 1");
$r=$db->fass("select * from `forum_razdel` where `id` = '".$p['razdel']."' LIMIT 1");

$tmp->title('title', '<a href="'.$r['id'].'">'.$r['name'].'</a> / <a href="'.$r['id'].'/'.$s['id'].'">'.$s['name'].'</a> / '.$p['name'] . ((($p['kto'] == User::ID() || User::level() >= 2) && User::aut()) ? ($p['is_close_topic'] == 0 ? '<span><a href="/forum/topic'.$id.'?close">'.img('t_close.png').'</a></span>' : '<span><a href="/forum/topic'.$id.'?open">'.img('t_open.png').'</a></span>') : NULL));
User::panel();

if (!$p) $tmp->show_error();

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `forum_message` where `topic` = '".$id."'");

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

if(User::aut()){

if($p['kto'] == User::ID() || User::level() >= 2){
	if(isset($_GET['close'])){
		$db->query("update `forum_topic` set  `is_close_topic` = '1' where `id` = '".$id."' ");
		$db->query("insert into `forum_message` set `razdel` = '".$p['razdel']."', `section` = '".$p['section']."', `topic` = '".$id."', `kto` = '".User::ID()."', `message` = 'Тема закрыта!', `time` = '".time()."' ");
		header('location: /forum/topic'.$id);
	}

	if(isset($_GET['open'])){
		$db->query("update `forum_topic` set  `is_close_topic` = '0' where `id` = '".$id."' ");
		$db->query("insert into `forum_message` set `razdel` = '".$p['razdel']."', `section` = '".$p['section']."', `topic` = '".$id."', `kto` = '".User::ID()."', `message` = 'Тема открыта!', `time` = '".time()."' ");
		header('location: /forum/topic'.$id);
	}
}

if(User::level() >= 2){
	if(isset($_GET['edit'])){
		if($p['is_close_topic'] != 1){
			if(isset($_REQUEST['submit'])){		
				$name = $db->guard($_POST['name']);
				$message = $db->guard($_POST['message']);
				
				if(empty($name) || empty($message)) $error .= Language::config('no_message');

				if(!isset($error)){
					if($p['is_close_topic'] != 1)
						$db->query("UPDATE `forum_topic` SET `name` = '".$name."', `message` = '".$message."' WHERE `id` = '".$id."' ");
					header('location: /forum/topic'.$id);
				}
			}
		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').': <br/>
<input type="text" name="name" value="'.$p['name'].'" /><br/>
'.Language::config('message').':<br/>
<textarea name="message">'.$p['message'].'</textarea><br />
<input type="submit" name="submit" value="'.Language::config('edit').'" /></form>');
		} else {
			header('location: /forum/topic'.$id);
		}

		$tmp->back('forum/topic'.$id);
	}

	if(isset($_GET['del'])){
		if(isset($_GET['yes'])){
			$db->query("DELETE FROM `forum_topic` where `id` = '".$id."'");
			$db->query("DELETE FROM `forum_message` where `topic` = '".$id."'");
			header('location: /forum/'.$p['razdel'].'/'.$p['section']);
		}

 		$tmp->del_sure($p['name'], 'del&yes');
		$tmp->footer();
	}
}

if(User::level() >= 3){
	if(isset($_GET['top'])){
		$db->query("update `forum_topic` set  `is_top_topic` = '1' where `id` = '".$id."' ");
		$db->query("insert into `forum_message` set `razdel` = '".$p['razdel']."', `section` = '".$p['section']."', `topic` = '".$id."', `kto` = '".User::ID()."', `message` = 'Тема закреплена!', `time` = '".time()."' ");
		header('location: /forum/topic'.$id);
	}

	if(isset($_GET['notop'])){
		$db->query("update `forum_topic` set  `is_top_topic` = '0' where `id` = '".$id."' ");
		$db->query("insert into `forum_message` set `razdel` = '".$p['razdel']."', `section` = '".$p['section']."', `topic` = '".$id."', `kto` = '".User::ID()."', `message` = 'Тема откреплена!', `time` = '".time()."' ");
		header('location: /forum/topic'.$id);
	}
}

if(isset($_REQUEST['submit'])){
	
	Security::verify_str();

	$message = $db->guard($_POST['messages']);
	$ant=$db->fass("SELECT * FROM `forum_message` where `kto` = '".User::ID()."' and `message` = '".$message."' and `time` > '".(time() - 3)."' limit 1");

	if($ant) $error .= Language::config('error');
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

	if(!isset($error)){
		if($p['is_close_topic'] != 1){
			$db->query("INSERT INTO `forum_message` set `razdel` = '".$p['razdel']."', `section` = '".$p['section']."', `topic` = '".$id."', `kto` = '".User::ID()."', `message` = '".$message."', `time` = '".time()."' ");
			$lid=$db->insert_id();
			
			if(!empty($filename)){
			    copy($_FILES['file']['tmp_name'], $dir . '/' . $file );
				$db->query("INSERT INTO `forum_file` set `kto` = '".User::ID()."', `post_id` = '".$lid."', `thema` = '".$id."', `name` = '".$file."' ");
			}

			$fc=$db->fass_c("SELECT COUNT(*) as count FROM `forum_message` where `topic` = '".$id."' ");
			if ($fc > $num) {
				$pg = ceil($fc / $num);
				$st = '?page='.$pg;
			}else{
				$st = '';
			}

			if (User::ID() != $p['kto']) {
				User::new_notify($p['kto'], 'mess_forum||'.$p['name'], '/forum/topic'.$id.$st);
			}

			$db->query("UPDATE `forum_topic` set  `last_message_time` = '".time()."' where `id` = '".$id."' ");
			$db->query("UPDATE `users` set `money` = money + 5 where `id` = '".User::ID()."' ");
		}

		header('location: /forum/topic'.$id.$st);
	}
}
}

if(User::level() >= 2) {
	$tmp->div('adm_menu flex', '<a title="'.Language::config('edit').'" href="/forum/topic'.$id.'?edit">'.img('t_edit.png').'</a> '.(($p['is_top_topic'] == 0) ? '<a title="'.Language::config('top').'" href="/forum/topic'.$id.'?top">'.img('t_pin.png').'</a>' : '<a title="'.Language::config('no_top').'" href="/forum/topic'.$id.'?notop">'.img('t_unpin.png').'</a>'). ' <a title="'.Language::config('move').'" href="/forum/movep'.$id.'">'.img('t_move.png').'</a> <a title="'.Language::config('delet').'" href="/forum/topic'.$id.'?del">'.img('t_del.png').'</a>');
}


echo '<div class="messages"><div>'.nick_new($p['kto']).'<br>'.bb(smile($p['message'])).'</div></div>';

$f=$db->query("SELECT * FROM `forum_message` where `topic` = '".$id."' ORDER BY time ASC LIMIT ".$start.", ".$num." ");

if (!empty($f)) {

	echo '<hr><div class="messages">';
	while($fo=$f->fetch_assoc()){
		echo '<hr><div>'.nick_new($fo['kto']).' '.((User::aut() && $p['is_close_topic'] != 1 && ($fo['kto'] == User::ID() || User::level() >=3)) ? (($fo['time'] + 60 < time() && User::level() <= 2) ? NULL : '<a class="mkey" href="/forum/topic'.$id.'/edit_post'.$fo['id'].'">'.img('ed.png').'</a>') .(User::level() >=3 ? '<a class="de" href="/forum/topic'.$id.'/delete'.$fo['id'].'">'.img('delete.png').'</a>' : NULL) : NULL).'<span class="times">'. times($fo['time']).'</span>';

		echo ((User::aut() && $fo['kto'] != User::ID() && $p['is_close_topic'] != 1) ? ' <a class="answer" href="/forum/topic'.$id.'/replay'.$fo['id'].'">'.img('answer.png').'</a> <a class="answer" href="/forum/topic'.$id.'/quote'.$fo['id'].'">'.img('cit.png').'</a>' : NULL );

		echo ($fo['quote'] ? '<div class="cit"><span class="rep">'.$fo['user_quote'].'</span> '.bb(smile(mb_strimwidth($fo['quote'], 0, 140, "..."))).'</div>'.bb(smile($fo['message'])) : '<br>'.bb(smile($fo['message'])));

		$filec = $db->n_r("select id from `forum_file` where `post_id` = '".$fo['id']."' limit 1");
		if($filec){
			$file = $db->query("select * from `forum_file` where `post_id` = '".$fo['id']."'");
			echo '<div class="files">';
			while($files = $file->fetch_assoc()){
				if($files['post_id'] == $fo['id'])
					echo  '<a href="/files/forum/'.$files['name'].'">'.img('down_s.png').' '.$files['name'].' | '.format_filesize(R.'/files/forum/'.$files['name']).'</a><br>';
			}
			echo '</div>';
		}
		echo '</div>';
	}
	echo '</div>';

}


if(User::aut()){
	if($p['is_close_topic'] != 1){
		error($error);
		bbcode();

		echo '<div class="main"><form method="POST" name = "message" action="" enctype="multipart/form-data">
'.Language::config('message').':<br/>
<textarea name="messages"></textarea><br />
<input name="file" type="file" id="file" onchange="uploadFile(this)">
<label class="select_file" for="file">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('send').'" /></form></div>';
	}
}

page('?');

$tmp->div('menu bm flex', '<a href="/forum/'.$p['razdel'].'/'.$p['section'].'">'.img('link.png').' '.Language::config('back').'</a><a href="/">'.img('link.png').' '.Language::config('home').'</a>');
$tmp->footer();
?>