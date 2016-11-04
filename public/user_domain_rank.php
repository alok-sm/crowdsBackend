<?php
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json");
		
	$servername = "localhost";
	$username = "crowd_user";
	$password = "lakj2345sl4h";
	$dbname = "crowd";
	
	extract($_GET);
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
    	die("Connection failed");
	}
	
	$sql = "INSERT INTO user_domain_rank VALUES ('$user_id','$domain_id','$rank')";
	
	if ($conn->query($sql) === TRUE) {
		echo json_encode(true);
	} else {
		echo json_encode(false);
	}

	$conn->close();
?> 