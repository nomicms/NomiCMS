<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('cabinet');
$tmp->title('title', Language::config('cabinet'));
User::panel();

if(!User::aut()){
	go_exit();
}

$tmp->div('menu', '<span class="fmenu">
<a href="/journal">'.img('pnotify.png').' '.Language::config('journal').'</a>
<a href="/friends'.User::ID().'">'.img('users.png').' '.Language::config('friends').'</a>
<a href="/us'.User::ID().'">'.img('puser.png').' '.Language::config('my_profile').'</a>
<a href="/edit">'.img('pedit.png').' '.Language::config('edit_profile').'</a>
<a href="/settings">'.img('psettings.png').' '.Language::config('my_settings').'</a>
<a href="/exit">'.img('pexit.png').' '.Language::config('exit').'</a>
</span>');

$tmp->footer();

?>