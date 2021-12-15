<?php
	session_start();
	$editMenu = 0;
	$editText = 0;
	echo "
		<head>
			<meta http-equiv='content-type' content='text/html; charset=utf-8'>
			<link rel='stylesheet' rev='stylesheet' type='text/css' href='style.css' />	
			<script type='text/javascript' src='ckeditor/ckeditor.js'></script>
			<script src='js/highlight.pack.js'></script>
			<script>
				hljs.initHighlightingOnLoad();
			</script>
			<link rel='stylesheet' rev='stylesheet' type='text/css' href='js/styles/darcula.css'/>	
		</head>
	";

	require_once 'functions.php';
	$txt = selectText($id);
	$txt[0]['text'] = str_replace('&', '&amp;', $txt[0]['text']);
	if (isset($_POST['editMenu'])) {
		echo "
			<body>
				<div class='content1'>
					<form method='post'>
						<input type='submit' name='saveMenu' class='saveButton' value='Сохранить'/>
						<br><br>
						<textarea id='editor1' name='txt' cols='100' rows='50'>".$txt[0]['text']."</textarea>
						<script type='text/javascript'>
							var ckeditor1 = CKEDITOR.replace('editor1', {width: '100%', height: '50%'});
						</script>
						<br />
						<input type='hidden' name='id' value=".$txt[0]['id']." />
					</form>
				</div>
			</body>
		";
	}
	if (isset($_POST['editText'])) {
		echo "
			<body>
				<div class='content1'>
					<form method='post'>
						<input type='submit' name='saveText' class='saveButton' value='Сохранить'/>
						<br><br>
						<textarea id='editor1' name='txt' cols='100' rows='50'>".$txt[0]['text']."</textarea>
						<script type='text/javascript'>
							var ckeditor1 = CKEDITOR.replace('editor1', {width: '100%', height: '50%'});
						</script>
						<br />
						<input type='hidden' name='id' value=".$txt[0]['id']." />
					</form>
				</div>
			</body>
		";
	}
	if (isset($_POST['saveMenu'])) {
		$txt = updateText($_POST['txt'], $_POST['id']);
		$txt[0]['text'] = str_replace('&', '&amp;', $txt[0]['text']);
		$editMenu = 0;
	}
	if (isset($_POST['saveText'])) {
		$txt = updateText($_POST['txt'], $_POST['id']);
		$txt[0]['text'] = str_replace('&', '&amp;', $txt[0]['text']);
		$editText = 0;
	}
	if (isset($_POST['update'])) {
		selectText($_POST['id']);
	}
	if (isset($_POST['delArticle'])) {
		if ($_POST['selectedArticleName'] != '') {
			$selectedArticleName = $_POST['selectedArticleName'];
			echo "<script>var ans = confirm('Вы точно хотите удалить статью $selectedArticleName?')</script>";
			if ("<script>document.writeln(ans);</script>") {
				// удаление статьи из БД
				global $con;
				$query = "DELETE FROM `content` WHERE id = '$selectedArticleName'";
				mysqli_query($con, $query);

				// удаление PHP-файла с id
				unlink($selectedArticleName.'.php');

				// удаление пункта из меню
				$query = "SELECT `text` FROM `content` WHERE id = 'menu'";
				$old_menu_html = mysqli_fetch_assoc(mysqli_query($con, $query))['text'] or die("Invalid query0: " . mysqli_error($con));
				$new_menu_html = str_replace('<li><a href="'.$selectedArticleName.'.php" target="content">', '₽', $old_menu_html);
				$new_menu_html = preg_replace("/₽.*?\</u", '₽', $new_menu_html);
				$new_menu_html = str_replace('₽/a></li>', '', $new_menu_html);
				if ((strrpos($new_menu_html, '<p>Недавно добавленные</p><ul><li>', 0) == false) && 
					(strrpos($new_menu_html, '<p>Недавно добавленные</p><ul>', 0) != false)) {
					$new_menu_html = str_replace('<p>Недавно добавленные</p>', '', $new_menu_html);
				}
				$query = "UPDATE `content` SET `text` = '$new_menu_html' WHERE id = 'menu'";
				mysqli_query($con, $query);

				// обновление страницы
				echo '<script>parent.location.reload();</script>';
			}
		}
	}
	if (isset($_POST['addArticle'])) {
		$newArticleName = $_POST['newArticleName'];
		$newArticleText = $_POST['txt'];
		if (($newArticleName != '') && ($newArticleText != '')) {
			echo "<script>var ans = confirm('Вы точно хотите добавить статью $newArticleName?')</script>";
			if ("<script>document.writeln(ans);</script>") {
				// составление Id и имени PHP-файла
				$newArticleId = str_replace("::$", "_", $newArticleName);
				$newArticleId = str_replace("::", "_", $newArticleId);
				$newArticleId = str_replace("\\", "_", $newArticleId);
				$newArticleId = str_replace("/", "_", $newArticleId);
				$newArticleId = str_replace(":", "_", $newArticleId);
				$newArticleId = str_replace("*", "_", $newArticleId);
				$newArticleId = str_replace("?", "_", $newArticleId);
				$newArticleId = str_replace('"', "_", $newArticleId);
				$newArticleId = str_replace("<", "_", $newArticleId);
				$newArticleId = str_replace(">", "_", $newArticleId);
				$newArticleId = str_replace("|", "_", $newArticleId);
				$newArticleId = str_replace("+", "_", $newArticleId);
				$newArticleId = str_replace(" ", "_", $newArticleId);
				$newArticleId = preg_replace("/\s+/", "_", $newArticleId);
				$newArticleId = str_replace(".", "_", $newArticleId);
				$newArticleId = str_replace("%", "_", $newArticleId);
				$newArticleId = str_replace("!", "_", $newArticleId);
				$newArticleId = str_replace("@", "_", $newArticleId);

				// составление PHP-кода и запись его в PHP-файл
				$PHPcode = '<?php'.PHP_EOL.'$id="'.$newArticleId.'";'.PHP_EOL.'require_once "content.php";'.PHP_EOL.'?>';
				file_put_contents($newArticleId.'.php', $PHPcode);

				// добавление новой статьи в БД
				global $con;
				$query = "INSERT INTO `content` VALUES ('$newArticleId', '$newArticleText')";
				mysqli_query($con, $query);

				// добавление ссылки на новую статью в боковое меню
				$query = "SELECT `text` FROM `content` WHERE id = 'menu'";
				$old_menu_html = mysqli_fetch_assoc(mysqli_query($con, $query))['text'] or die("Invalid query0: " . mysqli_error($con));
				$string_to_insert = '';
				if (strrpos($old_menu_html, '<p>Недавно добавленные</p>', 0) == false) {
					$string_to_insert = '<br><p>Недавно добавленные</p>';
				}
				$exitLink = '<p><a href="exit.php" target="_parent">Выход</a></p>';
				$string_to_insert = $string_to_insert.'<ul><li><a href="'.$newArticleId.'.php" target="content">'.$newArticleName.'</a></li></ul>'.$exitLink;
				$new_menu_html = str_replace($exitLink, $string_to_insert, $old_menu_html);
				$query = "UPDATE `content` SET `text` = '$new_menu_html' WHERE id = 'menu'";
				mysqli_query($con, $query);

				// обновление страницы
				echo '<script>parent.location.reload();</script>';
			}
		}
	}
	if (isset($_POST['editMenuItem'])) { 
		if ($_POST['selectedMenuItem'] != '') {
			$selectedMenuItem = $_POST['selectedMenuItem'];
			if ($_POST['newMenuItemName'] != '') {
				$newMenuItemName = $_POST['newMenuItemName'];
				echo "<script>var ans = confirm('Вы точно хотите переименовать пункт меню $selectedMenuItem в $newMenuItemName?')</script>";
				if ("<script>document.writeln(ans);</script>") {
					global $con;
					$query = "SELECT `text` FROM `content` WHERE id = 'menu'";
					$old_menu_html = mysqli_fetch_assoc(mysqli_query($con, $query))['text'] or die("Invalid query0: " . mysqli_error($con));
					$new_menu_html = str_replace('<li><a href="'.$selectedMenuItem.'.php" target="content">', '₴', $old_menu_html);
					$new_menu_html = preg_replace("/₴.*?\</u", '<li><a href="'.$selectedMenuItem.'.php" target="content">'.$newMenuItemName.'<', $new_menu_html);
					$query = "UPDATE `content` SET `text` = '$new_menu_html' WHERE id = 'menu'";
					mysqli_query($con, $query) or die("Invalid query1: " . mysqli_error($con));

					// обновление страницы
					echo '<script>parent.location.reload();</script>';
				}
			}
		}
	}
	if ($_SESSION['user']=="admin") {
		if ($id == 'logo') {
			$txt[0]['text']=htmlspecialchars_decode($txt[0]['text']);
			echo "
				<body>
					<div class='content'>".
						$txt[0]['text']."
					</div>
				</body>
			";	
		} else {
			if ($id == 'menu') {
				if (!isset($_POST['editMenu'])) {
					$txt[0]['text']=htmlspecialchars_decode($txt[0]['text']);
					echo "
						<form method='post'>
							<input type='submit' name='editMenu' class='editButton' value='Редактировать'/>
							<body>
								<div class='content'>".
									$txt[0]['text']."
								</div>
							</body>
						</form>
					";	
				}
			} else if ($id == 'edit') {
				echo "
					<form method='post'>
						<table style='border-width: 0'>
							<tr>
								<td style='border-width: 0; vertical-align: top' align=left>
									<p>Добавление новой статьи:</p>
									<input type='text' name='newArticleName' placeholder='Введите название новой статьи' style='width: 300px'/>
								</td>
								<td style='border-width: 0; width: 100%'>
									<textarea id='editor1' name='txt' cols='100' rows='50'>".$txt[0]['text']."</textarea>
									<script type='text/javascript'>
										var ckeditor1 = CKEDITOR.replace('editor1', {width: '100%', height: 100});
									</script>
								</td>
								<td style='border-width: 0; vertical-align: bottom'>
									<input type='submit' name='addArticle' class='addArticle' value='Добавить статью'/>
								</td>
							</tr>
							<tr>
								<td style='border-width: 0' align=left>
									<p>Удаление статьи:</p>
								</td>
								<td style='border-width: 0' align=left>
									<select name='selectedArticleName' id='selectedArticleName'>
										<option style='display:none' value=''>Выберите название статьи</option>
				";
				global $con;
				$query = "SELECT id FROM `content`";
				$res = mysqli_query($con, $query);
				while ($row = mysqli_fetch_assoc($res)) {
					if (($row["id"] != 'logo') && ($row["id"] != 'menu') && ($row["id"] != 'edit'))
						echo "			<option value='".$row['id']."' id='".$row['id']."_item'>".$row['id']."</option>";
				}
				echo "
									</select>
								</td>
								<td style='border-width: 0'>
									<input type='submit' name='delArticle' class='delArticle' value='Удалить статью'/>
								</td>
							</tr>
							<tr>
								<td style='border-width: 0' align=left>
									<p>Редактирование пунктов меню:</p>
								</td>
								<td style='border-width: 0' align=left>
									<select name='selectedMenuItem' id='selectedMenuItem'>
										<option style='display:none' value=''>Выберите название пункта меню</option>
				";
				global $con;
				$query = "SELECT id FROM `content`";
				$res = mysqli_query($con, $query);
				while ($row = mysqli_fetch_assoc($res)) {
					if (($row["id"] != 'logo') && ($row["id"] != 'menu') && ($row["id"] != 'edit'))
						echo "			<option value='".$row['id']."' id='".$row['id']."_item_2'>".$row['id']."</option>";
				}
				echo "
									</select>
									<input type='text' name='newMenuItemName' placeholder='Введите новое название пункта меню' style='width: 350px'/>
								</td>
								<td style='border-width: 0;'>
									<input type='submit' name='editMenuItem' class='editMenuItem' value='Редактировать пункт меню'/>
								</td>
							</tr>
						</table>
					</form>
				";
			} else if ($id != 'logo') {
				if (!isset($_POST['editText'])) {
					$txt[0]['text']=htmlspecialchars_decode($txt[0]['text']);
					echo "
						<form method='post'>
							<input type='submit' name='editText' class='editButton' value='Редактировать'/>
							<body>
								<div class='content'>".
									$txt[0]['text']."
								</div>
							</body>
						</form>
					";	
				}
			}
		}
		
	} else {
		$txt[0]['text']=htmlspecialchars_decode($txt[0]['text']);
		echo "
			<body>
				<div class='content'>".
					$txt[0]['text']."
				</div>
			</body>
		";	
	}
?>