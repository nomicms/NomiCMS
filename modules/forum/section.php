<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');

$id=my_int($db->guard($_GET['id']));
$p=$db->fass("select * from `forum_section` where `id` = '".$id."' ");
$r=$db->fass("select * from `forum_razdel` where `id` = '".$p['razdel']."'");

$tmp->title('title', '<a href="/forum">'.Language::config('forum').'</a> / <a href="/forum/'.$r['id'].'">'.$r['name'].'</a> / '.$p['name']. (User::level() >= 3 ? '<span><a href="/forum/'.$p['razdel'].'/'.$id.'?edit">'.img('edit_c.png').'</a><a href="/forum/'.$p['razdel'].'/'.$id.'?d">'.img('del_c.png').'</a></span>' : NULL));

User::panel();

if (!$p) $tmp->show_error();

if(User::level() >=3){

	if(isset($_GET['edit'])){
		if(isset($_REQUEST['submit'])){					
			$name = $db->guard($_POST['name']);
			$pos = $db->guard($_POST['pos']);
			
			if(empty($name) || empty($pos)) $error .= Language::config('error');
			
			if(!isset($error)) {
				$db->query("update `forum_section` set  `name` = '".$name."', `pos` = '".$pos."' where `id` = '".$id."' ");
				header('location: /forum/'.$p['razdel'].'/'.$p['id']);
			}
		}

		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').': <br/>
<input type="text" name="name" value="'. $p['name'] .'" /><br/>
'.Language::config('pos').':<br/>
<input type="text" name="pos" value="'.$p['pos'].'" style="width: 60px" /><br/>
<input type="submit" name="submit" value="'.Language::config('save').'" /></form>');
   		$tmp->back('forum/'.$p['razdel'].'/'.$p['id']);
	}

	if(isset($_GET['d'])) {
		if(isset($_GET['yes'])){
			$db->query("DELETE FROM `forum_section` where `id` = '".$id."' limit 1");
			$db->query("DELETE FROM `forum_topic` where `section` = '".$id."'");
			$db->query("DELETE FROM `forum_message` where `section` = '".$id."'");
			header('location: /forum/'.$p['razdel']);
		}

		$tmp->del_sure($p['name'], 'd&yes');
		$tmp->footer();
	}

}


if(isset($_GET['nt'])){
	if(isset($_REQUEST['submit'])){
		Security::verify_str();
		
		$name = $db->guard($_POST['name']);
		$message = $db->guard($_POST['message']);
		
		if(empty($name) || empty($message)) $error .= Language::config('no_message');
		
		if(!isset($error)){
			$db->query("insert into `forum_topic` set `razdel` = '".$p['razdel']."', `section` = '".$p['id']."', `kto` = '".User::ID()."', `name` = '".$name."', `message` = '".$message."', `time` = '".time()."', `last_message_time` = '".time()."' ");
			$lid = $db->insert_id();
			header('location: /forum/topic'.$lid);
		}
	}

	error($error);
$_POST['message'] = (empty($_POST['message']) ? null : $_POST['message']);
$_POST['name'] = (empty($_POST['name']) ? null : $_POST['name']);


	$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').':<br/>
<input type="text" name="name" value="'.out($_POST['name']) .'" /><br/>
'.Language::config('message').':<br/>
<textarea name="message">'.out($_POST['message']).'</textarea><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
	$tmp->back('forum/'.$p['razdel'].'/'.$p['id']);
}
	

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `forum_topic` where `section` = '".$id."' ");

if($posts==0){
	$tmp->div('main', Language::config('no_topics'));
	if(User::aut()){
		$tmp->div('menu', '<a class="items" href="/forum/'.$p['razdel'].'/'.$p['id'].'?nt">'.img('new_topic.png').' '.Language::config('new_topic').'</a>');
	}
	$tmp->back('forum/'.$p['razdel']);
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;


$f=$db->query("SELECT * FROM `forum_topic` where `section` = '".$id."' ORDER BY id and `is_top_topic` = '1' desc LIMIT ".$start.", ".$num." ");

if(User::aut()){
    $tmp->div('menu', '<a class="items" href="/forum/'.$p['razdel'].'/'.$p['id'].'?nt">'.img('new_topic.png').' '.Language::config('new_topic').'</a>');
}

echo '<div class="menu">';
while($fo=$f->fetch_assoc()) {
	if($fo['is_close_topic'] == 1){
		$icon = img('forum_close.png');
	}elseif($fo['is_top_topic'] == 1){
		$icon = img('forum_pin.png');
	} else {
		$icon = img('forum_topic.png');
	}

	$c=$db->fass_c("SELECT COUNT(*) as count FROM `forum_message` where `topic` = '".$fo['id']."'");
	echo '<hr><a href="/forum/topic'.$fo['id'].'">'.$icon.' '.$fo['name'].' <span>'.$c.'</span></a>';
}
echo '</div>';


page('?');

$tmp->back('forum/'.$p['razdel']);
?>