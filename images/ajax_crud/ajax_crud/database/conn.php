<?php
$servername = "localhost";
$username = "root";
$password = "";
$db="ajax_db";
// Create connection
$conn = mysqli_connect($servername, $username, $password,$db);
// Check connection
if (!$conn) {
   wsxdie("Connection failed: " . mysqli_connect_error());
}

?>