<?php 
	session_start();
	require 'functions.php';
	$conn = connect($config);

	//no need to check if user is logged in, gets here from names link only (navbar)
	//will error if no session available (they typed in the url)
	echo $_SESSION['CurrentUser'];


	$UserRow = query("SELECT * FROM users WHERE username = :username",
			   array('username' => $_SESSION['CurrentUser']),
			   $conn);	

	echo "<br>";

	$id = $UserRow[0]["id"];
	echo "ID --> ".$id;

	$scores = query("SELECT * FROM scores WHERE userid = :id",
			  array('id' => $id),
			  $conn);	


	$totalScore = 0;
	if ($scores) {
		foreach ($scores as $score) {
			$correct = intval($score["correct"]);
			$totalScore = $totalScore + $correct;
		}	
	}
	echo "<br>";
	echo "TOTAL SCORE IS " . $totalScore . "<br>";

	$wrongWords = array();
	$rightWords = array();
	if ($scores) {
		foreach ($scores as $score) {
			$word = $score["word"];
			$correct = intval($score["correct"]);

			if ($correct == 1) {
				array_push($rightWords, $word);
			}else{
				array_push($wrongWords, $word);
			}
		}
	}


	echo "<br><h3>words you know</h3><br>";
	foreach ($rightWords as $word) {
		echo $word;	
		echo "<br>";
	}


	echo "<br><h3>words you dont know</h3><br>";

	foreach ($wrongWords as $word) {
		echo $word;	
		echo "<br>";
	}

?>
































