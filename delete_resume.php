<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email'])) {
    die("Unauthorized access.");
}

if (!isset($_GET['id'])) {
    die("Resume ID not provided.");
}

$resume_id = intval($_GET['id']);
$email = $_SESSION['email'];

// Start transaction
$conn->begin_transaction();

try {
    // Check if the resume exists and belongs to the logged-in user
    $check_sql = "SELECT * FROM resume WHERE resume_id = $resume_id AND email_id = '$email'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows === 0) {
        throw new Exception("Resume not found or access denied.");
    }

    // Step 1: Delete from education
    $conn->query("DELETE FROM education WHERE resume_id = $resume_id");

    // Step 2: Delete from skills
    $conn->query("DELETE FROM skills WHERE resume_id = $resume_id");

    // Step 3: Delete from works
    $conn->query("DELETE FROM work WHERE resume_id = $resume_id");

    // Step 4: Delete from resume
    $conn->query("DELETE FROM resume WHERE resume_id = $resume_id");

    // If all queries successful
    $conn->commit();
    header("Location: clone2.php?deleted=1");
    exit;

} catch (Exception $e) {
    // Something went wrong, rollback
    $conn->rollback();
    echo "Error deleting resume: " . $e->getMessage();
}

$conn->close();
?>
