<?
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

if(User::aut()){
	go_exit();
}

if (isset($_REQUEST['submit'])) {

	Security::verify_str();

	$login = $db->guard($_POST['login']);
	
	if(empty($_POST['login'])) $error .= Language::config('no_empty_login').'<br/>';
	if(empty($_POST['password'])) $error .= Language::config('no_empty_pass').'<br/>';
	
	$password = encode($db->guard($_POST['password']));
	$row = $db->fass("SELECT id FROM users WHERE login = '". $login ."' AND password  = '". $password ."';");

	if (empty($row['id']) && (!empty($_POST['login']) && !empty($_POST['password']))) {
		$error .= Language::config('error_login').'<br/>';
	}

	if (!isset($error)) {
		$_SESSION['id'] = $row['id'];
		$_SESSION['login'] = $login;
		$_SESSION['password'] = $password;
		setcookie('id', $row['id'], time()+60*60*24*1024, '/');
		setcookie('login', $login, time()+60*60*24*1024, '/');
		setcookie('password', $password, time()+60*60*24*1024, '/');
		go_exit();
	}
}

$tmp->header('aut');
$tmp->title('title', Language::config('aut'));

error($error);

echo '<div class="main"><form action="/login" method="POST">
'.Language::config('login').':<br/>
<input type="text" name="login" /><br/>
'.Language::config('pass').':<br/>
<input type="password" name="password" /><br/>
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input class="button" type="submit" value="Войти" name="submit" /></form></div>
<hr><div class="menu"><a href="/restore">'.img('settings.png').' '.Language::config('restore').'</a></div>';

$tmp->footer();
?>