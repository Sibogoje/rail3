<?php
include "database/conn.php";
// Get the ID from the POST request
$id = $_POST['id'];

// Perform the deletion query
$sql = "DELETE FROM users WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    // If deletion is successful, send a success response
    $response = array('success' => true);
} else {
    // If an error occurs during deletion, send an error response
    $response = array('success' => false, 'error' => $conn->error);
}

// Close the database connection
$conn->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
