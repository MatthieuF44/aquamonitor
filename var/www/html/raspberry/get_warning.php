<?php
include_once ("../includes/sql.php");
$time=time();
$nb_alerte=0;
//Récupération des différentes valeurs
$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='temp'";
$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
if(mysqli_num_rows($resulttemp1) != 0) {
	while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
		$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
		$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
		if(mysqli_num_rows($resulttemp2) != 0) {
			while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
				$temp[] = [
					"id" => $rowtemp1["id"],
					"name" => $rowtemp1["name"],
					"value" => floatval($rowtemp2["value"]),
					"min" => floatval($rowtemp1["min"]),
					"max" => floatval($rowtemp1["max"]),
					"min0" => floatval($rowtemp1["min0"]),
					"max0" => floatval($rowtemp1["max0"])
				];
			}
		}
	}
}
$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='ph'";
$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
if(mysqli_num_rows($resulttemp1) != 0) {
	while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
		$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
		$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
		if(mysqli_num_rows($resulttemp2) != 0) {
			while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
				$ph[] = [
					"id" => $rowtemp1["id"],
					"name" => $rowtemp1["name"],
					"value" => floatval($rowtemp2["value"]),
					"min" => floatval($rowtemp1["min"]),
					"max" => floatval($rowtemp1["max"]),
					"min0" => floatval($rowtemp1["min0"]),
					"max0" => floatval($rowtemp1["max0"])
				];
			}
		}
	}
}
$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='level'";
$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
if(mysqli_num_rows($resulttemp1) != 0) {
	while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
		$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
		$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
		if(mysqli_num_rows($resulttemp2) != 0) {
			while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
				$level[] = [
					"id" => $rowtemp1["id"],
					"name" => $rowtemp1["name"],
					"value" => floatval($rowtemp2["value"]),
					"min" => floatval($rowtemp1["min"]),
					"max" => floatval($rowtemp1["max"]),
					"min0" => floatval($rowtemp1["min0"]),
					"max0" => floatval($rowtemp1["max0"])
				];
			}
		}
	}
}
//Traitement des valeurs

//-------------------TEMPERATURE----------------------
$i=0;
foreach ($temp as $key => $value) {
	//Niveau 1
	if ($temp[$i]['value']>$temp[$i]['max']){
		$niveau=1;
		$limite=$temp[$i]['max'];
		if ($temp[$i]['value']>$temp[$i]['max0']){
			$niveau=2;
			$limite=$temp[$i]['max0'];
		}
		if ($temp[$i]['name']=="Air"){
			$advice="Ouvrir les fenêtres.";
		}
		elseif ($temp[$i]['name']=="Eau"){
			$advice="Aérer l'eau en orientant la canne de rejet ou mettre en route un système de ventilation.";
		}
		$alerte[]=[
					"id_sensor" => $temp[$i]['id'],
					"sensor" => "Temp&eacute;rature",
					"name" => $temp[$i]['name'],
					"type" => "high",
					"level" => $niveau,
					"message" => "La température relevée est trop élevée : ".$temp[$i]['value']."°C au lieu de ".$limite."°C maximum.",
					"advice" => $advice,
				];
		$nb_alerte++;
	}
	elseif ($temp[$i]['value']<$temp[$i]['min']){
		$niveau=1;
		$limite=$temp[$i]['min'];
		if ($temp[$i]['value']<$temp[$i]['min0']){
			$niveau=2;
			$limite=$temp[$i]['min0'];
		}
		if ($temp[$i]['name']=="Air"){
			$advice="Augmenter la température de la pièce en augmentant les radiateurs ou en ouvrant les stores lorsque le soleil est présent.";
		}
		elseif ($temp[$i]['name']=="Eau"){
			$advice="Augmenter la température de la résistance de chauffage de l'aquarium.";
		}
		$alerte[]=[
					"id_sensor" => $temp[$i]['id'],
					"sensor" => "Temp&eacute;rature",
					"name" => $temp[$i]['name'],
					"type" => "low",
					"level" => $niveau,
					"message" => "La température relevée est trop faible : ".$temp[$i]['value']."°C au lieu de ".$limite."°C minimum.",
					"advice" => $advice,
				];
		$nb_alerte++;
	}
	$i++;
}

