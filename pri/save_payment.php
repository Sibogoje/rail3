<?php
session_start();
include "../database/conn.php";

// Debugging: Log the received POST data
file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);

$response = ['success' => false, 'message' => 'Invalid request.'];

if (isset($_POST['tenant']) && isset($_POST['amount'])) {
    $tenant = $_POST['tenant'];
    $amount = $_POST['amount'];
    $month = $_POST['month'];
    $date = date("Y-m-d");

    // Validate the data
    if (!empty($tenant) && is_numeric($amount) && $amount > 0) {
        // Fetch house code for the tenant
        $houseQuery = "SELECT autoid FROM house WHERE tcode = '$tenant'";
        $houseResult = mysqli_query($conn, $houseQuery);
        $houseRow = mysqli_fetch_assoc($houseResult);
        $houseId = $houseRow['autoid'] ?? null;

        if (!$houseId) {
            $response['message'] = "House not found for the tenant.";
            echo json_encode($response);
            exit;
        }

        // Check if a record exists in the `paid` table for this tenant
        $paidQuery = "SELECT id, amount FROM paid WHERE tenant = '$tenant'";
        $paidResult = mysqli_query($conn, $paidQuery);
        if ($paidRow = mysqli_fetch_assoc($paidResult)) {
            // Update the existing record
            $newAmount = $paidRow['amount'] + $amount;
            $updateQuery = "UPDATE paid SET amount = '$newAmount', date = '$date' WHERE id = " . $paidRow['id'];
            mysqli_query($conn, $updateQuery);
        } else {
            // Insert a new record
            $insertQuery = "INSERT INTO paid (house, tenant, amount, date) VALUES ('$houseId', '$tenant', '$amount', '$date')";
            mysqli_query($conn, $insertQuery);
        }

        $response = ['success' => true, 'message' => "Payment recorded successfully."];
    } else {
        $response['message'] = 'Invalid tenant or amount.';
    }
}

echo json_encode($response);
?>
