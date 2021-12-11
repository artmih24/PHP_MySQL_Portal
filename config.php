<?php

	define('host','localhost');
	define('db_name', 'curs');
	define('user', 'root');
	define('pass', '');
	define('tbl', 'content');
	define('users','users');

	$con=mysqli_connect(host, user, pass) or die("Нет соединения с сервером");
	mysqli_select_db($con, db_name) or die("Нет соединения с БД");
	mysqli_query($con, "SET NAMES 'utf8'") or die("Не удалось установить кодировку соединения");

?>