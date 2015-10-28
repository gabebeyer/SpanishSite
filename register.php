<?php 

	
	session_start();

	require 'functions.php';

	$conn = connect($config);

	$username = $_POST['username'];	
	$password = $_POST['password'];

	if ($username && $password) {	
		$row = insertquery("INSERT INTO users (id,username,password) VALUES (NULL,:username,:password);" , 
		array('username' => $username, 'password' => $password), 
		$conn);
		header('Location: ./index.php');    
	}else{	
		echo "
            <script type=\"text/javascript\">
           		alert('please enter username and password')    
            	window.location = './login_html.php'
            </script>
        ";
        die();
	}





