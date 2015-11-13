<?php

$config = array(
	'username' => 'root', 
	'password' => 'root'
);

//nessesary functions-------------------------------
function connect($config){
	try {
		//Connects to db. Expecting (id,email,password,totalscore) 
		$conn = new PDO('mysql:host=localhost;dbname=spanish', $config['username'], $config['password']);

		//Dev only. Shows all PDO errors
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//comment
		return $conn;


	} catch (exeption $e) {
		return false;
	}
}

//Returns all from table. 
function get($tableName, $conn)
{
	try {
		$results = $conn->query("SELECT * FROM $tableName");
		return ($results->rowCount() > 0)
			? $results
			: false;
	} catch (Exception $e) {
		return false;
	}
}

//main func for db querys
//pass sql query with bindings in array.
function query($query, $bindings, $conn)
{
	$stmt = $conn->prepare($query);
	$stmt->execute($bindings);
	$results =  $stmt->fetchAll();
	return $results ? $results : false;
}

//when using INSERT i use insertquery
//lazy way of deeling with INSERT not returning anything
//could be cleaned up
function insertquery($query, $bindings, $conn)
{
	$stmt = $conn->prepare($query);
	$stmt->execute($bindings);

	try {
		$stmt->fetchAll();
		$results =  $stmt->fetchAll();
	} catch (Exception $e) {
	}

	if (isset($results)) {
		return $results ? $results : false;
	}	
}






