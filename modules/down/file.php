<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('zc');

$id=my_int($_GET['id']);
$p=$db->fass("select * from `zc_file` where `id` = '" .$id."'");

$tmp->title('title', $p['name']);
User::panel();

if (!$p) $tmp->show_error();

if(isset($_GET['do'])) {
	if(User::aut()){
		$db->query("UPDATE `zc_file` set `down` = down + 1 where `id` = '".$id."'");
		header('location: /files/zc/'.$p['file']);
	} else {
		$tmp->div('error', Language::config('need_auth_down'));
	}
}

if(isset($_GET['del'])){
	if(User::aut()){
		if(User::ID() == $p['kto'] || User::level() >= 3){

			if(isset($_GET['yes'])){
				if (delete_file(R.'/files/zc/'.$p['file'])) {
					$db->query("DELETE FROM `zc_file` WHERE `id` = '".$id."' limit 1 ");
					header('location: /zc/cat'.$p['category'].'/pc'.$p['section'].'');
				}
			}

			$tmp->del_sure($p['name'], 'del&yes');
			$tmp->footer();	
		}
	}
}

if(isset($_GET['edit'])){
	if(User::ID() == $p['kto'] || User::level() >= 3){

		if(isset($_POST['submit'])){
			$name = $db->guard($_POST['name']);
			$opis = $db->guard($_POST['opis']);
			$screen = $db->guard($_FILES['screen']['name']);
			$pin = (User::level() >= 3 ? ($db->guard($_POST['pin']) ? 1 : 0) : $p['pin']);

			if (!empty($screen)) {
				$dirs = R.'/files/zc/screen';
				$exts = strtolower(strrchr($screen, '.'));

				if ($_FILES['screen']['size'] > (1048576 * 2)) $error .= Language::config('max_size_zc_screen').'. [Max. 2 mb.]<br />';
				
				if (preg_match('/.php/i', $screen) || preg_match('/.pl/i', $screen) || $screen == '.htaccess' || !in_array(substr($exts, 1), array('jpg', 'png', 'gif', 'jpeg'))) {
					$error .= Language::config('error_ext_scr').'<br />';
				}

				$screens =  rand(1,999).'_NOMICMS_'.rand(1,999). $exts;
			}

			if(empty($name) || mb_strlen($_POST['name'], 'UTF-8')<2){
				$error .= Language::config('no_name');
			}
			
			if(!isset($error)) {
				if(!empty($screen)){
					copy($_FILES['screen']['tmp_name'], $dirs . '/' . $screens );
					$db->query("UPDATE `zc_file` set `name` = '".$name."', `opis` = '".$opis."', `screen` = '".$screens."', `pin` = '".$pin."' where `id` ='".$id."' ");
				} else {
					$db->query("UPDATE `zc_file` set `name` = '".$name."', `opis` = '".$opis."', `pin` = '".$pin."' where `id` ='".$id."' ");
				}

			    header('location: /zc/file'.$id);
			}
		}

		error($error);
		upload_file();

		$tmp->div('main', '<form action="" method="post" enctype="multipart/form-data">
'.Language::config('name').':<br/>
<input name="name" value="'.$p['name'].'" /><br />
'.Language::config('opis').':<br/>
<textarea name="opis">'.$p['opis'].'</textarea><br />
'.(User::level() >= 3 ? '<input id="pin" type="checkbox" name="pin" value="yes" '.($p['pin'] ? 'checked' : NULL).'>
<label for="pin">'.Language::config('pinned').'</label><br>' : NULL ).'
'.Language::config('screen').':<br/>
<input name="screen" type="file" id="file" onchange="uploadFile(this)">
<label class="select_file" for="file">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
<input type="submit" name="submit" value="'.Language::config('save').'" /></form>');
		
		$tmp->div('menu', '<hr><a href="/zc/file'.$id.'">'.img('link.png').' '.Language::config('back').'</a>');
		$tmp->footer();
	}
}


$tmp->div('title', $p['name']);
echo ($p['opis'] ? '<hr><div class="main">'.bb(smile($p['opis'])). '</div>' : NULL );

$ext_file = strtolower(explode('.', $p['file'])[1]);

if(in_array($ext_file, array('jpg', 'png', 'gif', 'jpeg'))){
	$tmp->div('main', '<a target="_blank" href="../files/zc/'.$p['file'].'"><img src="../files/zc/'.$p['file'].'" style="max-width: 210px; max-height: 210px;"/></a>');
} elseif ($ext_file == 'mp3') {
	$tmp->div('main', '<audio controls><source src="/files/zc/'.$p['file'].'" type="audio/mpeg"></audio>');
} elseif ($ext_file == 'mp4') {
	$tmp->div('main', '<video controls><source src="/files/zc/'.$p['file'].'" type="video/mp4"></video>');
} else {
	if(!empty($p['screen'])){
		$tmp->div('main', '<a target="_blank" href="/files/zc/screen/'.$p['screen'].'"><img src="/files/zc/screen/'.$p['screen'].'" style="max-width: 100px; max-height: 100px;"/></a>');
	}
}

$tmp->div('menu', '<a class="items" href="/zc/file'.$p['id'].'?do" '.($ext_file == 'mp3' || $ext_file == 'mp4' ? 'download' : NULL).'>'.img('down.png').' '.Language::config('down').' ('.format_filesize(R.'/files/zc/'.$p['file']).')</a>');

$tmp->div('main', Language::config('add_name').': '.nick_new($p['kto']).' '.(User::level() >= 3 || User::ID() == $p['kto'] ? '<a class="de" href="/zc/file'.$p['id'].'?del">'.img('delete.png" style="width: inherit').'</a> <a class="de" href="/zc/file'.$p['id'].'?edit">'.img('edit.png" style="width: inherit').'</a>' : NULL).' <span class="times">'.times($p['time']).'</span><br>'.Language::config('downl').': '.$p['down']);


$count=$db->fass_c("SELECT COUNT(*) as count FROM `zc_comments` where `zc_file` = '".$id."'");
$tmp->div('menu', '<hr><a href="/zc/comment'.$p['id'].'">'.img('com.png').' '.Language::config('comments').' <span>'.$count.'</span></a>');

$tmp->back('zc/cat'.$p['category'].'/pc'.$p['section']);
?>