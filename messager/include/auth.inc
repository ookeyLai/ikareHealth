<?php
$name = $_SESSION['name'];
$authority = $_SESSION['authority'];
$userID = $_SESSION['userID'];

if ($name=="" || $name==Null || $authority!="ADMIN") {
	echo "<script language='javascript'>";
	echo "document.location='login.php';";
	echo "</script>";
}
?>
