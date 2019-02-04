<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('new_blog');
$tmp->title('title', Language::config('new_blog'));
User::panel();
 
if(User::aut()){

	if(isset($_POST['submit'])){
		$name = $db->guard($_POST['name']);
		$text = $db->guard($_POST['text']);
		
		if(empty($name)) $error .= Language::config('no_empty_name')."<br/>";
		if(empty($text)) $error .= Language::config('error')."<br/>";

		if(!isset($error)){
			$db->query("insert into `blogs` set `name` = '".$name."', `text` = '".$text."', `kto` = '".User::ID()."', `time` = '".time()."'  ");
			$last_id = $db->insert_id();
			header('location: /blogs/view'.$last_id);
		}
		
	}

	error($error);
$_POST['name'] = (empty($_POST['name']) ? null : $_POST['name']);
$_POST['text'] = (empty($_POST['text']) ? null : $_POST['text']);

	$tmp->div('main', '<form method="POST" action="">
	'.Language::config('name_blog').': [100]<br/>
	<input type="text" name="name" value="'. out($_POST['name']) .'" /><br/>
	'.Language::config('text').': <br/>
	<textarea name="text">'. out($_POST['text']) .'</textarea><br />
	<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
	$tmp->div('menu', '<hr><a href="/blogs">'.img('link.png').' '.Language::config('back').'</a>');
} else {
	header('location: /');
}

$tmp->footer();
?>