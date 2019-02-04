<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');
$tmp->title('title', Language::config('forum'). (User::level() >= 3 ? '<span><a href="/forum?r">'.img('add_c.png').'</a></span>' : NULL));

User::panel();

if(User::level() >=3){
	if(isset($_GET['r'])){
		
		if(isset($_REQUEST['submit'])){
			$name = $db->guard($_POST['name']);
			$pos = $db->guard($_POST['pos']);
			
			if(empty($name) || empty($pos)) $error .= Language::config('error');

			if(!isset($error)){
				$db->query("insert into `forum_razdel` set `name` = '".$name."', `pos` = '".$pos."' ");
				header('location: /forum');
			}
		}

		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').':<br/>
<input type="text" name="name" value="'.out($_POST['name']).'" /><br/>
'.Language::config('pos').':<br/>
<input type="text" name="pos" value="'.out($_POST['pos']).'" style="width: 60px" /><br/>
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
		$tmp->div('menu', '<hr><a href="/forum">'.img('link.png').' '.Language::config('back').'</a>');
		$tmp->footer();
   		exit();
	}
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `forum_razdel`");

if($posts==0){
   $tmp->div('main', Language::config('no_razdels'));
   $tmp->footer();
   exit();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;


$f= $db->query("SELECT * FROM `forum_razdel` ORDER BY pos ASC LIMIT ".$start.", ".$num."");

echo '<div class="menu">';
while($fo=$f->fetch_assoc()){
	echo '<hr><a href="/forum/'.$fo['id'].'">'.img('ct.png').' '.$fo['name'].'</a>';
}
echo '</div>';

page('?');

$tmp->div('menu', '<hr><a href="/">'.img('link.png').' '.Language::config('home').'</a>');
$tmp->footer();
?>