<?php
$today = date('Y-m-d'); // Obtenir la date actuelle au format YYYY-MM-DD
$thresholdDate = '2024-11-10';

if (strtotime($today) > strtotime($thresholdDate)) {
    header("location:loginadmin.php");
} 

?>