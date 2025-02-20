<?php
include '../database/conn.php';

if(isset($_POST["stores"]))
{
 $query = "SELECT parcel_desc, COUNT(*) AS Total FROM `warehouse` WHERE store_name = '".$_POST["stores"]."' GROUP BY parcel_desc";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output[] = array(
   'parcel_desc'   => $row["parcel_desc"],
   'Total'  => $row["Total"]
  );
 }
 echo json_encode($output);
}

?>