//-------------------PH----------------------
$i=0;
foreach ($ph as $key => $value) {
	//Niveau 1
	if ($ph[$i]['value']>$ph[$i]['max']){
		$niveau=1;
		$limite=$ph[$i]['max'];
		if ($ph[$i]['value']>$ph[$i]['max0']){
			$niveau=2;
			$limite=$ph[$i]['max0'];
		}
		$alerte[]=[
					"id_sensor" => $ph[$i]['id'],
					"sensor" => "pH",
					"name" => $ph[$i]['name'],
					"type" => "high",
					"level" => $niveau,
					"message" => "Le pH relevé est trop élevé : ".$ph[$i]['value']." au lieu de ".$limite." maximum.",
					"advice" => "Augmenter la quantité de CO2 afin de réduire le pH.",
				];
		$nb_alerte++;
	}
	elseif ($ph[$i]['value']<$ph[$i]['min']){
		$niveau=1;
		$limite=$ph[$i]['min'];
		if ($ph[$i]['value']<$ph[$i]['min0']){
			$niveau=2;
			$limite=$ph[$i]['min0'];
		}
		$alerte[]=[
					"id_sensor" => $ph[$i]['id'],
					"sensor" => "pH",
					"name" => $ph[$i]['name'],
					"type" => "low",
					"level" => $niveau,
					"message" => "Le pH relevé est trop faible : ".$ph[$i]['value']." au lieu de ".$limite." minimum.",
					"advice" => "Réduire la quantité de CO2 afin d'augmenter le pH.",
				];
		$nb_alerte++;
	}
	$i++;
}

