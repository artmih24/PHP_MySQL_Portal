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
							var ckeditor1 = CKEDITOR.replace('editor1');
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
							var ckeditor1 = CKEDITOR.replace('editor1');
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