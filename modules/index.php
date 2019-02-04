<?php

$tmp->header('home');
$tmp->title('title', Language::config('home'));
User::panel();


$nc = $db->n_r("SELECT id FROM `news` LIMIT 1");
if($nc){
	$n = $db->fass("select * from `news` ORDER BY time DESC LIMIT 1");
	$count = $db->fass_c("SELECT COUNT(*) as count FROM `news_comments` where `news` = '".$n['id']."'");
	$tmp->div('news', '<div><span class="news_title">'.$n['name'].' <span class="nt">'.times($n['time']).'</span></span>'.bb(smile($n['message'])).'<span class="news_comm"><a href="/news/comment'.$n['id'].'">'.img('com.png').' '.$count.'</a></span></div>');
}

echo '<div class="menu"><a class="items" href="/news">'.img('link.png').' '.Language::config('news').'</a>';

$c = $db->fass_c("SELECT COUNT(*) as count FROM `chat`");
$b = $db->fass_c("SELECT COUNT(*) as count FROM `blogs`");
$cn = $db->fass_c("SELECT COUNT(*) as count FROM `chat` where `time` > '".(time() - 6400)."'");
$fs = $db->fass_c("SELECT COUNT(*) as count FROM `forum_section`");
$l = $db->fass_c("SELECT COUNT(*) as count FROM `lib_category`");
$lr = $db->fass_c("SELECT COUNT(*) as count FROM `lib_r`");
$u = $db->fass_c("SELECT COUNT(*) as count FROM `users`");

$ft = $db->fass_c("SELECT COUNT(*) as count FROM `forum_topic`");
echo '<a href="/forum">'.img('forum.png').' '.Language::config('forum').' <span>'.$fs.'/'.$ft.'</span></a>';

if($ft){
	$ft2=$db->query("SELECT * FROM `forum_topic` ORDER BY `is_top_topic` = '0', `last_message_time` DESC LIMIT 5");
	
	while($f=$ft2->fetch_assoc()){
		if($f['is_close_topic'] == 1){
			$icon = img('forum_close.png');
		}elseif($f['is_top_topic'] == 1){
			$icon = img('forum_pin.png');
		} else {
			$icon = img('forum_topic.png');
		}

		$fc=$db->fass_c("SELECT COUNT(*) as count FROM `forum_message` where `topic` = '".$f['id']."'");
		if ($fc > $num) {
			$pg = ceil($fc / $num);
			$st = '?page='.$pg;
		}
		
		$st = (empty($st) ? null : $st);

		echo '<div class="forum_topic flex"><a class="items topic" href="/forum/topic'.$f['id'].'">'.$icon.' '.$f['name'].'</a><a class="items" href="/forum/topic'.$f['id'].$st.'"><span>'.$fc.'</span></a></div>';
	}
}

$z=$db->fass_c("SELECT COUNT(*) as count FROM `zc_file`");
echo '<a href="/zc">'.img('dload.png').' '.Language::config('zc').' <span>'.$z.'</span></a>'; 

if($z!=0){
	$zc=$db->query("SELECT * FROM `zc_file` ORDER BY `pin` = '0', `hide` = '1', `time` DESC LIMIT 5");
	while($z=$zc->fetch_assoc()){
		echo '<a class="items topic" href="/zc/file'.$z['id'].'">'.file_icon($z['file']).' '.$z['name'].'</a>';
	}
}

echo '<span class="fmenu"><a href="/lib">'.img('library.png').' '.Language::config('lib').' <span>'.$l.'/'.$lr.'</span></a>
<a href="/blogs">'.img('blog.png').' '.Language::config('blogs').' <span>'.$b.'</span></a>
<a href="/chat">'.img('chat.png').' '.Language::config('chat').' <span>'.$c.'</span>'.(($cn != 0) ? '<span>+ '.$cn.'</span>' : NULL ).'</a>
<a href="/people">'.img('users.png').' '.Language::config('users').' <span>'.$u.'</span></a>
<a href="/pages">'.img('helps.png').' '.Language::config('faq').' </a>';

echo '</span></div>';

$tmp->footer();
?>