<?php
Class Tmp {
	
	public function header($title)
	{
		global $db, $gens;
		$gens = microtime(true);
		$theme = User::settings('theme');

		if (file_exists(R.'/design/styles/'.$theme.'/header.php')) {
			require_once(R.'/design/styles/'.$theme.'/header.php');
		} else {
?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
<title><? echo Language::config($title); ?></title>
<meta name="viewport" content="width=device-width" />
<? echo (Core::config('description') ? '<meta name="description" content="'.Core::config('description').'" />' : NULL); ?>
<? echo (Core::config('keywords') ? '<meta name="keywords" content="'.Core::config('keywords').'" />' : NULL); ?>
<link rel="stylesheet" href="/design/styles/<? echo $theme; ?>/style.css" />
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,600" rel="stylesheet">
<link rel="shortcut icon" href="/design/styles/<? echo $theme; ?>/favicon.ico" type="image/x-icon" />
</head>
<body>
<div class="logo"><a href="/"><? echo img('logo.png') ?></a></div>
<?
			self::ads(1);
		}
	}
	
	public static function div($name, $content)
	{
		echo '<div class="'.$name.'">'.$content.'</div>';
	}

	public static function title($name, $content)
	{
		echo '<div class="'.$name.'">'.img('title.png').' '.($content ? $content : Language::config('error')).'</div>';
	}

	public static function friends_menu($uid, $a)
	{
		echo '<div class="friends_menu flex"><a '.(($a == 1) ? 'class="active"' : NULL).' href="/friends'.$uid.'">'.Language::config('friends').'</a> <a '.(($a == 2) ? 'class="active"' : NULL).' href="/friends/bid">'.Language::config('bid').'</a> <a '.(($a == 3) ? 'class="active"' : NULL).' href="/friends/online'.$uid.'">'.Language::config('friends_online').'</a></div>';
	}

	public static function del_sure($name, $link)
	{
		echo '<div class="main">Вы уверенны что хотите удалить?<br>| '.$name.'</div><div class="menu bm flex"><a href="?'.$link.'">'.img('delete.png').' Да, удалить!</a> <a style="cursor: pointer" onclick="history.go(-1)">'.img('link.png').' Отменить</a></div>';
	}

	public static function show_error()
	{
		self::div('error', Language::config('error'));
		self::div('menu', '<a href="/">'.img('link.png').' '.Language::config('home').'</a>');
		self::footer();
	}

	public static function need_auth($link)
	{
		self::div('error', Language::config('need_auth'));
		self::div('menu', '<a href="/'.$link.'">'.img('link.png').' '.Language::config('back').'</a>');
		self::footer();
	}

	public static function back($link)
	{
		self::div('menu', '<hr><a href="/'.$link.'">'.img('link.png').' '.Language::config('back').'</a>');
		self::footer();
	}

	public static function home()
	{
		self::div('menu', '<hr><a href="/">'.img('link.png').' '.Language::config('home').'</a>');
		self::footer();
	}

	public static function ads($pos)
	{
		global $db;
		$ads=$db->query("select * from `ads` where `time_end` > '".time()."' ");
		while($a = $ads->fetch_assoc()){
			if($a['local'] == $pos) self::div('ds', '<a href="http://'.$a['link'].'">'.img('ds.png').' '.$a['name'].'</a>');
		}
	}

	public function footer()
	{
		global $gens, $db;
		$theme = User::settings('theme');
		if (file_exists(R.'/design/styles/'.$theme.'/footer.php')) {
			require_once(R.'/design/styles/'.$theme.'/footer.php');
		} else {
			$o=$db->fass_c("SELECT COUNT(*) as count FROM `users` WHERE `date_last_entry` > '".(time() - 360)."'");
			$g=$db->fass_c("SELECT COUNT(*) as count FROM `guests` WHERE `time` > '".(time() - 600)."'");

			self::ads(0);
			self::div('footer', '<a href="/online">'. Language::config('online').': '.$o. ' </a> | '.$g.'<br>Gen: '.round(microtime(true) - $gens, 4) . (empty(Core::config('counters')) ? NULL : '<span class="counters">'.Core::config('counters').'</span>'));
		}
		echo '</body></html>';
		exit;
	}
}
?>