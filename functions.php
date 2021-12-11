<?php

require_once 'config.php';

function selectText($id){
	global $con;
	$txt = array();
	$query = "SELECT id, text FROM ".tbl." WHERE id='$id'";
	$res = mysqli_query($con, $query);
	while($row = mysqli_fetch_assoc($res)){
		$txt[] = $row;
	}
	
	return $txt;
}

function updateText($txt, $id){
	global $con;
	$id = $id;
	$txt = mysqli_real_escape_string($con, $txt);
	$query = "UPDATE ".tbl." SET text='$txt' WHERE id='$id'";
	mysqli_query($con, $query);
	return selectText($id);
}
?>