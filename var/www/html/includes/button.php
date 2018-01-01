<?php

//================================================================
// Prise A : ON 852232 / OFF 852230
// Prise B : ON 852226 / OFF 852225
// Prise C : ON 852237 / OFF 852227
// Prise D : ON 852236 / OFF 852228
// Prise E : ON 852229 / OFF 852231
//================================================================

$json_source = file_get_contents('./input.json');
$json_data = json_decode($json_source,true);

$data['sapin']['state']=$json_data['sapin']['state'];
$data['lumiere']['state']=$json_data['lumiere']['state'];
$data['air']['state']=$json_data['air']['state'];

if (isset($_GET['sapin_swicth'])){
	if ($_GET['sapin_swicth']=="off"){
		$output = shell_exec('sudo /home/pi/install/433Utils/RPi_utils/codesend 852230');
		echo "<pre>$output</pre>";
		$data['sapin']['state']=$_GET['sapin_swicth'];
	}
	elseif ($_GET['sapin_swicth']=="on"){
		$output = shell_exec('sudo /home/pi/install/433Utils/RPi_utils/codesend 852232');
		echo "<pre>$output</pre>";
		$data['sapin']['state']=$_GET['sapin_swicth'];
	}
}
if (isset($_GET['lumiere_swicth'])){
	if ($_GET['lumiere_swicth']=="off"){
		$output = shell_exec('sudo /home/pi/install/433Utils/RPi_utils/codesend 852227');
		echo "<pre>$output</pre>";
		$data['lumiere']['state']=$_GET['lumiere_swicth'];
	}
	elseif ($_GET['lumiere_swicth']=="on"){
		$output = shell_exec('sudo /home/pi/install/433Utils/RPi_utils/codesend 852237');
		echo "<pre>$output</pre>";
		$data['lumiere']['state']=$_GET['lumiere_swicth'];
	}
}
if (isset($_GET['air_swicth'])){
	if ($_GET['air_swicth']=="off"){
		$output = shell_exec('sudo /home/pi/install/433Utils/RPi_utils/codesend 852231');
		echo "<pre>$output</pre>";
		$data['air']['state']=$_GET['air_swicth'];
	}
	elseif ($_GET['air_swicth']=="on"){
		$output = shell_exec('sudo /home/pi/install/433Utils/RPi_utils/codesend 852229');
		echo "<pre>$output</pre>";
		$data['air']['state']=$_GET['air_swicth'];
	}
}

$data_json=json_encode($data);

$file = fopen("./input.json", "w") or die("Impossible d'ouvrir le fichier JSON.");
	fwrite($file, $data_json);
fclose($file);
?>