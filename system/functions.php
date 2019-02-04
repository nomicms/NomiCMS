<?php
function encode($var) {
	return md5(base64_encode($var) .'NomiCMS');
}

function error($var) {
	if(!empty($var)) 
		echo '<div class="error">'.$var.'</div>';
}

function my_int($var) {
	return abs(intval($var));
}

function out($var) {
	return htmlspecialchars(nl2br($var, ENT_QUOTES));
}

function bbcode() {
	require(R.'/modules/pages/bbcode.php');
}

function go_exit() {
	header('location: /');
	exit();
}

function upload_file() {
	echo <<<FILE

<script>
function uploadFile(target) {
	document.querySelector('#' + target.id + ' + .select_file > :last-child').innerHTML = target.files[0].name;
}
</script>

FILE;
}

function times($time) { 
	switch (date('j n Y', $time)) {
		case date('j n Y'): 
			return 'Сегодня ' . date('H:i', $time) .''; 
			break;

		case date('j n Y', $_SERVER['REQUEST_TIME'] - 86400): 
			return 'Вчера ' . date('H:i', $time).''; 
			break;
			
		default: 
			return strtr(date('j M Y в H:i', $time), array('Jan' => 'Янв', 
				'Feb' => 'Фев', 
				'Mar' => 'Марта', 
				'Apr' => 'Апр', 
				'May' => 'Мая', 
				'Jun' => 'Июня', 
				'Jul' => 'Июля', 
				'Aug' => 'Авг', 
				'Sep' => 'Сент', 
				'Oct' => 'Окт', 
				'Nov' => 'Ноября', 
				'Dec' => 'Дек')); 
				break; 
	}
}

function nick_new($id, $no_link=false) {
	global $db;
	$as = (($no_link) ? 'span' : 'a');
	$i = $db->fass("select * from `users` where `id` = '".$id."' limit 1");
	if (!empty($i)) {
		return '<'.(($no_link) ? 'span' : 'a href="/us'.$i['id'].'"').' class="user"><img class="ava'.(($i['date_last_entry'] > (time() -360)) ? NULL : ' offline').'" src="/files/ava/16_'.$i['ava'].'" alt="*"> '.(($i['date_last_entry'] > (time() -360)) ? ($i['sex'] == 1 ? img('on.png" class="online') : img('on_w.png" class="online')): NULL).' '.$i['login'].' '.level_new($i).'</'.$as.'>';
	} else {
		return  '<'.$as.' class="user">DELETED</'.$as.'>';
	}
}

function level_new($x) {
	return ($x['level'] == 4 ? '<span class="level dev">DEV</span>' : ($x['level'] == 3 ? '<span class="level adm">ADM</span>' : ($x['level'] ==2 ? '<span class="level mod">MOD</span>' : NULL)));
}

function ava($x) {
	return '<img src="/files/ava/'.$x['ava'].'" style="max-width: 210px;" alt="*">';
}

function nickname($id) {
	global $db;
	$i = $db->fass("select login from `users` where `id` ='".$id."' limit 1");
	if (empty($i)) return 'DELETED';
	return $i['login'];
}

function page($url) {
    global $page, $total;
    if($page != 1) $pervpage = '<a href="'.$url.'page=1">&lt;&lt;</a><a href="'.$url.'page='.($page-1).'">&lt;</a>';
    if($page != $total) $nextpage = '<a href="'.$url.'page='.($page+1).'">&gt;</a><a href="'.$url.'page='.$total.'">&gt;&gt;</a>';
    
    if($page-2>0 && $page == $total) $page2left = '<a href="'.$url.'page='.($page-2).'">'.($page-2).'</a>';
    if($page-1>0) $page1left = '<a href="'.$url.'page='.($page-1).'">'.($page-1).'</a>';
    
    if($page+2 <= $total) $page2right = '<a href="'.$url.'page='.($page+2).'">'.($page+2).'</a>';
    if($page+1 <= $total) $page1right = '<a href="'.$url.'page='.($page+1).'">'.($page+1).'</a>';
    
    if($total > 1) {
        echo '<hr><div class="main"><div class="nav flex">'.$pervpage.$page2left.$page1left.'<a class="active">'.$page.'</a>'.$page1right.$page2right.$nextpage.'</div></div>';
    }
}

