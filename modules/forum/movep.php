<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('forum');

$id = my_int($_GET['id']);


$section = (empty($_GET['section']) ? null : $_GET['section']);
$razdel = (empty($_GET['razdel']) ? null : $_GET['razdel']);


$p=$db->fass("select * from `forum_topic` where `id` = '".$id."' ");

$tmp->title('title', Language::config('move').' - '.$p['name']);
User::panel();

if (!$p) $tmp->show_error();

if(!User::aut()){
	go_exit();
}

if(User::aut() && User::level() >= 2){
	$forum_r = $db->query("SELECT * FROM `forum_razdel` ORDER BY `id` DESC");

	while($a=$forum_r->fetch_assoc()){
		$tmp->div('main', '<span class="blog_title">'.$a['name'].'</span>');
	    
	    $forum_s=$db->query("SELECT * FROM `forum_section` WHERE `razdel` = '".$a['id']."' ORDER BY `id` DESC");
	    while($a=$forum_s->fetch_assoc()) {
			$tmp->div('menu', '<a href="/forum/move'.$id.'/section'.$a['id'].'/razdel'.$a['razdel'].'">'.img(''.($p['section'] == $a['id'] ? 't_move' : 'cti').'.png').' '.$a['name'].'</a>');
	    }
	    echo '<hr>';
	}
}

$tmp->div('menu', '<a href="/forum/topic'.$id.'">'.img('link.png').' '.Language::config('back').'</a>');
$tmp->footer();
?>