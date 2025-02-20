<?php
// database.php - Create this file for your database connection settings
require_once 'database/conn.php';

header('Content-Type: application/json');

// Function to sanitize input
function sanitize($data) {
    // Implement your sanitization logic here (e.g., using mysqli_real_escape_string)
    return $data;
}

// Check if the required POST variables are set
if(isset($_POST['company'], $_POST['from'], $_POST['to'])) {
    $company = sanitize($_POST['company']);
    $from = sanitize($_POST['from']);
    $to = sanitize($_POST['to']);

    // Database query
   // Database query
$sql = "SELECT `balances` 
        FROM `invoices` 
        WHERE `tenant` = ? AND 
              STR_TO_DATE(`billdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE(?, '%Y-%m-%d') AND STR_TO_DATE(?, '%Y-%m-%d')";


    // Database connection
    $conn = new mysqli($servername, $username, $password,$db);

    // Check connection
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $company, $from, $to);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    $invoiceCount = 0;
    $balances = 0;

    // Calculate totals
    while($row = $result->fetch_assoc()) {

        $balances += $row['balances'];
        $invoiceCount++;
    }


$balances = round($balances, 2);

    // Return data as JSON
    echo json_encode(array(
        'count' => $invoiceCount,
        'total' => $balances
    ));

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
?>
