<head>
	<title>Учебный портал по Web</title>
	<meta http-equiv='content-type' content='text/html; charset=utf-8'>
	<link rel="stylesheet" href="style.css">
</head>

<body class="login_body">
	<?php
		require_once 'config.php';
		session_start();

		if (isset($_SESSION['user'])) {
			header("Location: index.php");
			exit;
		}

		if (isset($_POST['submit'])) {
			$query = "SELECT username, password FROM ".users.";";
			$res = mysqli_query($con, $query);
			$alertText = "";
			while ($row = mysqli_fetch_assoc($res)) {
				if ($row['username'] == $_POST['user'] AND $row['password'] == md5($_POST['pass'])) {
					$_SESSION['user'] = $row['username'];
					header("Location: index.php");
					exit;
				}
			}
			echo "<p align='center' class='login_p'><b>Имя пользователя и/или пароль введены неверно!</b></p>";
		}
	?>

	<form method="post" align=center>
		<div class="login_div" align=center>
			<table class="login_table" align=center border="0" cellpadding="3">
				<tr>
					<td align=center colspan="2" class="login_td">
						<img width=180 src="image/MPEI.jpg">&nbsp;&nbsp;&nbsp;
						<img width=200 src="image/ivti.jpg">&nbsp;&nbsp;&nbsp;
						<img src="image/vmss.jpg">
					</td>
				</tr>
				<tr>
					<td align=center colspan="2" class="login_td">
						<h1 class="login_h1">Учебный портал:</h1>
						<h2 class="login_h2">Функции PHP для работы с MySQL/MySQLi.<br>Примеры запросов.</h2><br>
					</td>
				</tr>
				<tr>
					<td align=right class="login_td">
						<p class="login_p">Имя пользователя:</p>
					</td>
					<td align=left class="login_td">
						<input type="text" name="user" value="" class="login_textinput"/>
					</td>
				</tr>
				<tr>
					<td align=right class="login_td">
						<p class=login_p>Пароль:</p>
					</td>
					<td align=left class="login_td">
						<input type="password" name="pass" value="" class="login_textinput"/>
					</td>
				</tr>
				<tr>
					<td align=center colspan="2" class="login_td">
						<p class=login_p>По вопросам доступа к данному ресурсу<br>обращаться по электронному адресу:<br>
							<a href="mailto:GorelikAM@mpei.ru" class="mail_a">GorelikAM@mpei.ru</a>
						</p>
					</td>
				</tr>	
			</table>
			<br>
			<input type="submit" name="submit" value="Войти" class="login_button"/>
		</div>
	</form>
</body>