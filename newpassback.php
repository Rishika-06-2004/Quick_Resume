<?php
session_start();
$pass=$_POST['pass'];
$email=$_SESSION['email'];
$conn = new mysqli("localhost", "root", "", "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE user SET password='$pass' WHERE email='$email'";
$result = $conn->query($sql);
echo "<script> alert('succefully changed ') </script>";
header('location:log.php');

$conn->close();
?>