function bb($mes) {
    $mes = stripslashes($mes);
	$mes = preg_replace('#\[b\](.+)\[/b\]#si', '<b>\1</b>', $mes);
    $mes = preg_replace('#\[i\](.+)\[\/i\]#si', '<i>\1</i>', $mes);
    $mes = preg_replace('#\[u\](.+)\[\/u\]#si', '<u>\1</u>', $mes);
    $mes = preg_replace('#\[s\](.+)\[\/s\]#si', '<s>\1</s>', $mes);
    $mes = preg_replace('#\[cit\](.+)\[/cit\]#si', '<div class="cit">\1</div>', $mes);
    $mes = preg_replace('#\[rep\](.+)\[/rep\]#si', '<span class="rep">\1</span>', $mes);
    $mes = preg_replace('#\[img\](.+)\[/img\]#si', '<a href="\1"><img src="\1" alt="*"></a>', $mes);
    $mes = preg_replace('#\[red\](.+)\[\/red\]#si', '<span style="color: #f44336">\1</span>', $mes);
    $mes = preg_replace('#\[green\](.+)\[\/green\]#si', '<span style="color: #81c136">\1</span>', $mes);
    $mes = preg_replace('#\[blue\](.+)\[\/blue\]#si', '<span style="color: #2196f3">\1</span>', $mes);
    $mes = preg_replace('~\[color=((?:#[a-fA-F0-9]{3,6})+)\](.+)\[/color\]~s', '<span style="color: \1">\2</span>', $mes);
    $mes = preg_replace('#\[code\](.+)\[\/code\]#si', '<code>\1</code>', $mes);
    $mes = preg_replace('#\[url=(https?://[a-z0-9-]+\.+\S[^\'"(><]+)*\](.+)\[/url\]#i', '<a class="link_visual" target="_blank" href="$1">$2</a>', $mes);
    $mes = preg_replace("~(^|\s|-|:| |\()(http(s?)://|(www\.))((\S{25})(\S{5,})(\S{15})([^\<\s.,>)\];'\"!?]))~i", "\\1<a class=\"link_visual\" target=\"_blank\" href=\"http\\3://\\4\\5\">\\4\\6...\\8\\9</a>", $mes);
    $mes = preg_replace("~(^|\s|-|:|\(| |\xAB)(http(s?)://|(www\.))((\S+)([^\<\s.,>)\];'\"!?]))~i", "\\1<a class=\"link_visual\" target=\"_blank\" href=\"http\\3://\\4\\5\">\\4\\5</a>", $mes);

    return nl2br($mes);
}

