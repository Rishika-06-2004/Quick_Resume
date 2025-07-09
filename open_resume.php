<?php
session_start();

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$resume_id = $_GET['id'];

$sql = "SELECT template_no FROM resume WHERE resume_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $resume_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Resume not found.");
}

$row = $result->fetch_assoc();
$template_no = $row['template_no'];

// Redirect to the correct template
switch ($template_no) {
    case 1:
        header("Location: t1resume.php?id=$resume_id");
        break;
    case 2:
        header("Location: t2resume.php?id=$resume_id");
        break;
    case 3:
        header("Location: t3resume.php?id=$resume_id");
        break;
    case 4:
        header("Location: t4resume.php?id=$resume_id");
        break;
    default:
        die("Invalid template.");
}
exit;
?>
