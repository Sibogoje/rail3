<?php
session_start();
include "../database/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenant = $_POST['tenant'];
    $amount = $_POST['amount'];
    $month = $_POST['month'];
    $date = date("Y-m-d");

    // Fetch house code for the tenant
    $houseQuery = "SELECT autoid FROM house WHERE tcode = '$tenant'";
    $houseResult = mysqli_query($conn, $houseQuery);
    $houseRow = mysqli_fetch_assoc($houseResult);
    $houseId = $houseRow['autoid'] ?? null;

    if (!$houseId) {
        echo json_encode(["success" => false, "message" => "House not found for the tenant."]);
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

    echo json_encode(["success" => true, "message" => "Payment recorded successfully."]);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request."]);
?>
