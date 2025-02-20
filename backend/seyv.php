<?php
session_start();
include '../database/conn.php';
if(isset($_POST['addinva']))
//echo "checking";	    
			$minprevwater=$_POST['minprevwater'];
			$minprevelec=$_POST['minprevelec'];
			$twater=$_POST['twater'];
			$telec=$_POST['telec'];
			$tnt=$_POST['tnt'];
			$month = $_POST['mnts'];
			$years = date("Y");
			$billdate = date("d/m/Y");
			$id = $tnt.$month.$years;

			$currentelec=$_POST['currentelec'];
			$currectwat=$_POST['currectwat'];
			$comments=$_POST['comments'];

			$previouswater=$_POST['ffprevwater'];
			$previouselectricity=$_POST['ffprevelec'];

			
				
			
				$digits = 4; 
				$randomise =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT); 




   $tquery = "SELECT MAX(invoicenumber) AS LargestPrice FROM invoices; ";
   $tresult8 = mysqli_query($conn, $tquery);

   // get the query result without the while loop
   $rowq = mysqli_fetch_array($tresult8);
  $hh = $rowq['LargestPrice'];
   $topidcount = $hh +1;

				
				
$ste = "SELECT station, region, tcode FROM house WHERE housecode='$tnt' ";
$res = $conn->query($ste);

if ($res->num_rows > 0) {
  // output data of each row
  while($rowd = $res->fetch_assoc()) {
      
      
    //  echo "suceess selecting";
      
      
      $stationz = $rowd['station'];
       $regionz = $rowd['region'];
       $tent = $rowd['tcode'];
    
		
																																							
			
			if($minprevwater !=""   && $billdate!=""){
			$sql = "INSERT INTO `invoices`(`id`,`tenant`, `w_units`, `e_units`,`electricity_charge`, `water_charge`, `month`, `year`, `billdate`,  `house_code`,`currentelec`, `currentwat`, `prevwater`, `prevelec`, `comments`, `invoicenumber`, `station`) 
			VALUES ('$id','$tent','$minprevwater','$minprevelec','$telec', '$twater', '$month','$years' , '$billdate', '$tnt', '$currentelec', '$currectwat', '$previouswater', '$previouselectricity', '$comments', '$topidcount', '$stationz')";
			if (mysqli_query($conn, $sql)) {
				//echo json_encode(array("statusCode"=>200));
	header('Location: ../billing.php');
			
			}else {
				//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			echo mysqli_error($conn);
			}
			//mysqli_close($conn);
		}else{
		    echo "something is empty";
		}
		
  }
} else {
  echo "0 results";
}

			//	}
	
			




?>