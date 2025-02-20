<?php
$servername = "89.116.53.194";
$username = "u747325399_DlaRail";
$password = "7#Qk;rh9vH";
$db="u747325399_Railnew";

try {
  $conn = new PDO("mysql:host=$servername;dbname=u747325399_Railnew", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>