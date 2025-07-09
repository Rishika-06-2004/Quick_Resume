<?php
$con = mysqli_connect('localhost', 'root', '', 'project');

if (!$con) {
    die('Unable to connect to server');
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$email = $_POST['email'];

$sql = "SELECT * FROM user WHERE email='$email'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
	$otp=rand(000000,999999);
    $mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'ourresumebuilder@gmail.com';                     //SMTP username
    $mail->Password   = 'suumrcogpgaspuqw';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('ourresumebuilder@gmail.com', 'Quick_Resume');
    $mail->addAddress($email);     //Add a recipient


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'forget password';
    $mail->Body    = 'Your 6 digits verification code: <b>'.$otp.'</b>';

    $mail->send();
    session_start();
    $_SESSION['otp']=$otp;
    $_SESSION['email']=$email;
     header('location:verify.php');

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
} else {
    header('location:forget.php? msg=This email is not registered.');
}

mysqli_close($con);
?>
