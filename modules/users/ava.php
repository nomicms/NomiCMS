<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('downl_ava');
$tmp->title('title', Language::config('downl_ava'));
User::panel();

if(!User::aut()){
	go_exit();
}

if (!empty($_POST['send'])) {
	$maxsize = 2;
	$whitelist = array('.jpg', '.jpeg', '.gif', '.png'); # Допустимые расширения
	$dir = R.'/files/ava'; // Папка, в которую будут загружаться файлы
	$name = $_FILES['file']['name']; # Название файла
	$ext = strtolower(strrchr($name, '.')); # Расширение файла 
	$size = $_FILES['file']['size']; # Вес файла

	if ($size > (1048576 * $maxsize)) $error .= Language::config('max_size').' [Max. '.$maxsize.'mb.]<br />';
	if (!in_array($ext, $whitelist)) $error .= Language::config('error_ext').'<br />';

	if(!isset($error)) {
		$avatar =  rand(1,999).'_NOMICMS_'.rand(1,999). $ext;
		copy($_FILES['file']['tmp_name'], $dir . '/' . $avatar ); # Копируем файл в папку
		
		if (img_resize($dir.'/'.$avatar, $dir.'/'.'16_'.$avatar, 16, 16)) {
			$db->query("update `users` set `ava` = '".$db->escape($avatar)."' WHERE `id`='".$db->escape(User::ID())."' ");
			header('location: /ava');
		} else {
			$error .= 'Ошибка обработки изображения.<br />';
		}
	}
}

error($error);
upload_file();

$b=$db->query("select * from `users` where `id` = '".User::ID()."' ")->fetch_assoc();

echo '<div class="main">'.(empty($b['ava']) ? '<img class="ava_orig" src="files/ava/no_ava.jpg" alt="*">' : ava($b)).'
<form action="" enctype="multipart/form-data" method="POST">
<input name="file" type="file" id="file" onchange="uploadFile(this)">
<label class="select_file" for="file">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
<input type="submit" name="send" value="Загрузить"/>
</form></div>';

$tmp->back('edit');
?>