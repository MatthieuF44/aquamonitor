<?php
$server="localhost";$user="aquamonitor";$password="aquamonitor";$database_name="aquarium";
$connection = mysqli_connect($server,$user,$password,$database_name) or die("Erreur de connexion (#sql.php/2) : " . mysqli_error($connection));
?>
