<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('faq');
$tmp->title('title', Language::config('faq'));

User::panel();

$tmp->div('menu', '<span class="fmenu"><a href="/pages/smile">'.img('smile.png').' '.Language::config('smiles').'</a> <a href="/pages/bb_codes">'.img('bb.png').' '.Language::config('bb_codes').'</a><a href="/pages/ads">'.img('dv.png').' '.Language::config('ads').'</a></span>');

$tmp->div('menu', '<a href="/">'.img('link.png').' '.Language::config('home').'</a>');
$tmp->footer();
?>