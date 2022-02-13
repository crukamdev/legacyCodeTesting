<?php

// script that commits the transactions that have been made

require_once 'DB.php';
require_once 'db_config.php';

$db =& DB::connect(DSN);
$db->query('COMMIT');

?>
<div align="center">
	Transactions committed!
	<hr>
	<a href=index.php>Return to main page</a>
