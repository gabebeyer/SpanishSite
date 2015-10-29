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
	echo "TOTAL SCORE IS " . $totalScore;

?>