//-------------------NIVEAU D'EAU----------------------
$i=0;
foreach ($level as $key => $value) {
	//Niveau 1
	if ($level[$i]['value']>$level[$i]['max']){
		$niveau=1;
		$limite=$level[$i]['max'];
		if ($level[$i]['value']>$level[$i]['max0']){
			$niveau=2;
			$limite=$level[$i]['max0'];
		}
		$alerte[]=[
					"id_sensor" => $level[$i]['id'],
					"sensor" => "Niveau",
					"name" => $level[$i]['name'],
					"type" => "high",
					"level" => $niveau,
					"message" => "Le niveau d'eau relevé est trop élevé (ou bien le capteur hallucine :) ): ".$level[$i]['value']."cm au lieu de ".$limite."cm maximum.",
					"advice" => "Réduiser la quantité d'eau dans l'aquarium."
				];
		$nb_alerte++;
	}
	elseif ($level[$i]['value']<$level[$i]['min']){
		$niveau=1;
		$limite=$level[$i]['min'];
		if ($level[$i]['value']<$level[$i]['min0']){
			$niveau=2;
			$limite=$level[$i]['min0'];
		}
		$alerte[]=[
					"id_sensor" => $level[$i]['id'],
					"sensor" => "Niveau",
					"name" => $level[$i]['name'],
					"type" => "low",
					"level" => $niveau,
					"message" => "Le niveau d'eau relevé est trop faible : ".$level[$i]['value']."cm au lieu de ".$limite."cm minimum.",
					"advice" => "Ajouter de l'eau à votre aquarium."
				];
		$nb_alerte++;
	}
	$i++;
}
//Affichage des alertes si nb_alerte supérieur à zéro
if ($nb_alerte>0){
	$output = shell_exec('gpio mode 27 out');	
	$output1 = shell_exec('gpio write 27 0');	
	echo "<pre>$output</pre>";
	echo "<pre>$output1</pre>";
	
	echo "Il y a ".$nb_alerte." alerte(s) :<br />";

	$alerte_mail=$alerte;
	
	echo '<pre>';
	var_dump($alerte);
	echo '</pre>';
	
	//Relevé des erreurs enregistrées dans la base de données
	$sqltemp1 = "SELECT * FROM `warning`";
	$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
	if(mysqli_num_rows($resulttemp1) != 0) {
		while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
				//Mise à jour des alertes déjà enregistrée
				$i=0;
				$modif=0;
				foreach ($alerte as $key => $value) {
					//Alerte active donc mise à jour du texte et du timestamp
					if ($alerte[$i]["id_sensor"]==$rowtemp1['id_sensor'] && $alerte[$i]["type"]==$rowtemp1['type'] && $alerte[$i]["level"]==$rowtemp1['level']){
						$sql1 = "UPDATE `warning` SET `message` = '".addslashes(htmlentities($alerte[$i]["message"]))."',`timestamp_end` = '".$time."',`active` = '1' WHERE `warning`.`id` = ".$rowtemp1['id']."";
						$result1 = mysqli_query($connection, $sql1) or die(mysqli_error($connection));
						unset($alerte[$i]);
						$alerte = array_merge($alerte);
						$modif=1;
					}
					$i++;
				}
				//Alerte non active donc passage du code en non-actif
				if ($modif==0){
					$sql1 = "UPDATE `warning` SET `active` = '0' WHERE `warning`.`id` = ".$rowtemp1['id']."";
					$result1 = mysqli_query($connection, $sql1) or die(mysqli_error($connection));
				}
		}
	}
	$new_alerte=count($alerte);
	if ($new_alerte>0){
		$i=0;
		foreach ($alerte as $key => $value) {
			$sql1 = "INSERT INTO `warning` (`id_sensor`,`sensor`,`name`,`type`,`level`,`message`,`advice`,`active`,`timestamp_start`,`timestamp_end`) VALUES('".$alerte[$i]["id_sensor"]."', '".$alerte[$i]["sensor"]."', '".htmlentities($alerte[$i]["name"])."', '".htmlentities($alerte[$i]["type"])."', '".$alerte[$i]["level"]."', '".addslashes(htmlentities($alerte[$i]["message"]))."', '".addslashes(htmlentities($alerte[$i]["advice"]))."','1', '".$time."', '".$time."')";
			$result1 = mysqli_query($connection, $sql1) or die(mysqli_error($connection));
			$i++;
		}
		
		//Envoi du mail
		$mail = "matthieu.fleury44@gmail.com";
		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
		{
			$passage_ligne = "\r\n";
		}
		else
		{
			$passage_ligne = "\n";
		}
		//=====Déclaration des messages au format texte et au format HTML.
		$message_txt = "Cet email est écrit en HTML.";
		$message_html = '<html>
			<head></head>
			<body>
				<table width="95%" cellpadding="0" cellspacing="0" align="center" style="border: 1px solid #d7d6d6;">
					<tr>
						<td height="80" valign="middle" align="center" bgcolor="#F2F0F0" style="border-bottom: 1px solid #d7d6d6;">
							<h3 style="font-size:22px;color:#0E7693;">AquaMonitor</h3>
						</td>
					</tr>                                  
					<tr>
						<td border="1" style="padding:20px;">
							<h2 style="color:#0E7693; font-size:20px;">Récapitulatif des erreurs :</h2>';

		$i=0;
		foreach ($alerte_mail as $key => $value) {
			if($alerte_mail[$i]["level"]=1){
				$couleur="#FFBF00";
			}
			elseif($alerte_mail[$i]["level"]=2){
				$couleur="#FF0000";
			}
			$message_html .=	'<p> 
									<b>- '.$alerte_mail[$i]["sensor"].' :</b><br />
									<i>Nom: '.$alerte_mail[$i]["name"].' -  <font color="'.$couleur.'"><b>Niveau: '.$alerte_mail[$i]["level"].'</b></font></i><br />
									'.$alerte_mail[$i]["message"].'<br />
									<i><b>'.$alerte_mail[$i]["advice"].'</i> 
								</p>';
			$i++;
		}
						
		$message_html .='</td>
					</tr>
					<tr>
						<td height="60" valign="middle" align="center" bgcolor="#F2F0F0" style="border-top: 1px solid #d7d6d6;">
							<b>Aquamonitor v1 - ©2018 mattdevue</b>
						</td>
					</tr>
				</table>
			</body>
		</html>';
		//==========
		 
		//=====Création de la boundary
		$boundary = "-----=".md5(rand());
		//==========
		 
		//=====Définition du sujet.
		$sujet = 'AquaMonitor : Récapitulatif des erreurs';
		//=========
		 
		//=====Création du header de l'e-mail.
		$header = "From: \"Aquamonitor\"<aquamonitor@virtual-btp.v-info.info>".$passage_ligne;
		//$header.= "Reply-to: \"WeaponsB\" <weaponsb@mail.fr>".$passage_ligne;
		$header.= "MIME-Version: 1.0".$passage_ligne;
		$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
		//==========
		 
		//=====Création du message.
		$message = $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format texte.
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_txt.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format HTML
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_html.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		//==========
			
		if(mail($mail,$sujet,$message,$header)){
			echo "Le message à été envoyé avec succès<br /><br />";
			echo nl2br($message);
		}
		else{
			echo "Erreur !!<br /><br />";
			echo nl2br($message);
		}
	}
}
else{
	$sql1 = "UPDATE `warning` SET `active` = '0'";
	$result1 = mysqli_query($connection, $sql1) or die(mysqli_error($connection));
	$output = shell_exec('gpio mode 27 out');	
	$output1 = shell_exec('gpio write 27 1');	
	echo "<pre>$output</pre>";
	echo "<pre>$output1</pre>";
}
?>
