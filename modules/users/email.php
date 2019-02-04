<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('email');
$tmp->title('title', Language::config('email_ttl'));
User::panel();


if(!User::aut() or User::profile('email') != null and User::profile('email_c') == 1){
	go_exit();
}

$act = (empty($_GET['act']) ? null : htmlspecialchars($_GET['act']));

switch ($act) {

	default:
		if(User::profile('email') != null)
			go_exit('?act=verify');

		if(!empty($_POST['install'])){
			Security::verify_str();
		    $email = $db->guard($_POST['email']);

			if(empty($email)){
				$error .= Language::config('no_empty').'<br/>';
		    } else if ($db->n_r("SELECT * FROM `users` WHERE `email` = '".$db->escape($email)."'") > 0){
		    	$error .= Language::config('email_used').'!<br/>';
		    } else if(mb_strlen($email) < 3 or mb_strlen($email) > 255){
		    	$error .= Language::config('email_strln')."<br/>";
	        } else if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    	$error .= Language::config('email_error')."<br/>";
		    } else {
		    	$db->query("UPDATE `users` SET `email` = '".$db->escape($email)."' WHERE `id` = '".User::ID()."'");
		    	go_exit('email_manager');
		    }
		}

		error($error);

		?>
		<form method="POST" action="">
		<div class="main"> 
			<?
			echo Language::config('email_w_1');
			echo '<div class="cit"> <span style="color: #F44336">'.Language::config('email_w_2').'</span><br>'.Language::config('email_w_3').'</div>';
			?>
			<input type="email" name="email" placeholder="Email"><br>
			<input type="hidden" name="S_Code" value="<?=Security::rand_str()?>">
			<input type="submit" name="install" value="<?=Language::config('send')?>"><br>
		</div>
		</form>
		<?
	break;

	case 'verify':
		if(User::profile('email_c') == 1 or User::profile('email') == null)
			go_exit('email_manager');

		if(isset($_GET['send_link'])){
		    if ($db->n_r("SELECT * FROM `users_emails` WHERE `time_end` > '".time()."' AND `us` = '".User::ID()."' and `module` = 'email_manager'") > 0) {
			    $error .= Language::config('email_code_get_err')."<br/>";
			} else {
			    $code = Security::email_code();
			    Security::make_email('email_manager', $code, User::ID(), Language::config('email_mes').' <a href="http://'.$_SERVER['HTTP_HOST'].'/email_manager?act=activate&code='.$code.'">'.Language::config('this_url').'</a>', Language::config('email_m_act'), User::profile('email'));
			    $tmp->div('success', Language::config('email_code_ok'));
		   }
		}

		if(isset($_GET['del_email'])){
			$db->query("UPDATE `users` SET `email` = '' WHERE `id` = '".User::ID()."'");
		    go_exit('email_manager');
		}

		error($error);
		?>
		<div class="main"><?=Language::config('email_ver_mes')?>!</div>
		<div class="main">
			<a href="email_manager?act=verify&send_link"><input type="submit" value="<?=Language::config('email_get_url')?>"></a><br>
			<br>
			<a href="email_manager?act=verify&del_email"><input type="submit" value="<?=Language::config('email_edit')?>"></a>
		</div>
		<?
	break;

	case 'activate':
		if(User::profile('email_c') == 1 or User::profile('email') == null)
			go_exit('email_manager');

		if(isset($_GET['code'])) {
			$code = $db->guard($_GET['code']);
			$arr = $db->fass("SELECT * FROM `users_emails` WHERE `code` = '".$code."'");
			
			if(empty($code)){
				$error .= Language::config('email_nf')."<br/>";
		    } else if(!$arr){
				$error .= Language::config('email_nf')."<br/>";
		    } else if($arr['valid'] == 0){
		        $error .= Language::config('email_cv')."<br/>";
		    } else if($arr['time_end'] < time()){
		        $error .= Language::config('email_del')."<br/>";
		    } else if($arr['us'] != User::ID()){
		        $error .= Language::config('email_notus')."<br/>";
		    } else if($arr['module'] != 'email_manager'){
		        $error .= "Error <br/>";
		    } else {
		    	$db->query("UPDATE `users` SET `email_c` = '1' WHERE `id` = '".User::ID()."'");
		    	$db->query("UPDATE `users_emails` SET `valid` = '0' WHERE `code` = '".$db->escape($code)."'");
		        $tmp->div('success', Language::config('email_sa'));
		    }
		}

		error($error);
	break;

}

$tmp->back('edit');
?>