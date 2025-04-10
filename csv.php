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

    // Convert numeric month to full month name if needed
    $months = [
        1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May",
        6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October",
        11 => "November", 12 => "December"
    ];
    $selectedMonthName = $months[(int)$selectedMonth];

    // Debugging: Check the values being passed
    // Uncomment the following lines to debug
    // echo "Selected Year: $selectedYear, Selected Month: $selectedMonthName";
    // exit;

    // Fetch data from the invoices table and separate by type within the selected month and year
    $query = "
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Water' AS `Type`, water_charge AS `Amount`,
               ROUND(water_charge , 2) AS `VAT`,
               ROUND(water_charge , 2) AS `Amount Incl`
        FROM invoices
        WHERE water_charge > 0 AND year = ? AND month = ?
        UNION ALL
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Electricity' AS `Type`, electricity_charge AS `Amount`,
               ROUND(electricity_charge , 2) AS `VAT`,
               ROUND(electricity_charge , 2) AS `Amount Incl`
        FROM invoices
        WHERE electricity_charge > 0 AND year = ? AND month = ?
        UNION ALL
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Sewage' AS `Type`, sewage_charge AS `Amount`,
               ROUND(sewage_charge , 2) AS `VAT`,
               ROUND(sewage_charge , 2) AS `Amount Incl`
        FROM invoices
        WHERE sewage_charge > 0 AND year = ? AND month = ? order by `Inv Number` ASC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $selectedYear, $selectedMonthName, $selectedYear, $selectedMonthName, $selectedYear, $selectedMonthName);
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

<!DOCTYPE html>
<html>
<head>
    <title>CSV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="file.css" rel="stylesheet">
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Include Bootstrap Modal -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    padding: 0;
    margin: 0;
    background-color: #f8f9fa;
}
* {
    box-sizing: border-box;
}

/* Modern form styling */
form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

form h2 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

form p {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

form .form-label {
    font-weight: 500;
    color: #555;
    margin-bottom: 5px;
}

form .form-select, form .btn-success {
    margin-bottom: 15px; /* Add vertical spacing between elements */
}

form .form-select {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 10px;
    font-size: 14px;
    color: #495057;
}

form .btn-success {
    background-color: #198754;
    border-color: #198754;
    border-radius: 6px;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

form .btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container custom-container" style="margin-top: 0px;">
    <div class="row" style="margin-top: 100px;">
        <div class="col-md-12">
            <form method="POST" action="">
                <h2>Export Invoices to CSV</h2>
                <p>Select the month and year for which you want to export the invoices.</p>
                <div class="mb-3">
                    <label for="year" class="form-label">Year</label>
                    <select id="year" name="year" class="form-select">
                        <option value="">Select Year</option>
                        <?php for ($y = 2021; $y <= 2025; $y++): ?>
                            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="month" class="form-label">Month</label>
                    <select id="month" name="month" class="form-select">
                        <option value="">Select Month</option>
                        <?php 
                        $months = [
                            "January", "February", "March", "April", "May", 
                            "June", "July", "August", "September", "October", 
                            "November", "December"
                        ];
                        foreach ($months as $index => $month): ?>
                            <option value="<?php echo $index + 1; ?>"><?php echo $month; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="d-grid">
                    <button type="submit" name="export_csv" class="btn btn-success">Export CSV</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});
</script>
</body>
</html>