<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('zc');

$id=my_int($db->guard($_GET['id']));
$p=$db->fass("select * from `zc_category` where `id` = '" .$id."'");

$tmp->title('title', '<a href="/zc">'.Language::config('zc').'</a> / '.$p['name']. (User::level() >= 3 ? '<span><a href="/zc/cat'.$p['id'].'?d">'.img('add_c.png').'</a><a href="/zc/cat'.$p['id'].'?del">'.img('del_c.png').'</a></span>' : NULL));

User::panel();

if (!$p) $tmp->show_error();

if(User::aut()){
	if(isset($_GET['d'])){
		if(User::level() >= 3){

			if(isset($_REQUEST['submit'])){
				$name = $db->guard($_POST['name']);
				$whitelist = $db->guard($_POST['whitelist']);
				$max_size = $db->guard($_POST['max_size']);
				$hide = ($db->guard($_POST['hide_file']) ? 1 : 0);

				if(mb_strlen($_POST['name'], 'UTF-8')<2) $error .= Language::config('no_name').'<br>';
				if(empty($max_size)) $max_size = 10;
				
				if(!isset($error)){
					$db->query("insert into `zc_section` set  `category` = '".$id."', `name` = '".$name."', `whitelist` = '".$whitelist."', `max_size` = '".$max_size."', `hide_files` = '".$hide."', `time` = '".time()."' ");
					$lid=$db->insert_id();
					header('location: /zc/cat'.$id.'/pc'.$lid);
				}
			}
		
			error($error);

			$tmp->div('main', '<form action="" method="post">
'.Language::config('name').':<br/>
<input name="name" value="'.out($_POST['name']) .'" /><br />
'.Language::config('whitelist').' (напр. zip;rar;txt):<br>
<div class="form_info"><input name="whitelist" value="'.out($_POST['whitelist']) .'" />
<br>'.img('f_info.png').' если не заполнять, будут разрешены все типы файлов</div>
'.Language::config('max_size_z').' :<br/>
<div class="form_info">
<input type="number" name="max_size" style="width: 40px" value="'.out($_POST['max_size']) .'" />
<br>'.img('f_info.png').' если не заполнять, размер будет установлен по умолчанию (10Мб)</div>
<input id="hide_file" type="checkbox" name="hide_file" value="yes">
<label for="hide_file">'.Language::config('hide_files').'</label>
<div class="form_info">'.img('f_info.png').' если отметить, файлы загруженные в этот раздел не будут показываться на главной странице</div>
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
			
			$tmp->div('menu', '<hr><a href="/zc/cat'.$id.'">'.img('link.png').' '.Language::config('back').'</a>');
			$tmp->footer();
		}
	}
	
	if(User::level() >= 3){
		 if(isset($_GET['del'])){
		 	if(isset($_GET['yes'])){
				$db->query("DELETE FROM `zc_category` where `id` = '".$id."' LIMIT 1 ");
				$db->query("DELETE FROM `zc_section` where `category` = '".$id."' ");
				header('location: /zc');
			}

			$tmp->del_sure($p['name'], 'del&yes');
			$tmp->footer();
		 }
	}
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `zc_section` where `category` = '".$id."' ");

if($posts==0){
	$tmp->div('main', Language::config('no_zc_section'));
	$tmp->back('zc');
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$zc= $db->query("SELECT * FROM `zc_section` where `category` = '".$id."' ORDER BY id ASC LIMIT ".$start.", ".$num." ");

echo '<div class="menu">';
while($z=$zc->fetch_assoc()){
	echo '<hr><a href="/zc/cat'.$p['id'].'/pc'.$z['id'].'">'.img('cti.png').' '.$z['name'].'</a>';
}
echo '</div>';

page('?');

$tmp->back('zc');
?>