function smile($text, $show=false) {
	$smile_dir = '/design/smile/'; // папка со смайликами
	$smile_array = array(
			':-)' => '<img src="' . $smile_dir . 'smile.png" alt="*" />',
			':)' => '<img src="' . $smile_dir . 'smile.png" alt="*" />',
			'=)' => '<img src="' . $smile_dir . 'smile.png" alt="*" />',
			':-(' => '<img src="' . $smile_dir . 'sad.png" alt="*" />',
			':(' => '<img src="' . $smile_dir . 'sad.png" alt="*" />',
			'=(' => '<img src="' . $smile_dir . 'sad.png" alt="*" />',
			':-D' => '<img src="' . $smile_dir . 'biggrin.png" alt="*" />',
			':D' => '<img src="' . $smile_dir . 'biggrin.png" alt="*" />',
			'=D' => '<img src="' . $smile_dir . 'biggrin.png" alt="*" />',
			':-P' => '<img src="' . $smile_dir . 'togue.png" alt="*" />',
			':P' => '<img src="' . $smile_dir . 'togue.png" alt="*" />',
			'=P' => '<img src="' . $smile_dir . 'togue.png" alt="*" />',
			':-O' => '<img src="' . $smile_dir . 'shock.png" alt="*" />',
			'=O' => '<img src="' . $smile_dir . 'shock.png" alt="*" />',
			'o_O' => '<img src="' . $smile_dir . 'shock.png" alt="*" />',
			';-(' => '<img src="' . $smile_dir . 'cry.png" alt="*" />',
			';(' => '<img src="' . $smile_dir . 'cry.png" alt="*" />',
			';-)' => '<img src="' . $smile_dir . 'wink.png" alt="*" />',
			';)' => '<img src="' . $smile_dir . 'wink.png" alt="*" />',
			':-[' => '<img src="' . $smile_dir . 'hesitate.png" alt="*" />',
			':[' => '<img src="' . $smile_dir . 'hesitate.png" alt="*" />',
			'=[' => '<img src="' . $smile_dir . 'hesitate.png" alt="*" />',
			':-*' => '<img src="' . $smile_dir . 'kiss.png" alt="*" />',
			'=*' => '<img src="' . $smile_dir . 'kiss.png" alt="*" />',
			':kiss:' => '<img src="' . $smile_dir . 'kiss.png" alt="*" />',
			'B-)' => '<img src="' . $smile_dir . 'cool.png" alt="*" />',
			'B)' => '<img src="' . $smile_dir . 'cool.png" alt="*" />',
			':cool:' => '<img src="' . $smile_dir . 'cool.png" alt="*" />',
			':@' => '<img src="' . $smile_dir . 'fu.png" alt="*" />',
			':fu:' => '<img src="' . $smile_dir . 'fu.png" alt="*" />',
			':angry:' => '<img src="' . $smile_dir . 'angry.png" alt="*" />',
			':-Z' => '<img src="' . $smile_dir . 'sleep.png" alt="*" />',
			':sleep:' => '<img src="' . $smile_dir . 'sleep.png" alt="*" />',
			':bravo:' => '<img src="' . $smile_dir . 'bravo.png" alt="*" />',
			':angel:' => '<img src="' . $smile_dir . 'angel.png" alt="*" />',
			':crazy:' => '<img src="' . $smile_dir . 'crazy.png" alt="*" />',
			':lol:' => '<img src="' . $smile_dir . 'lol.png" alt="*" />');
	
	if ($show) {
		$smile_array = array_unique($smile_array, SORT_REGULAR);
		foreach ($smile_array as $a => $i) { echo '<a onclick="tag(\''.$a.'\')">'.$i.'</a>'; }
	}

	return strtr($text, $smile_array);
}

function img($file) {
	 return '<img src="/design/styles/'.User::settings('theme').'/img/'.$file.'" alt="*" />';
}

function file_icon($path) {
	$icon_dir = 'file_icon/';
	$ext = strtolower(explode('.', $path)[1]);
	
	if (in_array($ext, array('jpg', 'png', 'gif', 'jpeg', 'svg', 'ico', 'psd')))
		return img($icon_dir . 'img.png');
	
	$ext_array = array(
		'zip' => img($icon_dir . 'zip.png'),
		'rar' => img($icon_dir . 'rar.png'),
		'txt' => img($icon_dir . 'txt.png')
	);

	$result = $ext_array[$ext];
    return ($result ? $result : img('file_icon/default.png'));
}

function subtok($string,$chr,$pos,$len = NULL) {
	return implode($chr,array_slice(explode($chr,$string),$pos,$len));
}

function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100) {
	if (!file_exists($src)) return false;
	$size = getimagesize($src);

	if ($size === false) return false;
	$format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
	$icfunc = "imagecreatefrom" . $format;
	if (!function_exists($icfunc)) return false;

	$x_ratio = $width / $size[0];
	$y_ratio = $height / $size[1];

	$ratio = min($x_ratio, $y_ratio);
	$use_x_ratio = false;	//($x_ratio == $ratio);

	$new_width = !$use_x_ratio  ? $width  : floor($size[0] * $ratio);
	$new_height = !$use_x_ratio ? $height : floor($size[1] * $ratio);
	$new_left = $use_x_ratio ? 0 : floor(($width - $new_width) / 2);
	$new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

	$isrc = $icfunc($src);
	$idest = imagecreatetruecolor($width, $height);

	imagefill($idest, 0, 0, $rgb);
	imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width+1, $new_height, $size[0], $size[1]);

	imagejpeg($idest, $dest, $quality);

	imagedestroy($isrc);
	imagedestroy($idest);

	return true;
}

