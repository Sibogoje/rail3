<?php

require_once '../database/conn.php';

if(isset($_POST["submit_file"]))
{


		// Allowed mime types
		$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
		
		// Validate whether selected file is a CSV file
		if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
			
			// If the file is uploaded
			if(is_uploaded_file($_FILES['file']['tmp_name'])){
				
				// Open uploaded CSV file with read-only mode
				$csvFile = fopen($_FILES['file']['tmp_name'], 'r');
				
				// Skip the first line
				fgetcsv($csvFile);
				
				// Parse data from CSV file line by line
				while(($line = fgetcsv($csvFile)) !== FALSE){
					// Get row data
				

					$housecode = $line[0];
					$tcode = $line[1];
					$station = $line[2];
					$emeter = $line[3];
					$wmeter = $line[4];
					
				
						// Insert member data in the database
						$conn->query("INSERT INTO house (`housecode`, `tcode`, `station`, `electricity_meter`, `water_meter`) VALUES ('$housecode','$tcode',' $station', '$emeter', '$wmeter')");
					
				}
				
				// Close opened CSV file
				fclose($csvFile);
        header("Location: ../houses.php"); 
				
				//$qstring = '?status=succ';
			}else{
        echo "<script> alert('FIle was not uploaded, try again'); window.location.href='../houses.php'; </script>";
				
			//	$qstring = '?status=err';
			}
		}else{
      echo "<script> alert('File is not csv'); window.location.href='../houses.php'; </script>";
				
			//$qstring = '?status=invalid_file';
		}


/*
 $file = $_FILES["file"]["tmp_name"];
 $file_open = fopen($file,"r");
 while(($csv = fgetcsv($file_open, 1000, ",")) !== false)
 {
  $housecode = $csv[0];
  $tcode = $csv[1];
  $station = $csv[2];
  $emeter = $csv[3];
  $wmeter = $csv[4];
 
 //$result3 = mysqli_query($conn,"SELECT * FROM house where housecode='$housecode' ");
	$sql = "SELECT * FROM house where housecode='$housecode' ";				
				if ($result3 = mysqli_query($conn, $sql)) {


    $rowcount = mysqli_num_rows( $result3 );
    if ($rowcount<1){
		
		  $sql = "INSERT INTO house (`housecode`, `tcode`, `station`, `electricity_meter`, `water_meter`) VALUES ('$housecode','$tcode',' $station', '$emeter', '$wmeter')";
  
  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    header("Location: ../houses.php"); 
    exit();
  } else {
    
	echo "<script> alert('There was a problem, check for duplication !'); window.location.href='../houses.php'; </script>";
	//header ("Location: ../houses.php");
  }
		
	}else{
		 echo "Duplicate key";
		
	}

 }


  
  $conn->close();

 }

 */
}
?>