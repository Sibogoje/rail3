<?php
session_start(); // Start the session
require "database/conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve hashed password from the database based on the username
    $stmt = $conn->prepare("SELECT id, userid, role, password FROM users WHERE userid = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $dbUsername, $dbRole, $dbPassword);

    if ($stmt->fetch()) {
        // Verify the password
        if (password_verify($password, $dbPassword)) {
            // Password is correct, set sessions
            session_regenerate_id(true); // Regenerate session ID to prevent session fixation
            $_SESSION['userid'] = $dbUsername;
            $_SESSION['role'] = $dbRole;

            echo json_encode(array('success' => true, 'message' => 'Login successful.'));
            exit;
        } else {
            echo json_encode(array('success' => false, 'message' => 'Invalid credentials.'));
            exit;
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'User not found.'));
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
