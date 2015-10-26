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

		return $conn;
		
	} catch (exeption $e) {
		return false;
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

//pass sql query with bindings in array.
function query($query, $bindings, $conn)
{
	$stmt = $conn->prepare($query);
	$stmt->execute($bindings);
	$results =  $stmt->fetchAll();
	return $results ? $results : false;
}

