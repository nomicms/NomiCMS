<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('lib');

$id=my_int($db->guard($_GET['id']));

$p=$db->fass("select * from `lib_category` where `id` = '" .$id."'");

$tmp->title('title', '<a href="/lib">'.Language::config('lib').'</a> / ' .$p['name'] . (User::level() >= 3 ? '<span><a href="/lib/c'.$p['id'].'?del">'.img('del_c.png').'</a></span>' : NULL));

User::panel();

if (!$p) $tmp->show_error();

if(isset($_GET['d'])){

	if(isset($_REQUEST['submit'])){
		Security::verify_str();
		
		$name = $db->guard($_POST['name']);
		$opis = $db->guard($_POST['opis']);
		$whitelist = array('.txt'); # Допустимые расширения
		$maxsize = 4; # В мегабайтах 
		$dir = R.'/files/lib/'; // Папка, в которую будут загружаться файлы
		$filename = $db->guard($_FILES['file']['name']); # Название файла
		$ext = strtolower(strrchr($filename, '.')); # Расширение файла 
		$size = $_FILES['file']['size']; # Вес файла
		
		if(!in_array($ext, $whitelist)) $error .= Language::config('error_ext').'<br />';
		if(mb_strlen($_POST['name'], 'UTF-8')<2) $error .= Language::config('no_name').'<br />';
		if($size > (1048576 * $maxsize)) $error .= Language::config('max_size').'. (Max. '.$maxsize.'Mb.)<br />';

		if(!isset($error)){
			$kniga = rand(1,999).'_NOMICMS_'.rand(1,999). $ext;
			$txt = file_get_contents($_FILES['file']['tmp_name']);
			
			if (mb_check_encoding($txt, 'UTF-8')) {
				// utf-8
			} elseif (mb_check_encoding($txt, 'windows-1251')) {
				$txt = iconv("windows-1251", "UTF-8", $txt);
			} elseif (mb_check_encoding($txt, 'KOI8-R')) {
				$txt = iconv("KOI8-R", "UTF-8", $txt);
			} else {
				$error .= 'Файл в неизвестной кодировке!'.'<br />';
			}
		}

		if(!isset($error)){
			file_put_contents($dir.$kniga, $txt);

			$db->query("insert into `lib_r` set `kto` = '".User::ID()."', `category` = '".$id."', `name` = '".$name."', `message` = '".$opis."', `txt` = '".$kniga."', `time` = '".time()."' ");
			$lid=$db->insert_id();
			header('location: /lib/c/l'.$lid);
		}
	}

	error($error);
	upload_file();
$_POST['name'] = (empty($_POST['name']) ? null : $_POST['name']);
$_POST['opis'] = (empty($_POST['name']) ? null : $_POST['opis']);

	$tmp->div('main', '<form action="" method="post" enctype="multipart/form-data">
'.Language::config('name').':<br/>
<input name="name" value="'.out($_POST['name']).'" /><br />
'.Language::config('opis').':<br/>
<textarea name="opis">'.out($_POST['opis']).'</textarea><br />
'.Language::config('file').':<br/>
<input name="file" type="file" id="file" onchange="uploadFile(this)">
<label class="select_file" for="file">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');

	$tmp->back('lib/c'.$id);
}

if(User::level() >= 3){
	if(isset($_GET['del'])){
		if(isset($_GET['yes'])){
			$db->query("DELETE FROM `lib_category` where `id` = '".$id."' LIMIT 1 ");
			$db->query("DELETE FROM `lib_r` where `category` = '".$id."' ");
			header('location: /lib/');
		}

		$tmp->del_sure($p['name'], 'del&yes');
		$tmp->footer();
	}
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `lib_r` where `category` = '".$id."' ");

if($posts==0){
	$tmp->div('main', Language::config('no_libl_r'));
	if(User::aut()){
		$tmp->div('menu', '<a class="items" href="/lib/c'.$p['id'].'?d">'.img('add_i.png').' '.Language::config('add_lib_r').'</a>');
	}	
	$tmp->back('lib');
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

if(User::aut()){
   	$tmp->div('menu', '<a class="items" href="/lib/c'.$p['id'].'?d">'.img('add_i.png').' '.Language::config('add_lib_r').'</a>');
}

$lib=$db->query("SELECT * FROM `lib_r` where `category` = '".$id."' ORDER BY id DESC LIMIT ".$start.", ".$num." ");

echo '<div class="menu">';
while($l=$lib->fetch_assoc()){
	echo '<hr><a href="/lib/c/l'.$l['id'].'">'.img('lib_book.png').' '.$l['name'].'</a>';
}
echo '</div>';

page('?');

$tmp->back('lib');
?>