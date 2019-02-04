<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('user');
$idu=my_int($db->guard($_GET['id']));
$u=$db->fass("select * from `users` where `id` = '".$idu."'");

if (!$u) $tmp->show_error();

$tmp->title('title', Language::config('user'). ' '.$u['login']);

User::panel();
User::banned($u['id']);

reply_user('wall', 'us', $idu, 'komy');


$tmp->div('main', ava($u));

if($u['level'] == 2) $lvl = '<span class="level mod">MOD</span>';
if($u['level'] == 3) $lvl = '<span class="level adm">ADM</span>';
if($u['level'] == 4) $lvl = '<span class="level dev">DEV</span>';

if (User::aut()) {
	echo '<div class="main"><ul><li>'.Language::config('login').': '.$u['login'].' '.(($u['level'] > 1) ? $lvl: NULL).'</li>
	<li>ID: '.$u['id'].'</li><!--<li>'.Language::config('money').': '.$u['money'].'</li>--></ul>';
	echo '<br><ul>';
	echo (!empty($u['name']) ? '<li>'.Language::config('name').': '.$u['name'].'</li>' : NULL );
	echo (!empty($u['first_name']) ? '<li>'.Language::config('fname').': '.$u['first_name'].'</li>' : NULL );
	echo '<li>'.Language::config('sex').': '.($u['sex']==1 ? Language::config('men') : Language::config('wom')).'</li>';
	echo (!empty($u['country']) ? '<li>'.Language::config('country').': '.$u['country'].'</li>' : NULL );
	echo (!empty($u['city']) ? '<li>'.Language::config('city').': '.$u['city'].'</li>' : NULL );
	echo (!empty($u['about']) ? '<li>'.Language::config('about').': '.$u['about'].'</li>' : NULL );
	echo (!empty($u['email'] && ($u['id'] == User::ID() || User::profile('level') == 4)) ? '<li>'.Language::config('email').': '.$u['email'].'</li>' : NULL );
	echo (!empty($u['tg']) ? '<li>'.Language::config('tg').': <a class="link_visual" target="_blank" href="https://t.me/'.$u['tg'].'">@'.$u['tg'].'</a></li>' : NULL );
	echo '</ul><br><ul>';
	echo (!empty($u['date_last_entry']) ? '<li>'.Language::config('date_last_entry').': '.times($u['date_last_entry']).'</li>' : NULL );
	echo (!empty($u['date_registration']) ? '<li>'.Language::config('date_registry').': '.times($u['date_registration']).'</li>' : NULL );
	echo '</ul></div>';
}

echo '<hr><div class="menu"><span class="fmenu">';

if (User::aut() && $u['id'] != User::ID()) {
	echo '<a href="/dialogs/dialogs'.$idu.'">'.img('send_mail.png').' '.Language::config('send_message').'</a>';
	$fr = $db->fass("SELECT * FROM `friends` where `komy` = '".$idu."' and `kto` = '".User::ID()."'");
	if ($fr) {
		echo ($fr['status'] ? '<a href="/friends/delete'.$idu.'">'.img('del_friend.png').' '.Language::config('delete_friend').'</a>' : '<a href="/friends/delete'.$idu.'">'.img('del_friend.png').' '.Language::config('del_req_friend').'</a>');
	} else {
		echo '<a href="/friends/add'.$idu.'">'.img('add_friend.png').' '.Language::config('add_friend').'</a>';
	}
}

echo '<a href="/friends'.$idu.'">'.img('users.png').' '.Language::config('friends').' <span>'.$db->fass_c("SELECT COUNT(*) as count FROM `friends` where `komy` = '".$idu."' and `status` = '1'").'</span></a>
<a href="/blogs/ublogs'.$idu.'">'.img('blog.png').' '.Language::config('blogs').' <span>'.$db->fass_c("SELECT COUNT(*) as count FROM `blogs` where `kto` = '".$idu."' ").'</span></a>';

// $tmp->title('title', Language::config('forum'));

echo '<a href="/forum/utopic'.$idu.'">'.img('forum.png').' '.Language::config('topic').' <span>'.$db->fass_c("SELECT COUNT(*) as count FROM `forum_topic` where `kto` = '".$idu."' ").'</span></a>
<a href="/forum/uposts'.$idu.'">'.img('posts.png').' '.Language::config('posts').' <span>'.$db->fass_c("SELECT COUNT(*) as count FROM `forum_message` where `kto` = '".$idu."' ").'</span></a>';

if (User::profile('level') >=3) {
	if (User::ID() != $u['id']) {
		echo '<a href="/apanel/ban'.$u['id'].'">'.img('add_ban.png').' '.Language::config('add_ban').'</a><a href="/apanel/uedit'.$u['id'].'">'.img('edit_profile.png').' '.Language::config('edit_profile').'</a>';
	}
}

echo '</span></div>';

################ WALL ##############
$posts=$db->fass_c("SELECT COUNT(*) as count FROM `wall` where `komy` = '".$u['id']."'");

$total = intval((($posts-1)/$num)+1);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$wall = $db->query("select * from `wall` where `komy` = '".$u['id']."' ORDER BY time DESC LIMIT ".$start.", ".$num."");
$tmp->title('title" id="wall', Language::config('wall'));

if(isset($_REQUEST['submit'])){
	Security::verify_str();

	$message = $db->guard($_POST['messages']);
	if (empty($message)) $error .= Language::config('no_message');

	if (!isset($error)) {
		if (User::ID() != $u['id'])
			User::new_notify($u['id'], 'wall_comment', '/us'.$u['id'].'#wall');

		$db->query("INSERT INTO `wall` set `kto` ='".User::ID()."', `komy` = '".$u['id']."', `message` = '".$message."', `time` = '".time()."' ");
		header('location: /us'.$u['id'].'#wall');
	}
}

if (User::aut()) {
	if (!empty($wall)) {
		while ($w=$wall->fetch_assoc()){
			echo '<hr><div class="news"><div>'.nick_new($w['kto']).' '.((User::ID() == $w['kto'] || User::level() >= 2) ? '<a class="de" href="/us'.$u['id'].'/delete'.$w['id'].'">'.img('delete.png').'</a>' : NULL).'<span class="times">'.times($w['time']).'</span>'.($w['kto'] != User::ID() ? '<a class="answer" href="us'.$idu.'/otv'.$w['id'].'">'.img('answer.png').'</a>' : NULL ).' <br/>' .bb(smile($w['message'])).'</div></div>';
		}
	}

	error($error);
	bbcode();

    $_POST['message'] = (empty($_POST['message']) ? null : $_POST['message']);
	
	$tmp->div('main', '
<form method="POST" name="message" action="">'.Language::config('message').':<br/><textarea name="messages">'.out($_POST['message']).'</textarea><br/>
<input type="hidden" name="S_Code" value="'.Security::rand_str().'">
<input type="submit" name="submit" value="'.Language::config('send').'">
</form>');

	page('?');
}

$tmp->home();
?>