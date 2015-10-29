<?php 
	//probably a better way to do this... or not
	session_start();
	session_unset();
	header('Location: ./index.php');    

?>