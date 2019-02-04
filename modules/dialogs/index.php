<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('dialogs');
$tmp->title('title', Language::config('dialogs'));
User::panel();

if(!User::aut()){
    go_exit();
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `dialogs` where `kto` = '".User::ID()."' and `time_last` > 0");

if(!$posts){
	$tmp->div('main', Language::config('no_dialogs'));
    $tmp->home();
}

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$d=$db->query("select * from `dialogs` where `kto` = '".User::ID()."' ORDER BY time_last DESC LIMIT ".$start.", ".$num."");

echo '<div class="dialogs">';
while ($di=$d->fetch_assoc()) {
    if ($di['time_last']) {
        $msc_new = $db->fass_c("SELECT COUNT(*) as count FROM `dialogs_message` WHERE `komy` = '".User::ID()."' and `kto` = '".$di['komy']."' and `readln` = '0'");
        $msc = $db->fass_c("SELECT COUNT(*) as count FROM `dialogs_message` WHERE `komy` = '".User::ID()."' and `kto` = '".$di['komy']."' or `komy` = '".$di['komy']."' and `kto` = '".User::ID()."'");

        echo '<a href="/dialogs/dialogs'.$di['komy'].'">'.(($msc_new > 0) ? img('mail_n.png') : img('mail_i.png')).''.nick_new($di['komy'], true).' <b>'.$msc.'</b> '.(($msc_new > 0) ? '<span class="new">+ '.$msc_new.'</span>' : NULL).' <span class="times">'.times($di['time_last']).'</span> </a>';
    }
}
echo '</div>';

page('?');

$tmp->home();
?>