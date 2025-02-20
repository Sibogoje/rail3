<?php 
include "../database/conn.php";

$result1 = mysqli_query($conn,"SELECT * FROM `house` where `tcode`='Zodvwa_Manyatsi' ");
              // echo $clientquery.$inyanga.$sterr;
if ($result1->num_rows > 0) {

while($row = $result1->fetch_assoc()) {
    
    $ui = $row['tcode'];
     $ff = $row['housecode'];
    
    $sql = "UPDATE invoices SET tenant='$ui' WHERE house_code='$ff' ";

if ($conn->query($sql) === TRUE) {
  echo "Record updated successfully";
} else {
  echo "Error updating record: " . $conn->error;
}

    
    
    
}
    
    
}else{
     echo "Nothing was found: " . $conn->error;
}


?>
