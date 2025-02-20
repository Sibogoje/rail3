<?php
// Include your database connection code here
include "database/conn.php"; // Replace with the actual code to establish a database connection

if (isset($_POST['invoicenumber'])) {
    $selectedInvoiceNumber = $_POST['invoicenumber'];
 $t_charge = 0.00; 
 $e_units == 0.00;
    $query = "SELECT * FROM invoices WHERE invoicenumber = ?";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedInvoiceNumber);
    $stmt->execute();
    
    // Get the result set as an associative array
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $tenant = $row['house_code'];
        $paid = $row['paid'];
        $electricity_charge = $row['electricity_charge'];
        $water_charge = $row['water_charge'];
        $e_units = $row['e_units'];
        $w_units = $row['w_units'];

        $selectpayments = "SELECT balance, credit FROM `payments` WHERE `house`=?";
        $stmt2 = $conn->prepare($selectpayments);
        $stmt2->bind_param("s", $tenant);
        $stmt2->execute();

        // Get the result set as an associative array
        $result2 = $stmt2->get_result();
        
        if ($row2 = $result2->fetch_assoc()) {
            $balance = $row2['balance'];
            $credit = $row2['credit'];
        } else {
            $balance = 0;
            $credit = 0;
        }
       
    
     if ($e_units == 0){
                	$w = 0.0;
                	$vw = 0;
                	 $bn = 0;
                	 $e_charge = 0.00;
                }else if ($e_units<101 && $e_units>0){
                	$w = $electricity_charge;
                	 $bn = 180.00;
                	 $e_charge = 180.00;
                }else{
                	$vw = 	($e_units - 100) * 1.8;
                	$e_charge = $vw + 180;
                	$bn = 180.00;
                }
    
    
  /////////////////////////////////////
  $ssubtotal = 0;

if ($w_units < 11) {
    $B1 = 80.92;
    $ssubtotal = $B1 + 93.88;
} elseif ($w_units < 16) {
    $temp0 = 10;
    $B1 = 80.92;
    $RR = $w_units;
    $temp1 = $RR - $temp0;
    $B2 = $temp1 * 21.08; 
    $ssubtotal = $B1 + $B2 + 93.88;
} elseif ($w_units < 51) {
    $temp0 = 10;
    $B1 = 80.92;
    $temp1 = 5;
    $B2 = $temp1 * 21.08;
    $temp2 = $w_units - 15;
    $B3 = $temp2 * 31.74; 
    $RR = $w_units;
    $RR = $RR - $temp0;
    $RR = $RR - $temp1;
    $ssubtotal = $B1 + $B2 + $B3 + 93.88;
} elseif ($w_units > 50) {
    $temp0 = 10;
    $B1 = 80.92;
    $temp1 = 5;
    $B2 = $temp1 * 21.08;
    $temp2 = 34;
    $B3 = $temp2 * 31.74; 
    $RR = $w_units;
    $RR = $RR - $temp0;
    $RR = $RR - $temp1;
    $temp3 = $RR - $temp2;
    $B4 = $temp3 * 35.22;    
    $ssubtotal = $B1 + $B2 + $B3 + $B4 + 93.88;
}
    
   $rounded_subtotal = round($ssubtotal, 2);
/////////////////////////////////////////////////////////////////////// 
    
        
        if ($e_units == 0.00){
            $t_charge = $rounded_subtotal;
        }else if ($w_units == 0.00){
           $t_charge = 	$e_charge;
          

        }else{
            $t_charge = $e_charge + $rounded_subtotal;
        }
        
        
        
        $data = array(
            'balance' => $balance,
            'surplus' => $credit,
            'paid' => $paid,
            'w_units' => $w_units,
            'e_units' => $e_units,
            'total_charge' => $t_charge 
        );

        // Return the data as JSON
        echo json_encode($data);
    } else {
        // Handle the case where no data was found for the selected invoicenumber
        echo json_encode(array('error' => 'No data found'));
    }
    
    // Close the statements
    $stmt2->close();
    $stmt->close();
} else {
    // Handle the case where 'invoicenumber' is not set in the POST data
    echo json_encode(array('error' => 'No invoicenumber selected'));
}

?>
