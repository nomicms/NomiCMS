<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('bb_codes');
$tmp->title('title', Language::config('bb_codes'));
User::panel();

$tmp->div('main', '<div class="helps_table">' .
'<span>[b]</span> '.Language::config('bb_text').' <span>[/b]</span> - <b>'.Language::config('bb_text').' </b><hr>' .
'<span>[u]</span> '.Language::config('bb_text').' <span>[/u]</span> - <u>'.Language::config('bb_text').' </u><hr>' .
'<span>[i]</span> '.Language::config('bb_text').' <span>[/i]</span> - <i>'.Language::config('bb_text').' </i><hr>' .
'<span>[s]</span> '.Language::config('bb_text').' <span>[/s]</span> - <s>'.Language::config('bb_text').' </s><hr>' .
'<span>[code]</span> '.Language::config('bb_program_code').' <span>[/code]</span> - <code>'.Language::config('bb_program_code').' </code><hr>' .
'<span>[cit]</span> '.Language::config('bb_text').' <span>[/cit]</span> - <div style="display: inline" class="cit">'.Language::config('bb_text').' </div><hr>' .
'<span>[red]</span> '.Language::config('bb_text').' <span>[/red]</span> - <label style="color: #f44336">'.Language::config('bb_text').' </label><hr>' .
'<span>[green]</span> '.Language::config('bb_text').' <span>[/green]</span> - <label style="color: #81c136">'.Language::config('bb_text').' </label><hr>' .
'<span>[blue]</span> '.Language::config('bb_text').' <span>[/blue]</span> - <label style="color: #2196f3">'.Language::config('bb_text').' </label><hr>' .
'<span>[color='.Language::config('bb_color').']</span> '.Language::config('bb_text').' <span>[/color]</span> - <label style="color: springgreen">'.Language::config('bb_text').' </label><hr>' .
'<span>[bg='.Language::config('bb_color').']</span> '.Language::config('bb_text').' <span>[/bg]</span> - <label style="background-color: crimson"> '.Language::config('bb_text').' </label><hr>' .
'<span>[url='.Language::config('bb_link_link').']</span> '.Language::config('bb_link_name').' <span>[/url]</span> - <a class="link_visual" href="http://site.com"> '.Language::config('bb_link_name').' </a><hr>' .
'<span>http://'.Language::config('bb_link').'</span> - <a class="link_visual" href="http://nomicms.ml">http://site.com</a><hr>'.
'<span>[img]</span> http://... <span>[/img]</span> - <img style="padding: 0" src="../design/images/nomicms.jpg" alt="*">'.
'</div>');

$tmp->back('pages');
?>