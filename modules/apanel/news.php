<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('news');
$tmp->title('title', Language::config('news'));
User::panel();

if(User::level() < 3){
	go_exit();
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `news`");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$n=$db->query("select * from `news` ORDER BY time DESC LIMIT ".$start.", ".$num."");

if(isset($_GET['del'])) {
	$del = $db->guard($_GET['del']);
	if(User::profile('level') >=3) 
		$db->query("DELETE FROM `news` where `id` ='".$del."'");
	header('location: /apanel/news');
}

if(isset($_GET['edit'])){
	$id_n = isset($_GET['edit']) ? my_int($_GET['edit']) : null;
	$p = $db->fass("SELECT * FROM `news` where `id` = '".$id_n."' ");
	
	if (!$p) $tmp->show_error();

	if (isset($_REQUEST['edit_news'])) {
		$name = $db->guard($_POST['name']);
		$message = $db->guard($_POST['message']);
		
		Security::verify_str();  


		if (empty($_POST['name']) || empty($_POST['message'])) $error .= Language::config('no_message');

		if(!isset($error)) {
			$db->query("UPDATE `news` set `name` = '".$name."', `message` = '".$message."' where `id` ='".$id_n."' ");
			header('location: /apanel/news');
		}
	}

	error($error);

	$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').':<br/>
<input type="text" name="name" value="'.$p['name'].'"><br />
'.Language::config('message').':<br/>
<textarea name="message">'.$p['message'].'</textarea><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="edit_news" value="'.Language::config('edit').'" /></form>');

	$tmp->back('apanel/news');
}

if(isset($_REQUEST['submit'])) {
	$name = $db->guard($_POST['name']);
	$message = $db->guard($_POST['message']);
			
	Security::verify_str();  

	if (empty($_POST['name']) || empty($_POST['message'])) $error .= Language::config('no_message');

	if (!isset($error)) {
		$db->query("INSERT INTO `news` set `kto` = '".User::ID()."', `name` = '".$name."', `message` = '".$message."', `time` = '".time()."' ");
		header('location: /apanel/news');

	}
}
	
error($error);

$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').':<br />
<input type="text" name="name"><br />
'.Language::config('message').':<br/>
<textarea name="message"></textarea><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('send').'" /></form>');

if(!$posts){
	$tmp->div('main', Language::config('no_news'));
} else {
	while($news=$n->fetch_assoc()) {
		echo '<hr><div class="news"><div>'.nick_new($news['kto']).'<span> '.((User::level() >=3) ? '<a class="mkey" href="./news/edit'.$news['id'].'">'.img('ed.png').'</a>' : NULL) .'</span>'.((User::profile('level') >=3) ? ' <a class="de" href="/apanel/news/del'.$news['id'].'">'.img('delete.png').'</a>' : NULL).'<span class="times">'. times($news['time']).'</span><br><span class="news_title">'. $news['name'].'</span>' .bb(smile($news['message'])).'</div></div>';
	}

}

page('?');

$tmp->back('apanel');
?>