<?php

session_start();
include 'connection.php';


if ( isset( $_POST['clerksubmit'] ) ) {
$id = $_POST['clerkusername'];
$pass = $_POST['clerkpass'];
$sql = "SELECT * FROM `users` WHERE `userid`='$id' AND `password` ='$pass' ";
$result = $conn->query($sql);

if ($result->rowCount() > 0) {


    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION["id"] = $row['userid'];
      }

    if(isset($_SESSION["id"])) {
        header('Location: ../home.php');
        }
  // output data of each row
 
} else {
  echo "0 results";
}
//$conn->close();

}
?>