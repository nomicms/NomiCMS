<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('zc');
$tmp->title('title', Language::config('zc'). (User::level() >= 3 ? '<span><a href="/zc/?d">'.img('add_c.png').'</a></span>' : NULL));
User::panel();


$zn = $db->fass_c("SELECT COUNT(*) as count FROM `zc_file` where `time` > '".(time() - 86400)."'");


if(User::aut()){
	if(isset($_GET['d'])){
		if(User::level() >= 3){

			if(isset($_REQUEST['submit'])){
				$name = $db->guard($_POST['name']);
				if(mb_strlen($_POST['name'], 'UTF-8')<2) $error .= Language::config('no_message');
				
				if(!isset($error)) {
					$db->query("insert into `zc_category` set `name` = '".$name."', `opis` = '', `time` = '".time()."' ");
					header('location: /zc');
				}
			}

		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').':<br/>
<input name="name" value="'.out($_POST['name']).'" /><br />
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
		$tmp->back('zc');
		}
	}
}



if(isset($_GET['new'])){
	if(User::aut()){
		if ($zn) {
			$tmp->title('title', Language::config('new_files'));

			$z = $db->query("SELECT * FROM `zc_file` where `time` > '".(time() - 86400)."' ORDER BY id DESC LIMIT 10 ");

			echo '<div class="menu">';
			while($zcn=$z->fetch_assoc()){
				echo '<hr><a href="file'.$zcn['id'].'">'.file_icon($zcn['file']).' '.$zcn['name'].' </a>';
			}
			echo '</div>';
		} else {
			$tmp->div('main', Language::config('no_zc_r'));
		}

		$tmp->back('zc');
	} else {
		$tmp->need_auth('zc');
	}
}


$posts=$db->fass_c("SELECT COUNT(*) as count FROM `zc_category`");

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

$tmp->div('menu', '<a class="items" href="zc/new">'.img('new_files.png').' '.Language::config('new_files').' '.(($zn != 0) ? '<label class="new_items">+ '.$zn.'</label>' : NULL).'</a>');

$zc=$db->query("SELECT * FROM `zc_category` ORDER BY id ASC LIMIT ".$start.", ".$num." ");

echo '<div class="menu">';
while($z=$zc->fetch_assoc()){
	echo '<hr><a href="/zc/cat'.$z['id'].'">'.img('ct.png').' '.$z['name'].'</a>';
}
echo '</div>';

page('?');

$tmp->home();
?>