<?php 
	
	//pretty much the same prosses as login
	
	session_start();

	require 'functions.php';

	$conn = connect($config);

	$username = $_POST['username'];	
	$password = $_POST['password'];

	if ($username && $password) {		

		$userExistsCheck = query("SELECT * FROM users WhERE username = :username",
			array('username' => $username ),
			$conn);

		if($userExistsCheck) {
			echo "
            	<script type=\"text/javascript\">
           			alert('Username Taken')    
            		window.location = './register_html.php'
           		</script>";
		}else{

			$row = insertquery("INSERT INTO users (id,username,password) VALUES (NULL,:username,:password);" , 
			array('username' => $username, 'password' => $password), 
			$conn);
			$_SESSION['CurrentUser'] = $username;		
			header('Location: ./index.php');    	
		}
	}else{	
		echo "
            <script type=\"text/javascript\">
           		alert('please enter username and password')    
            	window.location = './login_html.php'
            </script>";
        die();
	}






