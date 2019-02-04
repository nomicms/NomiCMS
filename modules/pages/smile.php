<?php
define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');
$tmp->header('smiles');
$tmp->title('title', Language::config('smiles'));

User::panel();

$tmp->div('main', '<div class="helps_table">'.
	 '<span>:)</span> <span>:-)</span> <span>=)</span>' . smile(':)') . '<hr>' .
	 '<span>:(</span> <span>:-(</span> <span>=(</span>' . smile(':(') . '<hr>' .
	 '<span>;)</span> <span>;-)</span>' . smile(';)') . '<hr>' .
	 '<span>:D</span> <span>:-D</span> <span>=D</span>' . smile(':D') . '<hr>' .
	 '<span>:P</span> <span>:-P</span> <span>=P</span>' . smile(':P') . '<hr>' .
	 '<span>:-O</span> <span>=O</span> <span>o_O</span>' . smile(':-O') . '<hr>' .
	 '<span>;(</span> <span>;-(</span>' . smile(';(') . '<hr>' .
	 '<span>:-[</span> <span>:[</span> <span>=[</span>' . smile(':[') . '<hr>' .
	 '<span>:-*</span> <span>=*</span> <span>:kiss:</span>' . smile(':kiss:') . '<hr>' .
	 '<span>B)</span> <span>B-)</span> <span>:cool:</span>' . smile(':cool:') . '<hr>' .
	 '<span>:@</span> <span>:fu:</span>' . smile(':fu:') . '<hr>' .
	 '<span>:angry:</span>' . smile(':angry:') . '<hr>' .
	 '<span>:-Z</span> <span>:sleep:</span>' . smile(':sleep:') . '<hr>' .
	 '<span>:bravo:</span>' . smile(':bravo:') . '<hr>' .
	 '<span>:angel:</span>' . smile(':angel:') . '<hr>' .
	 '<span>:crazy:</span>' . smile(':crazy:') . '<hr>' .
	 '<span>:lol:</span>' . smile(':lol:') .
	 '</div>');

$tmp->back('pages');
?>