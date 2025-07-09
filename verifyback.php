<?php
$otp=$_POST['otp'];
session_start();
if ($_SESSION['otp']== $otp)
{
	echo "<script> alert('email is verified') </script>";
	header('location:newpass.php');

}
else {
	header('location:verify.php?msg=incorrect OTP');

}