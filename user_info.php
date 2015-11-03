<?php 
	session_start();
	require 'functions.php';
	$conn = connect($config);

	//used to define how many times you must get a word right/wrong
	//in order for you to "know" the word
	$WRONG_AMOUNT = 3;
	$RIGHT_AMOUNT = 3;
	$CLASS_REWARD = 100;

	//no need to check if user is logged in, gets here from names link only (navbar)
	//will error if no session available (they typed in the url)
	$UserRow = query("SELECT * FROM users WHERE username = :username",
			   array('username' => $_SESSION['CurrentUser']),
			   $conn);	



	$id = strval($UserRow[0]["id"]);
	
	$period_id = $UserRow[0]["period"];


	$classmates = [];
	foreach ( query("SELECT * FROM users WHERE period = :period", array('period' => $period_id),$conn) as $key) {
		array_push($classmates, $key['id']);
	}



	$rem_date = date('Y-m-d H:i:s',time()-(7*86400));
	$scores = query("SELECT * FROM scores WHERE userid = :id",
			  array('id' => $id),
			  $conn);
	
	//list of ALL right/wrong words from last 2 weeks
	$wrongWords = array();
	$rightWords = array();
	if ($scores) {
		foreach ($scores as $score) {
			$word = $score["word"];
			$timestamp = $score["timestamp"];
			$correct = intval($score["correct"]);
			if ($correct == 1 && $timestamp >= $rem_date) {
				array_push($rightWords, $word);
			}elseif ($correct == 0 && $timestamp >= $rem_date) {
				array_push($wrongWords, $word);# code...
			}else{
			}
		}
	}

	//Define someNumber as the number of times till you "know" a word
	function knownWords($rightWords,$someNumber)
	{
		//we will return an array of words known
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

	//Define someNumber as the number of times till you "know" a word
	function problemWords($wrongWords,$someNumber)
	{
		//we will return an array of words known
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
	$correct = query("SELECT * FROM scores WHERE correct = :right",
					  array('right' => 1),
					  $conn);

	$correctTotal = 0;
	foreach ($correct as $key) {
		if ($correctTotal < $CLASS_REWARD && in_array( strval($key["userid"]) , $classmates))  {
			$correctTotal += 1;
		}else{
			//in this case they have "Won"
			try {
				mail("beyergabe@gmail.com", 	$period_id . "has won", $period_id . "has won a reward! they scored " . $CLASS_REWARD . " points");
			} catch (Exception $e) {
				echo "congrates! notify teacher that you won";
			}
			$correctTotal = 0;
			break;
		}
	}   


	$percentComplete = ($correctTotal/$CLASS_REWARD) * 100;


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
						    	if (empty($rightWords)) {
						    		echo "<li class='list-group-item'> Sorry you have no recent words </li>";
						    	} else {
									foreach (knownWords($rightWords,$RIGHT_AMOUNT) as $word) {
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
					  <!-- Default panel contents -->
					  <div class="panel-heading"> Words you Dont know</div>
					  <!-- List group -->
						  <ul class="list-group">
						    <?php 
						    	$counter = 0;
						    	if (empty($wrongWords)){
						    		echo "<li class='list-group-item'> Sorry you have no recent words </li>";
						    	}else{
							    	foreach (problemWords($wrongWords,$WRONG_AMOUNT) as $word) {
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
				  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $correctTotal; ?>"
				  	aria-valuemin="0" aria-valuemax="<?php echo $CLASS_REWARD;?>" style= "width: <?php echo $percentComplete;?>%">
				    	<span class="sr-only"><?php echo $correctTotal; ?>% Complete</span>
				  </div>
				</div>
			
			</div>
		</div>

	</footer>
	

</html>































