<?php 
	session_start();

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require 'functions.php';
	$conn = connect($config);

	if ($_SESSION['CurrentUser']) {
		$currentUsername = $_SESSION['CurrentUser'];
	}else{
		die();
	}

	$CurrentUserRow = query("SELECT * FROM users WHERE username = :username",
					  array('username' => $_SESSION['CurrentUser']),
					  $conn);


	$id = $CurrentUserRow[0]['id'];
	$word = $_GET['word'];
	$correct = $_GET['correct'];
	$timestamp =  date("Y-m-d H:i:s");

	$row = $insertQuery = query("INSERT INTO scores (score_id,userid,word,correct,timestamp) VALUES (NULL,:id,:word,:correct,:timestamp);",
					  array('id' => $id,
					  		'word' => $word,
					  		'correct' => $correct,
					  		'timestamp' =>$timestamp,
					  	),
					  $conn);


 ?>