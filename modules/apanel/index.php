<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('apanel');
$tmp->title('title', Language::config('apanel'));
User::panel();

if(User::level() < 2){
	go_exit();
}

$n=$db->fass_c("SELECT COUNT(*) as count FROM `news`");
$a=$db->fass_c("SELECT COUNT(*) as count FROM `ads` where `time_end` > '".time()."'");
$b=$db->fass_c("SELECT COUNT(*) as count FROM `ban` where `time_end` > '".time()."'");

$cn = $db->fass_c("SELECT COUNT(*) as count FROM `admin_chat` where `time` > '".(time() - 6400)."'");
$c = $db->fass_c("SELECT COUNT(*) as count FROM `admin_chat`");

$tmp->div('menu', '<span class="fmenu">
<a href="/apanel/news">'.img('news.png').' '.Language::config('news').' <span>'.$n.'</span></a>
<a href="/apanel/admin_chat">'.img('chat.png').' '.Language::config('admin_chat').' <span>'.$c.'</span>'.(($cn != 0) ? '<span>+ '.$cn.'</span>' : NULL ).'</a>
<a href="/apanel/ads_list">'.img('dv.png').' '.Language::config('ads_list').' <span>'.$a.'</span></a>
<a href="/apanel/ban_list/">'.img('banned.png').' '.Language::config('ban').' <span>'.$b.'</span></a>
<a href="/apanel/settings">'.img('settings.png').' '.Language::config('settings').'</a>
<a href="/apanel/content">'.img('content.png').' '.Language::config('content').'</a>
</span>');

$tmp->footer();
?>