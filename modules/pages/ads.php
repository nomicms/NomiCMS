<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('ads');
$tmp->title('title', Language::config('shop_ads'));
User::panel();

$tmp->div('main', 'Стоимость размещения рекламы:<br/>
<br/>
<b>Реклама вверху на всех страницах</b><br/>
70 руб в неделю<br/>
<br/>
<b>Реклама внизу на всех страницах</b><br/>
50 руб в неделю<br/>
<br/>

<b>Условия:</b><br/>
Ссылки ставятся по принципу круговой системы. Ваша ссылка размещается ниже уже имеющихся, а со временем поднимается выше.
Название ссылки не должно превышать 35 символов!<br/>
<br/>
<b>Oтвeтcтвeннocть:</b><br/>
Независимо от способа размещения объявления на сайте <b style="text-transform: uppercase">'.$_SERVER['SERVER_NAME'].'</b>, oтвeтcтвeннocть зa eгo coдepжaние нeceт peклaмoдaтeль.');

$tmp->back('pages');
?>