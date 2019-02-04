<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

if(User::aut()){
	go_exit();
}

$tmp->header('reg');
$tmp->title('title' , Language::config('reg'));

if(isset($_POST['submit'])) {
    $login = $db->guard($_POST['login']);
    $name = $db->guard($_POST['name']);
    $sex = $db->guard($_POST['sex']);
    $captcha = $db->guard($_POST['captcha']);

    $password = $db->guard($_POST['password']);
    $repeated_password = $db->guard($_POST['repeated_password']);

    if (empty($login)) {
        $error .= Language::config('no_empty_login')."<br/>";
    } else {
        if ((strlen($login) < 3 || strlen($login) > 15)) {
            $error .= Language::config('strlen_reg_login').". ". Language::config('dop_strlen_reg_login')."<br/>";
        } else {
            if (!preg_match("#^([A-z0-9\-\_\ ])+$#ui", $_POST['login'])) $error .= Language::config('sim_no_reg_login')."<br/>";
        }
    }

    if ($db->query("SELECT * FROM users WHERE login = '". $login ."';")->num_rows > 0) {
        $error .= Language::config('login').': <b>' . $login . '</b>, '.Language::config('login_z_reg').'.<br/>';
    }

    if (empty($password)) $error .= Language::config('no_empty_pass')."<br/>";
    if (!empty($password) && (strlen($password) < 5 || strlen($password) > 32)) {
        $error .= Language::config('strlen_reg_pass').". ". Language::config('dop_strlen_reg_pass')."<br/>";
    } else {
        if (!empty($password) && (empty($repeated_password))) $error .= Language::config('no_empty_pass2')."<br/>";
    }

    if (!empty($repeated_password) && $password != $repeated_password) $error .= Language::config('no_pass_pass2')."<br/>";

    if (!empty($name) && !preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui", $_POST['name'])) {
        $error .= Language::config('sim_no_reg_name')."<br/>";
    }

    if ($_SESSION['captcha'] != $_POST['captcha']) $error .= Language::config('captcha_error')."<br/>";

    if(!isset($error)) {
        $password = encode($password);
        $result = "INSERT INTO users (login, password, name, sex, date_registration, date_last_entry) VALUES ('". $db->escape($login) ."', '". $db->escape($password) ."', '". $db->escape($name) ."', '". $db->escape($sex) ."', '". time()."', '". time()."');";
        $db->query($result);
        $last_id = $db->insert_id();
        if($last_id == 1) $db->query("UPDATE `users` set `level` ='4' where `id` = '".$last_id."' ");

        $db->query("INSERT INTO `user_settings` set `kto` = '".$last_id."', `theme` = '".User::settings('theme')."', `language` = '".User::settings('language')."', `num` = '".Core::config('num')."' ");
        
        $_SESSION['id'] = $last_id;
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        setcookie('id', $last_id, time()+60*60*24*1024, '/');
        setcookie('login', $login, time()+60*60*24*1024, '/');
        setcookie('password', $password, time()+60*60*24*1024, '/');

        $tmp->div('success', Language::config('reg_ok'));
        $tmp->home();
    }
}
error($error);

$_POST['name'] = (empty($_POST['name']) ? null : $_POST['name']);
$_POST['login'] = (empty($_POST['login']) ? null : $_POST['login']);


$tmp->div('main', '<form method="POST" action="">
'.Language::config('login').': [3-15] *<br/>
<input type="text" name="login" value="'. out($_POST['login']) .'" /><br/>
'.Language::config('pass').': [5-32] *<br/>
<input type="password" name="password" /><br/>
'.Language::config('pass2').': *<br/>
<input type="password" name="repeated_password" /><br/>
'.Language::config('name').': <br/>
<input type="text" name="name" value="'. out($_POST['name']) .'" /><br/>
'.Language::config('sex').': <br/>
<select name="sex"><option value="1">'.Language::config('men').'</option><option value="0">'.Language::config('wom').'</option></select><br/>
<img onclick="this.src=\'/design/captcha/kcaptcha.php?\'+Math.random()" id="captcha" src="/design/captcha/kcaptcha.php" /><br />
<input type="text" name="captcha" size="7" maxlength="5" autocomplete="off" /><br/>
<input type="submit" name="submit" value="'.Language::config('reg').'" /></form>');

$tmp->footer();
?>