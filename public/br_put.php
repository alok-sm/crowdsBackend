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
	
	$sql = "REPLACE INTO br_state VALUES ('$key','$val')";
	
	if ($conn->query($sql) === TRUE) {
		$retarr = array('success'=>TRUE);
		echo json_encode($retarr);
	} else {
		$retarr = array('success'=>FALSE);
		echo json_encode($retarr);
	}

	$conn->close();
?> 
