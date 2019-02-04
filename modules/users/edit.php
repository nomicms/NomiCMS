<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('edit_profile');
$tmp->title('title', Language::config('edit_profile'));
User::panel();

if(!User::aut()){
	go_exit();
}

if (isset($_REQUEST['submit'])) {
    $name = $db->guard($_POST['name']);
    $fname = $db->guard($_POST['fname']);
    $strana = $db->guard($_POST['strana']);
    $gorod = $db->guard($_POST['gorod']);
    $osebe = $db->guard($_POST['osebe']);
    $tg = $db->guard($_POST['tg']);
    $sex = $db->guard($_POST['sex']);

    if (!empty($name) && !preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui", $_POST['name']) || (!empty($fname) && !preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui", $_POST['fname']))) {
        $error .= Language::config('sim_no_reg_name')."<br/>";
    }
    
    if (!isset($error)) {
    	$db->query("UPDATE `users` set `name` = '".$db->escape($name)."', `first_name` = '".$db->escape($fname)."', `sex` = '".$db->escape($sex)."', `country` = '".$db->escape($strana)."', `city` = '".$db->escape($gorod)."', `about` = '".$db->escape($osebe)."', `tg` = '".$db->escape($tg)."' WHERE `id`='".User::ID()."' ");
        $tmp->div('success', Language::config('ok_save'));
    }
}

error($error);

if(User::profile('email') == null || User::profile('email_c') == 0)
    $tmp->div('error orange', Language::config('not_secure').'! <br> <a class="email_button" href="email_manager">'.Language::config('sec_butt').'</a>');


$a=$db->fass("select * from `users` where `id` = '".User::ID()."'");

$tmp->div('menu' ,'<a class="items" href="/ava">'.img('ava.png').' '.Language::config('downl_ava').'</a>');

$tmp->div('main' ,'<form method="POST" action="">
'.Language::config('name').': <br/>
<input type="text" name="name" value="'.out($a['name']).'" /><br/>
'.Language::config('fname').'<br/>
<input type="text" name="fname" value="'.out($a['first_name']).'"/><br/>
'.Language::config('country').':<br/>
<input type="text" name="strana" value="'.out($a['country']).'"/><br/>
'.Language::config('city').':<br/>
<input type="text" name="gorod" value="'.out($a['city']).'"/><br/>
'.Language::config('about').':<br/>
<input type="text" name="osebe" value="'.out($a['about']).'"/><br/>
'.Language::config('sex').': <br/>
<select name="sex">
<option value="1" '.(out($a['sex']) == 1 ? 'selected="selected"':NULL).'>'.Language::config('men').'</option>
<option value="0" '.(out($a['sex']) == 0 ? 'selected="selected"':NULL).'>'.Language::config('wom').'</option>
</select><br/>
'.Language::config('tg').':<br/>
<input type="text" name="tg" value="'.out($a['tg']).'"/><br/>
<input type="submit" name="submit" value="'.Language::config('save').'" /></form>');

$tmp->back('panel');
?>