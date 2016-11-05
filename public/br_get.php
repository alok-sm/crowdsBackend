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
		
	$sql = "SELECT * FROM br_state WHERE u_key='$key'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
		    $retarr = array('success'=>TRUE,'data'=>$row['u_val']); 
		    echo json_encode($retarr);
		    break;
		}
    	}

	else 
	{
		$retarr = array('success'=>FALSE);
		echo json_encode($retarr);
	}

	$conn->close();
?> 
