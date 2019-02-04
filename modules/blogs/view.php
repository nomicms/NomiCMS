<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('blogs');
$tmp->title('title', Language::config('blogs'));
User::panel();

$id = isset($_GET['id']) ? $db->guard($_GET['id']) : NULL;
$p = $db->fass("select * from `blogs` where `id` = '".$id."'");

if (!$p) $tmp->show_error();

if(isset($_GET['del'])){
	(User::level() >=3 || User::ID() == $p['kto']) ? $db->query("delete from `blogs` where `id` = '".$p['id']."' limit 1 "). header('location: /blogs') : NULL;
}

if(isset($_GET['edit'])){
	if(User::level() >= 3 || User::ID() == $p['kto']){

		if(isset($_POST['submit'])){
			$name = $db->guard($_POST['name']);
			$text = $db->guard($_POST['text']);
			
			if(empty($name)) $error .= Language::config('no_empty_name')."<br/>";
			if(empty($text)) $error .= Language::config('error')."<br/>";

			if(!isset($error)){
				$db->query("update `blogs` set `name` = '".$name."', `text` = '".$text."', `time` = '".time()."' where `id` = '".$p['id']."' ");
				header('location: /blogs/view'.$p['id']);
			}
		}

		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('name_blog').': [100]<br/>
<input type="text" name="name" value="'. $p['name'] .'" /><br/>
'.Language::config('text').': <br/>
<textarea name="text">'.$p['text'].'</textarea><br/>
<input type="submit" name="submit" value="'.Language::config('edit').'" /></form>');
		$tmp->back('blogs/view'.$id);
	}
}

$tmp->div('title', bb(smile($p['name'])));
echo '<hr><div class="main">'.bb(smile($p['text'])).'</div><hr><div class="main">'.Language::config('avtor').': '.nick_new($p['kto']).' '.(User::level() >= 3 || User::ID() == $p['kto'] ? '<a class="de" href="/blogs/view'.$p['id'].'?del">'.img('delete.png" style="width: inherit').'</a> <a class="de" href="/blogs/view'.$p['id'].'?edit">'.img('edit.png" style="width: inherit').'</a>' : NULL).' <span class="times">'.times($p['time']).'</span></div><hr>';

$count = $db->fass_c("select COUNT(*) as count from `blog_comms` where `blog_id` = '".$p['id']."' ");
$tmp->div('menu', '<a href="/blogs/comment'.$p['id'].'">'.img('com.png').' '.Language::config('comments').' <span>'.$count.'</span></a>');

$tmp->back('blogs');
?>