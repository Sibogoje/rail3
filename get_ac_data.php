<?php


// Assuming you have a database connection
//include 'db_connection.php';
require "database/conn.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ac_id'])) {
    $ac_id = $_POST['ac_id'];

    // Fetch data from the airconditioners table based on AC ID
    $query = "SELECT * FROM airconditioners WHERE ac_id = '$ac_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $ac_data = mysqli_fetch_assoc($result);

        // Return data as JSON
        echo json_encode($ac_data);
    } else {
        // Handle database query error
        echo json_encode(['error' => 'Database query error']);
    }
} else {
    // Handle invalid request
    echo json_encode(['error' => 'Invalid request']);
}
?>
