<?php
session_start();
include "../database/conn.php";

// Debugging: Log the received POST data
file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);

$response = ['success' => false, 'message' => 'Invalid request.'];

if (isset($_POST['tenant']) && isset($_POST['amount'])) {
    $tenant = mysqli_real_escape_string($conn, $_POST['tenant']);
    $amount = floatval($_POST['amount']);
    $date = date("Y-m-d");

    // Validate the data
    if (!empty($tenant) && $amount > 0) {
        // Start a transaction to prevent race conditions
        mysqli_begin_transaction($conn);

        try {
            // Check if a record exists in the `paid` table for this tenant
            $paidQuery = "SELECT id, amount FROM paid WHERE tenant = '$tenant' FOR UPDATE";
            $paidResult = mysqli_query($conn, $paidQuery);

            if ($paidRow = mysqli_fetch_assoc($paidResult)) {
                // Update the existing record
                $newAmount = $paidRow['amount'] + $amount;
                $updateQuery = "UPDATE paid SET amount = '$newAmount', date = '$date' WHERE id = " . $paidRow['id'];
                mysqli_query($conn, $updateQuery);
            } else {
                // Insert a new record
                $insertQuery = "INSERT INTO paid (tenant, amount, date) VALUES ('$tenant', '$amount', '$date')";
                mysqli_query($conn, $insertQuery);
            }

            // Commit the transaction
            mysqli_commit($conn);

            $response = ['success' => true, 'message' => "Payment recorded successfully."];
        } catch (Exception $e) {
            // Rollback the transaction on error
            mysqli_rollback($conn);
            $response['message'] = "An error occurred while processing the payment.";
        }
    } else {
        $response['message'] = 'Invalid tenant or amount.';
    }
}

echo json_encode($response);
?>
