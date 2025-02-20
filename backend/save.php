<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log'); 
include '../database/conn.php';
if(count($_POST)>0){
	if($_POST['type']==1){
	    
			$housecode=$_POST['housecode'];
			$tenant=$_POST['tenant'];
			$station=$_POST['station'];
			$emater=$_POST['emeter'];
			$wmeter=$_POST['wmeter'];
			
			
			
			
			
			
		if($housecode !=""){
			$sql = "INSERT INTO `house`(`housecode`, `tcode`, `station`,`electricity_meter`,`water_meter`) 
			VALUES ('$housecode','$tenant','$station','$emater', '$wmeter')";
			if (mysqli_query($conn, $sql)) {
				echo json_encode(array("statusCode"=>200));
			} 
			else {
				//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
					echo json_encode(array("statusCode"=>206));
			}
			mysqli_close($conn);
		}
		else{
				echo json_encode(array("statusCode"=>206));
		}
	}
}



if (count($_POST) > 0) {
    if ($_POST['type'] == 6) {
        $minprevwater = floatval($_POST['minprevwater']);
        $minprevelec = floatval($_POST['minprevelec']);
        $twater = floatval($_POST['twater']);
        $telec = floatval($_POST['telec']);
        $tnt = $_POST['tnt'];
        $month = $_POST['mnts'];
        $years = date("Y");
        $billdate = date("d/m/Y");
        $what = $_POST['what'];
        $id = $tnt . $month . $years . "-" . $what;
        $housecode = $_POST['tnt'];
        $currentelec = floatval($_POST['currentelec']);
        $currectwat = floatval($_POST['currectwat']);
        $comments = $_POST['comments'];
        $pre_sewage = floatval($_POST['pre_sewage']);
        $pre_sewage1 = floatval($_POST['pre_sewage']) + floatval($_POST['curr_sewage']);
        $curr_sewage = floatval($_POST['curr_sewage']);
        $previouswater = floatval($_POST['ffprevwater']);
        $previouselectricity = floatval($_POST['ffprevelec']);
        $checksewage = $_POST['zeroSewage'];

        $digits = 4;
        $randomise = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);

        $tquery = "SELECT MAX(invoicenumber) AS latest_invoice FROM invoices;";
        $tresult8 = mysqli_query($conn, $tquery);
        $rowq = mysqli_fetch_array($tresult8);
        $hh = $rowq['latest_invoice'];
        $topidcount = $hh + 1;

        $ste = "SELECT station, region, tcode FROM house WHERE housecode= '$housecode'";
        $res = $conn->query($ste);

if ($res->num_rows > 0) {
    // Output data of each row
    while ($rowd = $res->fetch_assoc()) {
        $stationz = $rowd['station'];
        $regionz = $rowd['region'];
        $tent = $rowd['tcode'];
        
        if (!empty($_POST['zeroSewage'])) {
            // Checkbox is checked, set values to zero
            $pre_sewage1 = 0;
            $curr_sewage = 0; 
            $sewage_charge = 0;
        } else {
            // Make sure $curr_sewage is set or retrieved
            if (isset($curr_sewage)) {
                if ($curr_sewage <= 11.12) {
                    $sewage_charge = 83.90;
                } else {
                    $sewage_charge = (($curr_sewage - 11.12) * 13.73) + 83.90;
                    $sewage_charge = round($sewage_charge, 2);  // Round to 2 decimal places
                }
            } else {
                // Handle the case where $curr_sewage is not defined
                $sewage_charge = 0;  // or handle appropriately
            }
        }

        // Debugging
        error_log("Current sewage: $curr_sewage, Sewage charge: $sewage_charge");

        if ($minprevwater != "" && $minprevelec != "" && $telec != "" && $billdate != "") {
            $sql = "INSERT INTO `invoices`(`id`, `tenant`, `w_units`, `e_units`, `electricity_charge`, `water_charge`, `month`, `year`, `billdate`, `house_code`, `currentelec`, `currentwat`, `prevwater`, `prevelec`, `comments`, `invoicenumber`, `station`, `prev_sewage`, `curr_sewage`, `sewage_charge`) 
            VALUES ('$id', '$tent', '$minprevwater', '$minprevelec', '$telec', '$twater', '$month', '$years', '$billdate', '$tnt', '$currentelec', '$currectwat', '$previouswater', '$previouselectricity', '$comments', '$topidcount', '$stationz', '$pre_sewage1', '$curr_sewage', '$sewage_charge')";
            
            // Log the SQL statement
            error_log("SQL: $sql");

            if (mysqli_query($conn, $sql)) {
                echo "<script type='text/javascript'>
                        alert('Invoice Processed Successfully!!');
                        window.location.href = '../billing.php';
                      </script>";
                exit();
            } else {
                $response = array(
                    'statusCode' => 201,
                    'error' => mysqli_error($conn)
                );
                echo json_encode($response);
                exit();
            }
        } else {
            $response = array(
                'statusCode' => 202
            );
            echo json_encode($response);
        }
    }
} else {
    $response = array(
        'statusCode' => $housecode
    );
    echo json_encode($response);
}

      
        
        
    } else {
        $response = array(
            'statusCode' => 204
        );
        echo json_encode($response);
    }
}


if(count($_POST)>0){
	if($_POST['type']==2){
		$housecode=$_POST['uhousecode'];
		$tenant=$_POST['utenant'];
		$autoid=$_POST['uautoid'];
		$station=$_POST['ustation'];
		$emeter=$_POST['uemeter'];
		$wmeter=$_POST['uwmeter'];
		
		$sql = "UPDATE `house` SET `tcode`='$tenant',`station`='$station',`electricity_meter`='$emeter', `water_meter`='$wmeter', `housecode`='$housecode'  WHERE  `autoid`=$autoid";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}





if(count($_POST)>0){
	if($_POST['type']==3){
		$id=$_POST['id'];
		$sql = "DELETE FROM house WHERE `housecode` = '$id'";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==4){
		$id=$_POST['id'];
		$sql = "DELETE FROM user WHERE id in ($id)";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}

?>