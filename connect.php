<?php
$con=mysqli_connect('localhost','root','');
if(!$con)
	die('unable to find server');
$db=mysqli_select_db($con,'project');
if(!$db)
die('unable to find database');
echo " <HTML> <P> HII </P> </HTML> <?php";
?>
<html>
<head>
<style>
p{

color:white;
}</style></head>
