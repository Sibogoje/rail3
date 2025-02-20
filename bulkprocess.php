<?php
// Assuming you have already connected to the database and have a function to sanitize inputs
require_once 'database/conn.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

//header('Content-Type: application/json');


function sanitize($data) {
    $data = trim($data); // Remove whitespace from the beginning and end of the string
    $data = stripslashes($data); // Remove backslashes
    $data = htmlspecialchars($data); // Convert special characters to HTML entities
    return $data;
}


    // Database connection
    $conn = new mysqli($servername, $username, $password,$db);

    // Check connection
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


///////////////////////////////////////////////


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['process'])) {
    $tenant = $_POST['company']; // Assuming 'company' is the tenant identifier
    $from = sanitize($_POST['from']);
    $to = sanitize($_POST['to']);
    $paymentAmount = floatval(sanitize($_POST['payment'])); // The entered payment amount
    $originalPaymentAmount = floatval(sanitize($_POST['payment'])); // The entered payment amount

    // Start database transaction
    $conn->begin_transaction();

    try {
        // Fetch relevant invoices
        $sql = "SELECT invoicenumber, balances FROM invoices WHERE tenant = ? AND STR_TO_DATE(billdate, '%d/%m/%Y') BETWEEN ? AND ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $tenant, $from, $to);
        $stmt->execute();
        $result = $stmt->get_result();

        // Process each invoice
            while (($row = $result->fetch_assoc()) && $paymentAmount > 0) {
                $invoiceId = $row['invoicenumber'];
                $invoiceBalance = $row['balances'];

            $amountApplied = min($invoiceBalance, $paymentAmount); // The amount to apply to this invoice
            $newBalance = $invoiceBalance - $amountApplied; // New balance for this invoice
            $paymentAmount -= $amountApplied; // Remaining payment amount

            // Update the invoice record
            $updateSql = "UPDATE invoices SET balances = ?, paid = paid + ? WHERE invoicenumber = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ddi", $newBalance, $amountApplied, $invoiceId);
            $updateStmt->execute();

            // Record this payment application in invoice_payments
            $insertSql = "INSERT INTO invoice_payments (invoice_id, amount_applied) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("id", $invoiceId, $amountApplied);
            $insertStmt->execute();
        }

        // Record the payment in the payments table
        // (Assuming you have a way to identify the tenant_id or the tenant identifier used in the payments table)
        $paymentSql = "INSERT INTO bulkpayments (tenant_id, amount, payment_date) VALUES (?, ?, CURDATE())";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->bind_param("sd", $tenant, $originalPaymentAmount); // Use the original payment amount here
        $paymentStmt->execute();

        // Commit the transaction
        $conn->commit();

        // After successful payment processing
        echo "<script type='text/javascript'>alert('Payment processed successfully".$tenant."');</script>";

    } catch (Exception $e) {
        // An error occurred, roll back the transaction
        $conn->rollback();
        echo "<script type='text/javascript'>alert('An error occurred:An error occurred:".$e->getMessage()."');</script>";
    }
} else {
    echo "<script type='text/javascript'>alert('Invalid Request');</script>";
}
?>
