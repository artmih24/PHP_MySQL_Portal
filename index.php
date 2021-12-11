<?php
	require "users.php";
?>
<head>
	<title>Учебный портал по Web</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<frameset ROWS= "120px, *" frameborder=1 border=4 bordercolor="black">
	<frame src="logo.php" name="logo" scrolling=no noresize >
	<frameset ROWS= *% COLS="350px, *%" frameborder=1 border=4 bordercolor="black">
		<frame src="menu.php" name="menu" noresize frameborder=1>
		<frame src="mysql_affected_rows.php" name="content" frameborder=1>
	</frameset>
</frameset>