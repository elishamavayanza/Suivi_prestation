<?php 
$host ="localhost";
$user ="admin";
$password ="admin";
$db ="suivi_prestation";
$port = 3306;
$con = mysqli_connect($host,$user,$password,$db,$port);
if (mysqli_connect_error())
{
    echo "Probleme de connexion".mysqli_connect_error();
}
?>