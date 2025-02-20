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
				

				//	$tcode = $line[0];
					$name = $line[0];
					$housecode = $line[1];
				//	$emeter = $line[3];
				//	$wmeter = $line[4];
					
				
						// Insert member data in the database
						$conn->query("INSERT INTO tenant (`name`, `station`) VALUES ('$name','$housecode')");
					
				}
				
				// Close opened CSV file
				fclose($csvFile);
        header("Location: ../tenants.php"); 
				
				//$qstring = '?status=succ';
			}else{
        echo "<script> alert('FIle was not uploaded, try again'); window.location.href='../tenants.php'; </script>";
				
			//	$qstring = '?status=err';
			}
		}else{
      echo "<script> alert('File is not csv'); window.location.href='../tenants.php'; </script>";
				
			//$qstring = '?status=invalid_file';
		}



}
?>