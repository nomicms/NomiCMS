<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('lib');

$id=my_int($db->guard($_GET['id']));
$p=$db->fass("select * from `lib_r` where `id` = '" .$id."'");

$tmp->title('title', Language::config('lib'). ' - ' .$p['name']);
User::panel();

if (!$p) $tmp->show_error();

$db->query("UPDATE `lib_r` set `look` = look + 1 where `id` = '".$id."'");

if(isset($_GET['d'])){
	if(User::ID() == $p['kto'] || User::level() >= 3){
		$db->query("DELETE FROM `lib_r` where `id` = '".$id."' LIMIT 1 ");
		header('location: /lib/c'.my_int($db->guard($_GET['cid'])));
	}
}

if(isset($_GET['edit'])){
	if(User::ID() == $p['kto'] || User::level() >= 3){

		if(isset($_POST['submit'])){
			$name = $db->guard($_POST['name']);
			$message = $db->guard($_POST['message']);

			if(mb_strlen($_POST['name'], 'UTF-8')<2) $error .= Language::config('error');

			if(!isset($error)) {
				$db->query("UPDATE `lib_r` set `name` = '".$name."', `message` = '".$message."' where `id` ='".$id."' ");
				header('location: /lib/c/l'.$id);
			}
		}

		error($error);

		$tmp->div('main', '<form action="" method="post">
'.Language::config('name').':<br/>
<input name="name" value="'.$p['name'].'" /><br />
'.Language::config('opis').'  :<br/>
<textarea name="message">'.$p['message'].'</textarea><br/>
<input type="submit" name="submit" value="'.Language::config('save').'" /></form>');

		$tmp->back('lib/c/l'.$id);
	}
}

$tmp->div('title', bb(smile($p['name'])));
echo ($p['message'] ? '<hr><div class="main">'. bb(smile($p['message'])) . '</div>' : NULL );

$tmp->div('menu', '<a class="items" href="/files/lib/'.$p['txt'].'">'.img('down.png').' '.Language::config('down').' ('.format_filesize(R.'/files/lib/'.$p['txt']).')</a>');

$tmp->div('main', Language::config('add_name').': '.nick_new($p['kto']).' '.(User::level() >= 3 || User::ID() == $p['kto'] ? '<a class="de" href="/lib/c'.$p['category'].'/l'.$p['id'].'?d">'.img('delete.png" style="width: inherit').'</a> <a class="de" href="/lib/c/l'.$p['id'].'?edit">'.img('edit.png" style="width: inherit').'</a>' : NULL).' <span class="times">'.times($p['time']).'</span><br>'.Language::config('look').': '.$p['look']);

$count=$db->fass_c("SELECT COUNT(*) as count FROM `lib_comments` where `lib_r` = '".$id."' ");
$tmp->div('menu', '<hr><a href="/lib/comment'.$p['id'].'">'.img('com.png').' '.Language::config('comments').' <span>'.$count.'</span></a>');

$tmp->back('lib/c'.$p['category']);
?>