<?php
session_start();
if (!isset($_SESSION['userid']) || !isset($_SESSION['role'])) {
    // Redirect to the login page
    header("Location: index.php"); // Adjust the path if needed
    exit();
}

// Access session variables
$username = $_SESSION['userid'];
$role = $_SESSION['role'];

include "database/conn.php";

if (isset($_POST['process'])) {
    $selectedInvoiceNumber = $_POST['invoicenumber'];
    $paymentAmount = $_POST['pay'];
    $balance = $_POST['balance'];
    $credit = $_POST['surplus'];

    // Step 1: Update 'invoices' table's 'paid' column
    $updateInvoicesQuery = "UPDATE invoices SET paid = ? WHERE invoicenumber = ?";
    $stmt1 = $conn->prepare($updateInvoicesQuery);
    $stmt1->bind_param("di", $paymentAmount, $selectedInvoiceNumber);
    $stmt1->execute();

    // Step 2: Get 'tenant' from 'invoices' table
    $getTenantQuery = "SELECT house_code FROM invoices WHERE invoicenumber = ?";
    $stmt2 = $conn->prepare($getTenantQuery);
    $stmt2->bind_param("i", $selectedInvoiceNumber);
    $stmt2->execute();

    // Get the result as an associative array
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
    $tenant = $row2['house_code'];

    // Step 3: Insert or update 'payments' table's 'balance' and 'credit' columns using INSERT ... ON DUPLICATE KEY UPDATE
    $insertOrUpdatePaymentsQuery = "INSERT INTO payments (house, balance, credit) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE balance = VALUES(balance), credit = VALUES(credit)";
    $stmt3 = $conn->prepare($insertOrUpdatePaymentsQuery);
    $stmt3->bind_param("sdd", $tenant, $balance, $credit);
    $stmt3->execute();

    // Close statements
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();

    // Handle success or redirect to another page
    if ($stmt1 && $stmt2 && $stmt3) {
        echo "<script type='text/javascript'>alert('Invoice processed successfully!'); window.location.href = 'payment.php';</script>";
    } else {
        echo "<script type='text/javascript'>alert('Error in processing Invoice!');</script>";
    }
}

if (isset($_POST['export_csv'])) {
    $selectedYear = $_POST['year'];
    $selectedMonth = $_POST['month'];

    // Fetch data from the invoices table and separate by type within the selected month and year
    $query = "
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Water' AS `Type`, water_charge AS `Amount`,
               ROUND(water_charge * 0.15, 2) AS `VAT`,
               ROUND(water_charge * 1.15, 2) AS `Amount Incl`
        FROM invoices
        WHERE water_charge > 0 AND year = ? AND month = ?
        UNION ALL
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Electricity' AS `Type`, electricity_charge AS `Amount`,
               ROUND(electricity_charge * 0.15, 2) AS `VAT`,
               ROUND(electricity_charge * 1.15, 2) AS `Amount Incl`
        FROM invoices
        WHERE electricity_charge > 0 AND year = ? AND month = ?
        UNION ALL
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Sewage' AS `Type`, sewage_charge AS `Amount`,
               ROUND(sewage_charge * 0.15, 2) AS `VAT`,
               ROUND(sewage_charge * 1.15, 2) AS `Amount Incl`
        FROM invoices
        WHERE sewage_charge > 0 AND year = ? AND month = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("isisis", $selectedYear, $selectedMonth, $selectedYear, $selectedMonth, $selectedYear, $selectedMonth);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="invoices.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write column headers
        fputcsv($output, ['House Nr', 'Occupant', 'Inv Number', 'Month', 'Type', 'Amount', 'VAT', 'Amount Incl']);

        // Write rows
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    } else {
        echo "<script>alert('No data available to export for the selected month and year.');</script>";
    }
}
?>