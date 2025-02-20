<?php
session_start();
include 'database/conn.php';
if(isset($_POST['delete'])){
//echo "checking";	    
			$id=$_POST['tenanted'];
			$month = $_POST['mnm'];
			
			$sql = "DELETE FROM invoices WHERE house_code='$id' AND month = '$month' ";
			
			

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Invoice Deleted Succesfully'); window.location.href='billing.php';</script>";
  //header('Location: billing.php');
} else {
   echo "<script>alert('There was an error Deleting'); window.location.href='billing.php';</script>";
}
			
  
} else {
  echo "0 results";
}
	?>
