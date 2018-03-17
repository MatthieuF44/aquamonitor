<?php
$server="localhost";$user="root";$password="aquarium";$database_name="aquarium";
$connection = mysqli_connect($server,$user,$password,$database_name) or die("Erreur de connexion (#sql.php/2) : " . mysqli_error($connection));
?>
