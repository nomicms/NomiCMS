<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

$sod = file_get_contents(S.'/db_config.php');

if (file_exists(S."/db_config.php") && !empty($sod)) {

	if (file_exists(R."/install/index.php")) {
		echo 'Удалите папку install';
		exit();
	}

	require_once(R.'/system/kernel.php');
	require_once(R.'/modules/index.php');
} else {
	header('location: /install/');
}

?>