<?php 
	session_start();
	require 'functions.php';
	$conn = connect($config);

	//used to define how many times you must get a word right/wrong
	//in order for you to "know" the word
	$WRONG_AMOUNT = 3;
	$RIGHT_AMOUNT = 3;

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
			  $conn);	;
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

	//Define someNumber as the number of times till you "know" a word
	function knownWords($rightWords,$someNumber)
	{
		//we will return an array of words known
		$wordsKnown = array();
		$word_counts = array_count_values($rightWords);
		foreach ($word_counts as $word => $count) {
			if ($count >= $someNumber) {
				array_push($wordsKnown, $word);				
			}
		}
		return $wordsKnown;
	}

	//Define someNumber as the number of times till you "know" a word
	function problemWords($wrongWords,$someNumber)
	{
		//we will return an array of words known
		$problem_Words = array();
		$word_counts = array_count_values($wrongWords);
		foreach ($word_counts as $word => $count) {
			if ($count >= $someNumber) {
				array_push($problem_Words, $word);				
			}
		}
		return $problem_Words;
	}
?>

<?php require("html_imports.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>User Pgae</title>
	<?php require("navbar.php"); ?>
</head>
<body>
	<div class="row">
		<div style="padding:0 75px">
			<div class="col-md-6">
				<div class="panel panel-info">
				  <!-- Default panel contents -->
				  <div class="panel-heading"> Words you know</div>
				  <!-- List group -->
					  <ul class="list-group">
					    <?php 
					    	$counter = 0;
					    	foreach (knownWords($rightWords,$RIGHT_AMOUNT) as $word) {
					    		if ($counter <= 5) {
					    			echo "<li class='list-group-item'>$word</li>";	
					    		}else{
					    			die();
					    		}
								$counter += 1;
							}
					     ?>
					  </ul>
				</div>
			</div>

			<div class="col-md-6">
				<div class="panel panel-danger">
				  <!-- Default panel contents -->
				  <div class="panel-heading"> Words you Dont know</div>
				  <!-- List group -->
					  <ul class="list-group">
					    <?php 
					    	$counter = 0;
					    	foreach (problemWords($wrongWords,$WRONG_AMOUNT) as $word) {
					    		if ($counter <= 5) {
					    			echo "<li class='list-group-item'>$word</li>";	
					    		}else{
					    			die();
					    		}
								$counter += 1;
							}
					     ?>
					  </ul>
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>