function format_filesize($path, $decimals = 2) {
	if(!file_exists($path)) return "файл не найден";

	$bytes = filesize($path);
    $size = array('Байт', 'Кб', 'Мб', 'Гб');
    $factor = (int) floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
}

function browser($browser) {

    if (empty($browser)) {
		$browser = $_SERVER['HTTP_USER_AGENT']; 
	}

    if (stripos($browser, 'Avant Browser') !== false) {
      return 'Avant Browser';
    } elseif (stripos($browser, 'Acoo Browser') !== false) {
      return 'Acoo Browser';
    } elseif (stripos($browser, 'MyIE2') !== false) {
      return 'MyIE2';
    } elseif (preg_match('|Iron/([0-9a-z\.]*)|i', $browser, $pocket)) {
      return 'SRWare Iron ' . subtok($pocket[1], '.', 0, 2);
    } elseif(preg_match('|OPR/([0-9a-z\.]*)|i', $browser, $pocket)) {
      return 'Opera ' . subtok($pocket[1], '.', 0, 3);
    } elseif (preg_match('|Chrome/([0-9a-z\.]*)|i', $browser, $pocket)) {
      return 'Chrome ' . subtok($pocket[1], '.', 0, 3);
    } elseif (preg_match('#(Maxthon|NetCaptor)( [0-9a-z\.]*)?#i', $browser, $pocket)) {
      return $pocket[1] . $pocket[2];
    } elseif (stripos($browser, 'Safari') !== false && preg_match('|Version/([0-9]{1,2}.[0-9]{1,2})|i', $browser, $pocket)) {
      return 'Safari ' . subtok($pocket[1], '.', 0, 3);
    } elseif (preg_match('#(NetFront|K-Meleon|Netscape|Galeon|Epiphany|Konqueror|Safari|Opera Mini|Opera Mobile/Opera Mobi)/([0-9a-z\.]*)#i', $browser, $pocket)) {
      return $pocket[1] . ' ' . subtok($pocket[2], '.', 0, 2);
    } elseif (stripos($browser, 'Opera') !== false && preg_match('|Version/([0-9]{1,2}.[0-9]{1,2})|i', $browser, $pocket)) {
      return 'Opera ' . $pocket[1];
    } elseif (preg_match('|Opera[/ ]([0-9a-z\.]*)|i', $browser, $pocket)) {
      return 'Opera ' . subtok($pocket[1], '.', 0, 2);
    } elseif (preg_match('|Orca/([ 0-9a-z\.]*)|i', $browser, $pocket)) {
      return 'Orca ' . subtok($pocket[1], '.', 0, 2);
    } elseif (preg_match('#(SeaMonkey|Firefox|GranParadiso|Minefield|Shiretoko)/([0-9a-z\.]*)#i', $browser, $pocket)) {
      return $pocket[1] . ' ' . subtok($pocket[2], '.', 0, 3);
    } elseif (preg_match('|rv:([0-9a-z\.]*)|i', $browser, $pocket) && strpos($browser, 'Mozilla/') !== false) {
      return 'Mozilla ' . subtok($pocket[1], '.', 0, 2);
    } elseif (preg_match('|Lynx/([0-9a-z\.]*)|i', $browser, $pocket)) {
      return 'Lynx ' . subtok($pocket[1], '.', 0, 2);
    } elseif (preg_match('|MSIE ([0-9a-z\.]*)|i', $browser, $pocket)) {
      return 'IE ' . subtok($pocket[1], '.', 0, 2);
    } elseif (preg_match('|Googlebot/([0-9a-z\.]*)|i', $browser, $pocket)) {
      return 'Google Bot ' . subtok($pocket[1], '/', 0, 2);
    } elseif (preg_match('|Yandex|i', $browser)) {
      return 'Yandex Bot ';
    } elseif (preg_match('|Nokia([0-9a-z\.\-\_]*)|i', $browser, $pocket)) {
      return 'Nokia '.$pocket[1];
    } else {
      $browser = preg_replace('|http://|i', '', $browser);
      $browser = strtok($browser, '/ ');
      $browser = substr($browser, 0, 22);
      $browser = subtok($browser, '.', 0, 2);

      if (!empty($browser)) {
      	return $browser;
      }
    } 
	return 'Unknown';
}

?>