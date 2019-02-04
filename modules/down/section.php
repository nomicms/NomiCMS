<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('zc');

$cid=my_int($db->guard($_GET['cid']));
$id=my_int($db->guard($_GET['id']));
$c=$db->fass("select * from `zc_category` where `id` = '" .$cid."'");
$p=$db->fass("select * from `zc_section` where `id` = '" .$id."'");

$tmp->title('title', '<a href="/zc">'.Language::config('zc').'</a> / <a href="/zc/cat'.$c['id'].'">'.$c['name'].'</a> / '.$p['name']. (User::level() >= 3 ? '<span><a href="/zc/cat'.$p['category'].'/pc'.$p['id'].'?del">'.img('del_c.png').'</a></span>' : NULL));

User::panel();

if (!$p) $tmp->show_error();

if(isset($_GET['d'])){

	if(isset($_REQUEST['submit'])){
		Security::verify_str();

		$name = $db->guard($_POST['name']);
		$opis = $db->guard($_POST['opis']);
		$whitelist = explode(';', $p['whitelist']); # Допустимые расширения
		$whitelistscreen = array('jpg', 'png', 'gif', 'jpeg'); # Допустимые расширения скринов
		$maxsize = $p['max_size']; # В мегабайтах 
		$dir = R.'/files/zc'; // Папка, в которую будут загружаться файлы
		$dirs = R.'/files/zc/screen'; // Папка, в которую будут загружаться файлы
		$filename = $db->guard($_FILES['file']['name']); # Название файла
		$screen = $db->guard($_FILES['screen']['name']); # Название скрина
		$ext = strtolower(strrchr($filename, '.')); # Расширение файла 
		$exts = strtolower(strrchr($screen, '.')); # Расширение скрина
		$size = $_FILES['file']['size']; # Вес файла
		$sizescreen = $_FILES['screen']['size']; # Вес скрина
		$hide = ($p["hide_files"] ? 1 : 0);

		if ($size > (1048576 * $maxsize)) $error .= Language::config('max_size').'. [Max. '.$maxsize.'mb.]<br />';
		if ($sizescreen > (1048576 * 2)) $error .= Language::config('max_size_zc_screen').'. [Max. 2 mb.]<br />';

		if (preg_match('/.php/i', $filename) || preg_match('/.pl/i', $filename) || $filename == '.htaccess' || !in_array(substr($ext, 1), $whitelist) && !empty($whitelist[0]) || empty($filename)) {
				$error .= Language::config('error_ext').'<br />';
		}

		if (!empty($screen) && !in_array(substr($exts, 1), $whitelistscreen)) $error .= Language::config('error_ext_scr').'<br />';

		$file =  rand(1,999).'_NOMICMS_'.rand(1,999). $ext;
		$screens =  rand(1,999).'_NOMICMS_'.rand(1,999). $exts;

		if(empty($name) || mb_strlen($_POST['name'], 'UTF-8')<2){
			$error .= Language::config('no_name').'<br />';
		}
		
		if(!isset($error)) {
		    copy($_FILES['file']['tmp_name'], $dir . '/' . $file ); # Копируем файл в папку
		    
			if(!empty($screen)){
			    copy($_FILES['screen']['tmp_name'], $dirs . '/' . $screens ); # Копируем файл в папку
				$db->query("insert into `zc_file` set `kto` = '".User::ID()."', `category` = '".$cid."', `section` = '".$id."', `name` = '".$name."', `opis` = '".$opis."', `file` = '".$file."',`screen` = '".$screens."', `time` = '".time()."', `hide` = '".$hide."' ");
				$lid=$db->insert_id();
				header('location: /zc/file'.$lid);
			} else {
				$db->query("insert into `zc_file` set `kto` = '".User::ID()."', `category` = '".$cid."', `section` = '".$id."', `name` = '".$name."', `opis` = '".$opis."', `file` = '".$file."', `time` = '".time()."', `hide` = '".$hide."' ");
				$lid=$db->insert_id();
				header('location: /zc/file'.$lid);
			}
		}
	}

error($error);
upload_file();

$_POST['opis'] = (empty($_POST['opis']) ? null : $_POST['opis']);
$_POST['name'] = (empty($_POST['name']) ? null : $_POST['name']);

$tmp->div('main', '<form action="" method="post" enctype="multipart/form-data">
'.Language::config('name').':<br/>
<input name="name" value="'.out($_POST['name']) .'" /><br />
'.Language::config('opis').':<br/>
<textarea name="opis">'.out($_POST['opis']).'</textarea><br />
'.Language::config('file').''.($p['whitelist'] ? ' ('. str_replace(';', ', ',($p['whitelist'])) .')' : NULL).':<br/>
<input name="file" type="file" id="file" onchange="uploadFile(this)">
<label class="select_file" for="file">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
'.Language::config('screen').' (для изображений не нужен):<br/>
<input name="screen" type="file" id="file_s" onchange="uploadFile(this)">
<label class="select_file" for="file_s">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');

$tmp->back('zc/cat'.$p['category'].'/pc'.$p['id']);
}

if(User::level() >= 3){
	 if(isset($_GET['del'])){
	 	if(isset($_GET['yes'])){
			$db->query("DELETE FROM `zc_section` where `id` = '".$id."' ");
			$db->query("DELETE FROM `zc_file` where `section` = '".$id."' ");
			header('location: /zc/cat'.$cid.'');
		}

		$tmp->del_sure($p['name'], 'del&yes');
		$tmp->footer();
	}
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `zc_file` where `section` = '".$id."' ");

if($posts==0){
	$tmp->div('main', Language::config('no_zc_r'));

	if(User::aut()){
		$tmp->div('menu', '<a class="items" href="/zc/cat'.$p['category'].'/pc'.$p['id'].'?d">'.img('add_i.png').' '.Language::config('add_zc_file').'</a>');
	}

	$tmp->back('zc/cat'.$cid);
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

if(User::aut()){
   	$tmp->div('menu', '<a class="items" href="/zc/cat'.$p['category'].'/pc'.$p['id'].'?d">'.img('add_i.png').' '.Language::config('add_zc_file').'</a>');
}

$zc=$db->query("SELECT * FROM `zc_file` where `section` = '".$id."' ORDER BY id DESC LIMIT ".$start.", ".$num." ");

echo '<div class="menu">';
while($z=$zc->fetch_assoc()){
	echo '<hr><a href="/zc/file'.$z['id'].'">'.file_icon($z['file']).' '.$z['name'].'</a>';
}
echo '</div>';

page('?');

$tmp->back('zc/cat'.$cid);
?>