<?php
$id=1;
$titre="Erreur !!!";
$nb_erreur=0;
$erreur="";
include_once ("./includes/fonctions.php");
include_once ("./includes/sql.php");
if (isset($_GET['id'])){
	if (is_numeric($_GET['id'])){
		$id= mysqli_real_escape_string($connection,$_GET['id']);
		$sql1 = "select fishtank_id, fishtank_name, fishtank_picture from fishtank where fishtank_id=$id";
		$result1 = mysqli_query($connection, $sql1) or die("Erreur dans l'ID de l'aquarium (#index2.php.php/8) : " . mysqli_error($connection));
		if(mysqli_num_rows($result1) != 0) {
			while($row1 =mysqli_fetch_assoc($result1)){
					$titre=$row1["fishtank_name"];
			}
		}
	}
	else{
		$nb_erreur++;
		$erreur .= "- L'id est incorrect.(#index2.php.php/21)<br/>";
	}
}
else{
	$nb_erreur++;
	$erreur .= "- L'id n'a pas été communiqué.(#index2.php.php/26)<br/>";
}
?>
<html>
<head>
	<?php include_once ("./includes/head.php"); ?>
</head>

<body>

	<div data-role="page">
		<!-- Barre d'en-tête -->
		<div data-role="header" style="height:45px;"><a href="index.php" data-icon="arrow-l">Accueil</a><a href="./reglages.php?id=1" data-icon="gear">Réglages</a>
			<h1><?php echo $titre;?></h1> 
		</div>
		<!-- /Barre d'en-tête -->
		<!-- Contenu -->
		<div data-role="main" class="ui-content">
			<ul data-role="listview" >
		
					<?php
					if ($nb_erreur!=0){
						echo "<li style='color:#FF0000;font-weight: bold;'>";
						echo "Il y a ".$nb_erreur." erreur(s):<br />";
						echo $erreur;
						echo "<br/><i>Vous allez être redirigé dans 5 sec.</i>";
						header("refresh:5;url=index.php");
						echo "</li>";
					}
					?>
				<li>
					<a href="temperature.php?id=<?php echo $id; ?>" data-ajax="false">
						<h2>Température</h2>
						<?php
							$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='temp'";
							$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
							if(mysqli_num_rows($resulttemp1) != 0) {
								while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
									$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
									$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
									if(mysqli_num_rows($resulttemp2) != 0) {
										while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
											echo "<p>".$rowtemp1["name"]." : ".$rowtemp2["value"] ."°C</p>";
										}
									}
								}
							}
						?>
					</a>
				  </li>
				  <li>
					<a href="ph.php?id=<?php echo $id; ?>" data-ajax="false">
						<h2>pH</h2>
						<?php
							$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='ph'";
							$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
							if(mysqli_num_rows($resulttemp1) != 0) {
								while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
									$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
									$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
									if(mysqli_num_rows($resulttemp2) != 0) {
										while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
											echo "<p>".$rowtemp1["name"]." : ".$rowtemp2["value"] ."</p>";
										}
									}
								}
							}
						?>
					</a>
				  </li>
				  <li>
					<a href="hauteur.php?id=<?php echo $id; ?>" data-ajax="false">
						<h2>Hauteur d'eau</h2>
						<?php
							$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='level'";
							$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
							if(mysqli_num_rows($resulttemp1) != 0) {
								while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
									$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
									$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
									if(mysqli_num_rows($resulttemp2) != 0) {
										while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
											echo "<p>".$rowtemp1["name"]." : ".$rowtemp2["value"] ." l</p>";
										}
									}
								}
							}
						?>
					</a>
				  </li>
				  <li>
					<a href="alerte.php?id=<?php echo $id; ?>" data-ajax="false">
						<h2>Historique des alertes</h2>
						<?php
						$sqltemp1 = "SELECT * FROM `warning` WHERE `active`=1";
						$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
						$nombre=mysqli_num_rows($resulttemp1);
						if($nombre != 0) {
							echo "<p><b><font color='#FF0000'>Il y a ".$nombre." alerte(s) active(s) !</font></b></p>";
						}
						else{
							echo "<p>Il n'y a pas d'alerte active.</p>";
						}
						?>
					</a>
				  </li>
				  <li>
					<a href="reglages.php?id=<?php echo $id; ?>" data-ajax="false">
						<h2>Réglages</h2>
					</a>
				  </li>
			</ul>
		</div>
		<!-- /Contenu -->
		<!-- Barre de pied de page -->
		<div data-role="footer">
			<h4>Aquamonitor v1 - &copy;2017 mattdevue</h4>
		</div>
		<!-- /Barre de pied de page -->
	</div>
</body>
</html>