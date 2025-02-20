<?php
//address of the server where db is installed
$servername = "194.5.156.43";

//username to connect to the db
//the default value is root
$username = "u747325399_church";

//password to connect to the db
//this is the value you would have specified during installation of WAMP stack
$password = "Church@1";

//name of the db under which the table is created
$dbName = "u747325399_church";

//establishing the connection to the db.
$conn = new mysqli($servername, $username, $password, $dbName);

//checking if there were any error during the last connection attempt
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//the SQL query to be executed
$query = "SELECT station,COUNT(DISTINCT(autoid))FROM house GROUP BY station";
;
//storing the result of the executed query
$result = $conn->query($query);




//initialize the array to store the processed data
$jsonArray = array();

//check if there is any data returned by the SQL Query
if ($result->num_rows > 0) {
	
		
	
  //Converting the results into an associative array
while($row = $result->fetch_assoc()) {
    $jsonArrayItem = array();
    $jsonArrayItem['label'] = $row['station'];
	$jsonArrayItem['value'] = $row['COUNT(DISTINCT(autoid))'];
	// $jsonArrayItem['station'] = $row['station'];
	
	
array_push($jsonArray, $jsonArrayItem); 
}
}
//Closing the connection to DB
$conn->close();

//set the response content type as JSON
header('Content-type: application/json');
//output the return value of json encode using the echo function. 
echo json_encode($jsonArray);
?>
