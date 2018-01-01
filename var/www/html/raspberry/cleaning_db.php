<?php
include_once ("../includes/sql.php");

function mediane($arr) {
    $count = count($arr);
	$middleval = floor(($count-1)/2);
	if($count % 2) {
        $median = $arr[$middleval];
    } 
	else {
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}

//pH Selection des valeurs de la dernière heure
$sqltemp1 = "SELECT `value`,`id` FROM `value` WHERE `id_sensor` = '3' ORDER BY `value`.`id` DESC LIMIT 0,6";
$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
if(mysqli_num_rows($resulttemp1) != 0) {
	while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
		$liste1[$rowtemp1['id']] = $rowtemp1['value'];
		$liste_mediane1[] = $rowtemp1['value'];
	}
}
sort($liste_mediane1);
$mediane1=mediane($liste_mediane1);
$min1=$mediane1-0.01;
$max1=$mediane1+0.01;
$nb_suppr1=0;
foreach($liste1 as $key1=>$val1){
	if ($val1<=$min1 or $val1>=$max1){
		//echo "DELETE FROM `value` WHERE `value`.`id` = ".$key1;
		//$sqltemp2 = "DELETE FROM `value` WHERE `value`.`id` = ".$key1;
		//$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
		$nb_suppr1++;
	}
}
echo "---------------- pH ----------------<br />";
echo "La mediane est de ".$mediane1.". Min : ".$min1." / Max : ".$max1."<br />";
echo $nb_suppr1." valeur(s) supprimée(s)<br />";

//Hauteur d'eau Selection des valeurs de la dernière heure
$sqltemp3 = "SELECT `value`,`id` FROM `value` WHERE `id_sensor` = '4' ORDER BY `value`.`id` DESC LIMIT 0,6";
$resulttemp3 = mysqli_query($connection, $sqltemp3) or die(mysqli_error($connection));
if(mysqli_num_rows($resulttemp3) != 0) {
	while($rowtemp3 =mysqli_fetch_assoc($resulttemp3)){
		$liste3[$rowtemp3['id']] = $rowtemp3['value'];
		$liste_mediane3[] = $rowtemp3['value'];
	}
}
sort($liste_mediane3);
$mediane3=mediane($liste_mediane3);
$min3=$mediane3-0.01;
$max3=$mediane3+0.01;
$nb_suppr3=0;
foreach($liste3 as $key3=>$val3){
	if ($val3<=$min3 or $val3>=$max3){
		//echo "DELETE FROM `value` WHERE `value`.`id` = ".$key3;
		//$sqltemp4 = "DELETE FROM `value` WHERE `value`.`id` = ".$key3;
		//$resulttemp4 = mysqli_query($connection, $sqltemp4) or die(mysqli_error($connection));
		$nb_suppr3++;
	}
}
echo "---------------- Hauteur d'eau ----------------<br />";
echo "La mediane est de ".$mediane3.". Min : ".$min3." / Max : ".$max3."<br />";
echo $nb_suppr3." valeur(s) supprimée(s)<br />";

//Nettoyage de la base
$timestamp=time()-(60*60*24*2);//2 jours
$sqltemp5 = "DELETE FROM `value` WHERE `value`.`timestamp` < ".$timestamp;
$resulttemp5 = mysqli_query($connection, $sqltemp5) or die(mysqli_error($connection));
$sqltemp6 = "DELETE FROM `weather` WHERE `weather`.`timestamp` < ".$timestamp;
$resulttemp6 = mysqli_query($connection, $sqltemp6) or die(mysqli_error($connection));

//Optimisation de la base
$sqltemp7 = "OPTIMIZE TABLE `fishtank`, `sensor`, `users`, `value`, `warning`, `weather`";
$resulttemp7 = mysqli_query($connection, $sqltemp7) or die(mysqli_error($connection));
?>