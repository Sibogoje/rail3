<?php
// Include your database connection code here
include "../database/conn.php"; // Replace with the actual code to establish a database connection

if (isset($_POST['invoicenumber'])) {
    $selectedInvoiceNumber = $_POST['invoicenumber'];

    $query = "SELECT tenant, paid, electricity_charge, water_charge FROM invoices WHERE invoicenumber = ?";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedInvoiceNumber);
    $stmt->execute();
    
    // Get the result set as an associative array
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $tenant = $row['tenant'];
        $paid = $row['paid'];
        $electricity_charge = $row['electricity_charge'];
        $water_charge = $row['water_charge'];

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
        
        $data = array(
            'balance' => $balance,
            'surplus' => $credit,
            'total_charge' => $electricity_charge + $water_charge
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
