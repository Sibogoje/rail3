<?php
// edit_ac_process.php
// Set error reporting and logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);

ini_set('error_log', 'error_log.log');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a database connection
    include 'database/conn.php';

    $edit_id = $_POST["acedit_id"];
    $brand = $_POST["brand"];
     $model = $_POST["model"];
      $size = $_POST["size"];
       $ser_in = $_POST["ser_in"];
        $ser_out = $_POST["ser_out"];
        $house = $_POST["house_id"];
    // Add other fields as needed

    // Check if the AC ID exists in the airconditioners table
    $checkQuery = "SELECT * FROM airconditioners WHERE ac_id = '$edit_id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
    // AC exists, update the airconditioners table
    $updateQuery = "UPDATE airconditioners SET brand = '$brand', model = '$model', size = '$size', ser_in = '$ser_in', ser_out = '$ser_out' WHERE ac_id = '$edit_id'";
    mysqli_query($conn, $updateQuery);
} else {
    // AC doesn't exist, create a new row in the airconditioners table
    $insertQuery = "INSERT INTO airconditioners (brand, model, size, ser_in, ser_out) VALUES ('$brand','$model', '$size', '$ser_in', '$ser_out')";
    mysqli_query($conn, $insertQuery);

    // Fetch the last inserted ID from the airconditioners table
    $getLastInsertedQuery = "SELECT LAST_INSERT_ID() as last_id";
    $result = mysqli_query($conn, $getLastInsertedQuery);
    $row = mysqli_fetch_assoc($result);
    $newAcId = $row['last_id'];

    // Log the values for debugging
    error_log("House ID: $house, New AC ID: $newAcId");

    // Update the house table with the new AC ID
    $houseUpdateQuery = "UPDATE house SET ac_code = '$newAcId' WHERE autoid = '$house'";
    mysqli_query($conn, $houseUpdateQuery);
}


    // Redirect to the main page or display a success message
    header("Location: houses2.php"); // Update the URL accordingly
    exit();
}
?>
