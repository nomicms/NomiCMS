<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('search');
$tmp->title('title', Language::config('search'));

User::panel();


if(!User::aut()){
	$tmp->div('error', Language::config('need_auth'));
	$tmp->back('forum');
}


if(isset($_REQUEST['s'])){
	$search = $db->guard($_GET['s']);
	
	if ((strlen($search) < 3 || strlen($search) > 30)) {
		$error .= Language::config('dop_strlen_search');
	} else {
		$posts = $db->fass_c("SELECT COUNT(*) as count FROM `forum_message` WHERE `message` LIKE '%".$db->escape($search)."%' ");
		if (!$posts) $error .= Language::config('search_empty');
	}

	if(!isset($error)) {

		$tmp->title('title', Language::config('search_result') . ' ('.$posts.')');

		$total = (($posts-1)/$num)+1;
		$total = intval($total);
		$page = intval($page);
		if(empty($page) or $page<0) $page=1;
		if($page>$total) $page=$total;
		$start=$page*$num-$num;

		$s = $db->query("SELECT * FROM `forum_message` WHERE `message` LIKE '%".$db->escape($search)."%' ORDER BY id DESC LIMIT ".$start.", ".$num." ");

		echo '<hr><div class="messages">';
		while($result=$s->fetch_assoc()){
			echo '<hr><div>'.nick_new($result['kto']).'<br><a href="topic'.$result['topic'].'">'.str_replace($search, '<span class="highlight">'.$search.'</span>', $result['message']).'</a></div>';
		}
		echo '</div>';


		page('?s='.urlencode($search).'&');
		$tmp->back('forum/search');
	}
}

error($error);

$tmp->div('main', '<form method="GET" action="">
'.Language::config('search_text').': <br/>
<input type="text" name="s" value="" /><br/>
<input type="submit" value="'.Language::config('search').'" />
</form>');

$tmp->back('forum');
?>