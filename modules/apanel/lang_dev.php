<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

if(User::level() != 4){
	go_exit();
}

?>
<table style="width: 100%;" border="1">
  <tr>
    <th>name</th>
    <th>RU</th>
    <th>UA</th>
    <th>UZ</th>
  </tr>
<?php

$ru = parse_ini_file(S.'/lang/ru/lang.ini');
$ua = parse_ini_file(S.'/lang/ua/lang.ini');
$uz = parse_ini_file(S.'/lang/uz/lang.ini');

foreach ($ru as $a => $b) {
	echo '<tr><td>'.$a.'</td><td>'.$ru[$a].'</td><td'.(!$ua[$a] ? ' style="background:#ffdbdb"' : NULL).'>'.$ua[$a].'</td><td'.(!$uz[$a] ? ' style="background:#ffdbdb"' : NULL).'>'.$uz[$a].'</td></tr>';
}

echo '</table>';
?>