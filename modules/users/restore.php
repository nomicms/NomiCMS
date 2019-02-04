<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('restore');
$tmp->title('title' , Language::config('restore'));

if(User::aut()){
	go_exit();
}


$act = (empty($_GET['act'])?null : htmlspecialchars($_GET['act']));

switch ($act) {

    default:
        if(!empty($_POST['go'])) {
            Security::verify_str();
                
            $login = $db->guard($_POST['login']);
            $arr = $db->fass("SELECT * FROM `users` WHERE `login` = '".$db->escape($login)."'");
            
            if(mb_strlen($login) < 2 or mb_strlen($login) > 255){
                $error .= Language::config('email_strln')."<br/>";
            } else if (!$arr){
                $error .= Language::config('error')."<br/>";
            } else if($arr['email'] == null or $arr['email_c'] == 0){
                $error .= Language::config('restore_enw')."<br/>";
            } else if ($db->n_r("SELECT * FROM `users_emails` WHERE `time_end` > '".time()."' AND `us` = '".$arr['id']."' and `module` = 'restore'") > 0){
                $error .= Language::config('email_code_get_err')."<br/>";
            } else {
                $code = Security::email_code();
                Security::make_email('restore', $code, $arr['id'], Language::config('restore_access').' <a href="http://'.$_SERVER['HTTP_HOST'].'/restore?act=activate&code='.$code.'">'.Language::config('this_url').'</a>', Language::config('restore'), $arr['email']);
                $tmp->div('success', Language::config('restore_url_go'));
            }
        }

        error($error);

        ?>
        <form method="POST" action="">
        <div class="main"> 
        <?=Language::config('restore_mes')?>: <br>
            <input type="login" required="" name="login" placeholder="<?=Language::config('restore_log')?>"><br>
            <input type="hidden" name="S_Code" value="<?=Security::rand_str()?>">
            <input type="submit" name="go" value="<?=Language::config('send')?>"><br>
        </div>
        </form>
        <?
    break;


    case 'activate':
        if(isset($_GET['code'])) {
            $code = $db->guard($_GET['code']);
            $arr = $db->fass("SELECT * FROM `users_emails` WHERE `code` = '".$code."'");
            $us = $db->fass("SELECT * FROM `users` WHERE `id` = '".$arr['us']."'");

            if(empty($code)){
                $error .= Language::config('email_nf')."<br/>";
            } else if(!$arr){
                $error .= Language::config('email_nf')."<br/>";
            } else if($arr['valid'] == 0){
                $error .= Language::config('email_cv')."<br/>";
            } else if($arr['time_end'] < time()){
                $error .= Language::config('email_del')."<br/>";
            } else if($arr['module'] != 'restore'){
                $error .= Language::config('error')."<br/>";
            } else {
                $w = Security::email_code();
                $db->query("UPDATE `users` SET `password` = '".encode($w)."' WHERE `id` = '".$arr['us']."'");
                $db->query("UPDATE `users_emails` SET `valid` = '0' WHERE `code` = '".$db->escape($code)."'");
                Core::email($us['email'], Language::config('restore_np'), Language::config('restore_np').': <b>'.$w.'</b>');
                $tmp->div('success', Language::config('restore_nps'));
            }

        }

        error($error);

    break;
}

 $tmp->back('login');
?>