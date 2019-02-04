<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');

$id=my_int($db->guard($_GET['id']));
$p=$db->fass("select * from `forum_razdel` where `id` = '".$id."' ");

$tmp->title('title', '<a href="/forum">'.Language::config('forum').'</a> / '.$p['name']. (User::level() >= 3 ? '<span><a href="/forum/'.$id.'?r">'.img('add_c.png').'</a><a href="/forum/'.$id.'?edit">'.img('edit_c.png').'</a><a href="/forum/'.$id.'?d">'.img('del_c.png').'</a></span>' : NULL));
User::panel();

if (!$p) $tmp->show_error();

if(User::level() >=3){

	if(isset($_GET['r'])){
		
		if(isset($_REQUEST['submit'])){
			$name = $db->guard($_POST['name']);
			$pos = $db->guard($_POST['pos']);
			
			if(empty($name) || empty($pos)) $error .= Language::config('error');

			if(!isset($error)){
				$db->query("insert into `forum_section` set `name` = '".$name."', `razdel` = '".$id."', `pos` = '".$pos."'");
				$lid = $db->insert_id();
				header('location: /forum/'.$id);
			}
		}

		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').':<br/>
<input type="text" name="name" value="'.out($_POST['name']).'" /><br/>
'.Language::config('pos').':<br/>
<input type="text" name="pos" value="'.out($_POST['pos']).'" style="width: 60px" /><br/>
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
   		$tmp->back('forum/'.$id);
	}

	if(isset($_GET['edit'])){
		if(isset($_REQUEST['submit'])) {					
			$name = $db->guard($_POST['name']);
			$pos = $db->guard($_POST['pos']);
		
			if(empty($name) || empty($pos)) $error .= Language::config('error');

			if(!isset($error)) {
				$db->query("update `forum_razdel` set  `name` = '".$name."', `pos` = '".$pos."' where `id` = '".$id."' ");
				header('location: /forum/'.$p['razdel']);
			}
		}

		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').': <br/>
<input type="text" name="name" value="'.$p['name'].'" /><br/>
'.Language::config('pos').':<br/>
<input type="text" name="pos" value="'.$p['pos'].'" style="width: 60px" /><br/>
<input type="submit" name="submit" value="'.Language::config('save').'" /></form>');
   		$tmp->back('forum/'.$id);
	}

	if(isset($_GET['d'])) {
		if(isset($_GET['yes'])){
			$db->query("DELETE FROM `forum_razdel` where `id` = '".$id."' limit 1 ");
			$db->query("DELETE FROM `forum_section` where `razdel` = '".$id."'");
			$db->query("DELETE FROM `forum_topic` where `razdel` = '".$id."' ");
			$db->query("DELETE FROM `forum_message` where `razdel` = '".$id."'");
			header('location: /forum');
		}

		$tmp->del_sure($p['name'], 'd&yes');
		$tmp->footer();
	}
	
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `forum_section` where `razdel` = '".$id."'");

if($posts==0){
   $tmp->div('main', Language::config('no_sections'));
   $tmp->back('forum');
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;


$f=$db->query("SELECT * FROM `forum_section` where `razdel` = '".$id."' ORDER BY id ASC LIMIT ".$start.", ".$num." ");

echo '<div class="menu">';
while($fo=$f->fetch_assoc()){
	echo '<hr><a href="/forum/'.$fo['razdel'].'/'.$fo['id'].'">'.img('cti.png').' '.$fo['name'].'</a>';
}
echo '</div>';

page('?');

$tmp->back('forum');
?>