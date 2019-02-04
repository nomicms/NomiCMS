<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('lib');
$tmp->title('title', Language::config('lib') . (User::level() >= 3 ? '<span><a href="/lib/?d">'.img('add_c.png').'</a></span>' : NULL));
User::panel();

$id = (empty($_GET['d']) ? null : my_int($_GET['d']));

if(isset($_GET['d'])){
	if(User::level() >= 3){
		if(isset($_REQUEST['submit'])){
			$name = $db->guard($_POST['name']);
			if(mb_strlen($_POST['name'], 'UTF-8')<2) $error .= Language::config('error');
			
			if(!isset($error)) {
				$db->query("insert into `lib_category` set `name` = '".$name."',  `time` = '".time()."' ");
				header('location: /lib');
			}
		}
	}
	error($error);

	$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').':<br/>
<input name="name" value="'. out($_POST['name']) .'" /><br />
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
	$tmp->back('lib');
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `lib_category`");

if($posts==0){
	$tmp->div('main', Language::config('no_libl_category'));
	$tmp->footer();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$lib=$db->query("SELECT * FROM `lib_category` ORDER BY id ASC LIMIT ".$start.", ".$num." ");

echo '<div class="menu">';
while($l=$lib->fetch_assoc()){
	$lc=$db->fass_c("SELECT COUNT(*) as count FROM `lib_r` where  `category` = '".$l['id']."' ");
	echo '<hr><a href="/lib/c'.$l['id'].'">'.img('ct.png').' '.$l['name'].' <span>'.$lc.'</span></a>';
}
echo '</div>';

page('?');

$tmp->home();
?>