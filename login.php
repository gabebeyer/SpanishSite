<?php 

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();

	require 'functions.php';

	$conn = connect($config);

	$username = $_POST['username'];	
	$password = $_POST['password'];

	if ($username && $password) {
		try {
			$row = query("SELECT * FROM users WHERE username = :username" , array('username' => $username), $conn);
		} catch (Exception $e) {
			echo "Trouble connecting to database";
		}
	}else{	
		echo "
            <script type=\"text/javascript\">
           		alert('please enter username and password')    
            	window.location = './login_html.php'
            </script>
        ";
        die();
	}

	if ($username == $row[0]['username'] && $password == $row[0][$password]) {
			$_SESSION['CurrentUser'] = $username;
			header('Location: ./index.php');    
		}else{
			echo "
            <script type=\"text/javascript\">
           		alert('wrong username and/or password')    
          	 	window.location = './login_html.php'
            </script>
        ";
	}






?>
