<?php
session_start();
session_unset();
session_destroy();
header("Location: index.html"); // Or wherever your login page is
exit();
?>
