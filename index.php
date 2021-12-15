<?php
	require "users.php";
	echo '
		<head>
			<title>Учебный портал по Web</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
	';

	if ($_SESSION['user']=="admin") {
		echo '
			<frameset ROWS= "120px, *, 330px" frameborder=1 border=4 bordercolor="black">
				<frame src="logo.php" name="logo" scrolling=no noresize >
				<frameset ROWS= *% COLS="350px, *%" frameborder=1 border=4 bordercolor="black">
					<frame src="menu.php" name="menu" noresize frameborder=1>
					<frame src="mysql_affected_rows.php" name="content" frameborder=1>
				</frameset>
				<frame src="edit.php" name="edit" scrolling=no noresize>
			</frameset>
		';
	} else {
		echo '
			<frameset ROWS= "120px, *" frameborder=1 border=4 bordercolor="black">
				<frame src="logo.php" name="logo" scrolling=no noresize >
				<frameset ROWS= *% COLS="350px, *%" frameborder=1 border=4 bordercolor="black">
					<frame src="menu.php" name="menu" noresize frameborder=1>
					<frame src="mysql_affected_rows.php" name="content" frameborder=1>
				</frameset>
			</frameset>
		';
	}
?>
