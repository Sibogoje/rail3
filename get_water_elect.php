<?php
// Connect to your database (assuming $conn is your database connection)
include "database/conn.php";
// Perform the query to get the water amount from the database
$query = "SELECT amount FROM flat_charge WHERE code = '02'";
$result = $conn->query($query);

if ($result) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();
    $waterAmount = $row['amount'];

    // Output the result
    echo $waterAmount;
} else {
    // Handle the query error
    echo "Error fetching water amount";
}

// Close the database connection
$conn->close();
?>
