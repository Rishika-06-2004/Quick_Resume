<?php
session_start();
session_unset();
session_destroy();
header("Location: h1.html"); // Or wherever your login page is
exit();
?>
