<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('dialogs');
$tmp->title('title', Language::config('dialogs'));
User::panel();

if(!User::aut()){
	go_exit();
}

$id=my_int($db->guard($_GET['id']));
$p=$db->fass("select * from `users` where `id` = '".$id."'");

if (!$p || User::ID()==$id) $tmp->show_error();

$proverka=$db->fass("SELECT * FROM `dialogs` where `kto`= '".User::ID()."' and `komy`= '".$id."'");

if($proverka['komy'] != $id && $proverka['kto'] != User::ID()) {
    $db->query("INSERT INTO `dialogs` set `kto` = '".User::ID()."', `komy` = '".$id."'");
    $db->query("INSERT INTO `dialogs` set `kto` = '".$id."', `komy` = '".User::ID()."'");
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `dialogs_message` where `kto` = '".User::ID()."' and `komy` = '".$id."' or `kto` = '".$id."' and `komy` = '".User::ID()."'");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;


$ignor=$db->fass("SELECT `ignor` FROM `dialogs` WHERE `kto`= '".$id."' AND `komy`= '".User::ID()."' LIMIT 1")['ignor'];
if ($ignor) error(Language::config('ignor_you'));

// var_dump($proverka);

if(isset($_REQUEST['ignor'])) {
    $db->query("UPDATE `dialogs` set `ignor` = '".($proverka['ignor'] ? 0 : 1)."' where `kto` = '".User::ID()."' and `komy` = '".$id."' ");
    header('location: /dialogs/dialogs'.$id);
}


$d=$db->query("select * from `dialogs_message` where `kto` = '".User::ID()."' and `komy` = '".$id."' or `kto` = '".$id."' and `komy` = '".User::ID()."' ORDER BY time DESC LIMIT ".$start.", ".$num." ");

if(User::aut()){
    if(isset($_REQUEST['submit'])) {
        if ($ignor) go_exit('/dialogs/dialogs'.$id);

        $text = $db->guard($_POST['messages']);
          
        Security::verify_str();  

        if(empty($text) || mb_strlen($text, 'UTF-8')<2) $error .= Language::config('no_message').'<br>';
        if ($proverka['ignor']) $error .= Language::config('need_del_ignor');

        $filename = $db->guard($_FILES['file']['name']);

        if (!empty($filename)) {
            $whitelist = array('jpg','gif','png','jpeg', 'bmp','zip','rar','mp4','mp3','amr','3gp','avi','flv','apk','txt');
            $maxsize = 10;
            $dir = R.'/files/mail';
            $ext = strtolower(strrchr($filename, '.'));
            $size = $_FILES['file']['size'];

            if (!in_array(substr($ext, 1), $whitelist)) $error .= Language::config('error_ext').'<br />';
            if ($size > (1048576 * $maxsize)) $error .= Language::config('max_size').'. [Max. '.$maxsize.'mb.]<br />';

            $file = rand(1,999).'_NOMICMS_' . substr(md5(rand(1,9999).'NOMICMS'), 0, 8) . $ext;
        }

        if(!isset($error)) {
            $db->query("INSERT INTO `dialogs_message` SET `kto` = '".User::ID()."', `komy` = '".$id."', `message` = '".$text."', `time` = '".time()."', `readln` = '0' ");
            $lid=$db->insert_id();
            
            if(!empty($filename)){
                copy($_FILES['file']['tmp_name'], $dir . '/' . $file );
                $db->query("insert into `dialogs_file` set `kto` = '".User::ID()."', `komy` = '".$id."', `mess_id` = '".$lid."', `name` = '".$file."' ");
            }

            $db->query("UPDATE `dialogs` set `time_last` = '".time()."' where `kto` = '".User::ID()."' and  `komy` = '".$id."' or `kto` = '".$id."' and  `komy` = '".User::ID()."'");
            header('location: /dialogs/dialogs'.$id);
        }

    }

    $tmp->div('menu', '<a href="/dialogs/dialogs'.$id.'?'.rand(101, 999).' ">'.img('refresh.png').' '.Language::config('refresh').'</a>');
    
    error($error);
    bbcode();

    $tmp->div('main', '<form method="POST" name="message" action="/dialogs/dialogs'.$id.'?'.rand(101, 999).'" enctype="multipart/form-data">
'.Language::config('message').':<br/><textarea name="messages"></textarea><br />
<input name="file" type="file" id="file" onchange="uploadFile(this)">
<label class="select_file" for="file">'.img('file.png').'<span>'.Language::config('select_file').'</span></label><br />
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('send').'" />
<a class="ignore '.($proverka['ignor'] ? 'green' : 'red').'" href="?ignor">'.($proverka['ignor'] ? Language::config('ignor_del') : Language::config('ignor_add')).'</a></form>');

}


$b=$db->fass("SELECT * FROM `users` where `id` = '".$id."' LIMIT 1 ");
$date_last_entry = $db->guard($b['date_last_entry']);

echo (empty($date_last_entry) ? NULL : $date_last_entry > (time() - 360) ? NULL : '<hr><div class="main">'.Language::config('date_last_entry').': '.times($date_last_entry).'</div>');

if($posts==0){
	$tmp->div('main', Language::config('no_messages'));
    $tmp->div('menu', '<hr><a href="/dialogs">'.img('link.png').' '.Language::config('dialogs').'</a>');
    $tmp->footer();
}


if(User::aut()){
    echo '<div class="messages">';

    while($di=$d->fetch_assoc()) {     
        if (User::ID() == $di['komy'])
            $db->query("UPDATE `dialogs_message` set `readln` = '1' where `id` = '".$di['id']."' ");

    	echo '<hr><div'.(($di['readln'] == 0) ? ' class="no_read"' : NULL).'>'.nick_new($di['kto']).' <span class="times">'.times($di['time']).'</span><br>'.bb(smile($di['message']));

        $filec = $db->n_r("select id from `dialogs_file` where `mess_id` = '".$di['id']."' limit 1");
        if($filec){
            $file = $db->fass("select * from `dialogs_file` where `mess_id` = '".$di['id']."' limit 1");
                if($file['mess_id'] == $di['id'] && $file['komy'] == $di['komy'])
                    echo  '<div class="files"><a href="/files/mail/'.$file['name'].'">'.img('down_s.png').' '.$file['name'].' | '.format_filesize(R.'/files/mail/'.$file['name']).'</a></div>';
        }
        echo '</div>';
    }

    echo '</div>';

}
page('?');

$tmp->div('menu', '<hr><a href="/dialogs">'.img('link.png').' '.Language::config('dialogs').'</a>');
$tmp->footer();
?>