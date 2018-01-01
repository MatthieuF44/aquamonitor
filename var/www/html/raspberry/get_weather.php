<?php
//Affichage température extérieure
$json = file_get_contents('http://dataservice.accuweather.com/currentconditions/v1/136018.json?apikey=mW5DkQ5MdizExEQmrlSoTE9BiZGYdHyi&language=fr&details=true');
date_default_timezone_set("Europe/Paris");
$data = json_decode($json,true);	
include_once ("../includes/sql.php");
$meteo_heure_sql=$data['0']['EpochTime'];
$meteo_image=$data['0']['WeatherIcon'];
$meteo_texte=$data['0']['WeatherText'];
$meteo_temperature=$data['0']['Temperature']['Metric']['Value'];

$sql1 = "INSERT INTO weather VALUES('0', '".$meteo_heure_sql."', '".$meteo_image."', '".htmlentities($meteo_texte)."', '".$meteo_temperature."')";
$result1 = mysqli_query($connection, $sql1) or die(mysqli_error($connection));
?>