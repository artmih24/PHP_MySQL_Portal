<?php
	session_start();

	if (isset($_GET['do'])) {
		if ($_GET['do'] == 'logout') {
			unset($_SESSION['user']);
			session_destroy();
		}
	}
	if (!isset($_SESSION['user'])) {
		header("Location: enter.php");
		exit;
	}
?>