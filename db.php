<?php
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
	die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db('mylove', $link);
mysql_set_charset('utf8', $link);

?>