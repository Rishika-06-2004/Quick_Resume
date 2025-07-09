<?php
// Start session if needed
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input values and sanitize
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Query to check if user exists
$sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Successful login
    $_SESSION['email'] = $email; // optional: store session if needed
    header("Location: clone2.php");
    exit();
} else {
    // Login failed
    $msg = "Incorrect Email or Password!";
    header("Location: log.php ? msg=" . urlencode($msg));
    exit();
}

$conn->close();
?>
