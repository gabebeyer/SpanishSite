<?php 
	session_start();
	require 'functions.php';
	$conn = connect($config);

	//used to define how many times you must get a word right/wrong
	//in order for you to "know" the word
	$WRONG_AMOUNT = 5;
	$RIGHT_AMOUNT = 3;
	$CLASS_REWARD = 5;

	//no need to check if user is logged in, gets here from names link only (navbar)
	//will error if no session available (they typed in the url)
	$UserRow = query("SELECT * FROM users WHERE username = :username",
			   array('username' => $_SESSION['CurrentUser']),
			   $conn);	

	//the id of the current user
	$id = strval($UserRow[0]["id"]);
	$period_id = $UserRow[0]["period"];

	//i build a classmates array, of all the other users in my "Class"
	//this is used to show a specific progress bar for each period
	$classmates = [];
	foreach ( query("SELECT * FROM users WHERE period = :period", array('period' => $period_id),$conn) as $key) {
		array_push($classmates, $key['id']);
	}

	//here we start to generate a list of words 
	// 2 lists of "Known" and "unkown" words
	//defined as amount of right/wrong awnsers on a word in past x weeks 

	//how long we want to go back
	//any thing before this date is not even considered at all
	$rem_date = date('Y-m-d H:i:s',time()-(7*86400));
		
	//scores is a table with score_id userid word correct and a timestamp
	//stores all awnsers ever on the site
	$scores = query("SELECT * FROM scores WHERE userid = :id",
			  array('id' => $id),
			  $conn);
	
	//list of all right/wrong words from last 2 weeks
	$wrongWords = array();
	$rightWords = array();
	if ($scores) {
		foreach ($scores as $score) {
			//get some relevant data
			$word = $score["word"];
			$timestamp = $score["timestamp"];
			$correct = intval($score["correct"]);
			//sort it into to lists of right and wrong words.
			//i like lists man idk i use them alot
			//if ($correct == 1 && $timestamp >= $rem_date) {
			if ($correct == 1) {
				array_push($rightWords, $word);
			}elseif ($correct == 0) {
				array_push($wrongWords, $word);# code...
			}else{
			}
		}
	}

	//Define someNumber as the number of times till you "know" a word
	// returns wordsKnowk and problem_Words
	//these new lists take into account # of occurences, 
	function knownWords($rightWords,$someNumber)
	{
		$wordsKnown = array();
		$word_counts = array_count_values($rightWords);
		asort($word_counts);
		foreach ($word_counts as $word => $count) {
			if ($count >= $someNumber) {
				array_push($wordsKnown, $word );			
			}
		}
		return $wordsKnown;
	}
	function problemWords($wrongWords,$someNumber)
	{
		$problem_Words = array();
		$word_counts = array_count_values($wrongWords);
		asort($word_counts);
		foreach ($word_counts as $word => $count) {
			if ($count >= $someNumber) {
				array_push($problem_Words, $word);				
			}
		}
		return $problem_Words;
	}

	$initial = query("SELECT * FROM scores WHERE correct = 1 AND cashedIn = 0",
			   				array(),
			   				$conn);	
	$totalToCount = 0;


	if (!empty($initial)) {
		foreach ($initial as $score) {
		if (in_array( strval($score["userid"]) , $classmates)) {
			$totalToCount+=1;
		} 
	}
	
	}
	
	//if the total is greater then our goal
	if ($totalToCount > $CLASS_REWARD) {
		//loop through correct unchashed awnsers
			foreach ($initial as $score) {
			//if awnser is from a classmate
			if (in_array( strval($score["userid"]) , $classmates) ) {
				//set score as cashed
				$updater = insertquery("UPDATE scores SET cashedIn = 1 WHERE score_id = :scoreid;",
							array('scoreid' => $score["score_id"] ),
							$conn);
			}
		}

		//neesds to be updated
		mail("SET TO ELLES EMAIL","subject", "the class $period_id has scored");
		//then refresh page
		header("Refresh:0");
	}
	$percenetComplete =  ($totalToCount/$CLASS_REWARD) * 100;


?>

<?php require("html_imports.php"); ?>
<!DOCTYPE html>
<html>
<head>
<title>User page</title>
<?php require("navbar.php"); ?>
</head>
	<body>
		<div class="row">
			<div style="padding:0 75px">
				<div class="col-md-6">
					<div class="panel panel-info">
					  <div class="panel-heading"> Words you know</div>
						  <ul class="list-group">
						    <?php 
						    	$counter = 0;
						    	$knownWordsComputed = knownWords($rightWords,$RIGHT_AMOUNT);
						    	if (empty($knownWordsComputed)) {
						    		echo "<li class='list-group-item'> Sorry you have no recent words </li>";
						    	} else {
									foreach ($knownWordsComputed as $word) {
						    			if ($counter <= 5) {					    				
						    				//michael bay of code rn
						    				$pieces = explode(" ", $word);
											$search_word = implode(" ",array_slice($pieces, 1));
						    				$link = "<a href='http://www.wordreference.com/es/translation.asp?tranword=$search_word'>$word</a>";
						   					echo "<li class='list-group-item'> $link </li>";	
						    			}
										$counter += 1;
									}
						    	}
						     ?>
						  </ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-danger">
					  <div class="panel-heading"> Words you dont know</div>
						  <ul class="list-group">
						    <?php 
						    	$counter = 0;
						    	$problemWordsComputed = problemWords($wrongWords,$WRONG_AMOUNT);
						    	if (empty($wrongWords)){
						    		echo "<li class='list-group-item'> Sorry you have no recent words </li>";
						    	}else{
							    	foreach ($problemWordsComputed as $word) {
							    		if ($counter < 5) {
							    			$pieces = explode(" ", $word);
											$search_word = implode(" ",array_slice($pieces, 1));
							    			$link = "<a href='http://www.wordreference.com/es/translation.asp?tranword=$search_word'>$word</a>";							    		
							    			echo "<li class='list-group-item'> $link </li>";	
							    		}
										$counter += 1;
									}
								}
						     ?>
						  </ul>
					</div>
				</div>
			</div>
		</div>
	<!-- End of words you know/dont -->
	</body>
	<footer>
		<div class="row">
			<div style="padding:25 150px">				
				<div class="progress">
				  <div class="progress-bar" role="progressbar" style= "width: <?php echo $percenetComplete ;?>%">
				    	<span class="sr-only"><?php echo $correctTotal; ?>% complete</span>
				  </div>
				</div>
			</div>
		</div>
	</footer>
</html>































