<?php
// Résolution s5 360x640
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
		$erreur .= "- L'id est incorrect.(#ph.php.php/18)<br/>";
	}
}
else{
	$nb_erreur++;
	$erreur .= "- L'id n'a pas été communiqué.(#ph.php.php/23)<br/>";
}
?>
<?php include_once ("./includes/button.php"); ?>
<html>
<head>
	<?php include_once ("./includes/head.php"); ?>
</head>

<body>
	<div data-role="page">
		<?php
		//Récupération de l'id avec protection

		$sql1 = "select fishtank_id, fishtank_name, fishtank_picture from fishtank where fishtank_id=$id";
		$result1 = mysqli_query($connection, $sql1) or die("Erreur dans l'ID de l'aquarium (#detail.php.php/59) : " . mysqli_error($connection));
		if(mysqli_num_rows($result1) != 0) {
			while($row1 =mysqli_fetch_assoc($result1)){
			?>
				<!-- Barre d'en-tête -->
				<div data-role="header" style="height:45px;"><a href="./index2.php?id=1" data-icon="arrow-l">Accueil</a>
					<h1><?php echo $row1["fishtank_name"];?> | Réglages</h1> 
				</div>
				<!-- /Barre d'en-tête -->
				<!-- Contenu -->
				<div data-role="content" style="text-align: center; background-image:url(./css/images/hip-square.png);" class="ui-content">
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
					<div class="ui-grid-a">
						<form action="reglages.php?id=<?php echo $id; ?>" method="post" class="ui-body ui-body-a ui-corner-all">
							<?php
							// Début traitement post
							echo '<pre>';
							if (isset($_POST['sauvegarder'])){
								$i=0;
								print_r($_POST);
								//Début mise à jour des seuils
								$sqltemp2 = "SELECT * FROM `sensor`";
								$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
								if(mysqli_num_rows($resulttemp2) != 0) {
									while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
										if($_POST['seuil-'.$rowtemp2["id"].'-min']!=$rowtemp2["min"]){
											$min=$_POST['seuil-'.$rowtemp2["id"].'-min'];
											$sql2 = "UPDATE `sensor` SET `min` = '".$min."' WHERE `id` = '".$rowtemp2["id"]."'";
											$result2 = mysqli_query($connection, $sql2) or die(mysqli_error($connection));
											$i++;
										}
										if($_POST['seuil-'.$rowtemp2["id"].'-max']!=$rowtemp2["max"]){
											$max=$_POST['seuil-'.$rowtemp2["id"].'-max'];
											$sql3 = "UPDATE `sensor` SET `max` = '".$max."' WHERE `id` = '".$rowtemp2["id"]."'";
											$result3 = mysqli_query($connection, $sql3) or die(mysqli_error($connection));
											$i++;
										}
										if($_POST['seuil-'.$rowtemp2["id"].'-min0']!=$rowtemp2["min0"]){
											$min0=$_POST['seuil-'.$rowtemp2["id"].'-min0'];
											$sql4 = "UPDATE `sensor` SET `min0` = '".$min0."' WHERE `id` = '".$rowtemp2["id"]."'";
											$result4 = mysqli_query($connection, $sql4) or die(mysqli_error($connection));
											$i++;
										}
										if($_POST['seuil-'.$rowtemp2["id"].'-max0']!=$rowtemp2["max0"]){
											$max0=$_POST['seuil-'.$rowtemp2["id"].'-max0'];
											$sql5 = "UPDATE `sensor` SET `max0` = '".$max0."' WHERE `id` = '".$rowtemp2["id"]."'";
											$result5 = mysqli_query($connection, $sql5) or die(mysqli_error($connection));
											$i++;
										}
									}
								}
								//Fin mise à jour des seuils
								//Début mise à jour du reste du formulaire
								$sqltemp4 = "SELECT * FROM `fishtank`";
								$resulttemp4 = mysqli_query($connection, $sqltemp4) or die(mysqli_error($connection));
								if(mysqli_num_rows($resulttemp4) != 0) {
									while($rowtemp4 =mysqli_fetch_assoc($resulttemp4)){
										if($_POST['co2_auto']!=$rowtemp4["co2_auto"]){
											$sql6 = "UPDATE `fishtank` SET `co2_auto` = '".$_POST['co2_auto']."' WHERE `fishtank_id` = '".$rowtemp4["fishtank_id"]."'";
											$result6 = mysqli_query($connection, $sql6) or die(mysqli_error($connection));
											$i++;
										}
										if($_POST['warning']!=$rowtemp4["warning"]){
											$sql7 = "UPDATE `fishtank` SET `warning` = '".$_POST['warning']."' WHERE `fishtank_id` = '".$rowtemp4["fishtank_id"]."'";
											$result7 = mysqli_query($connection, $sql7) or die(mysqli_error($connection));
											$i++;
										}
									}
								}
								//Fin mise à jour du reste du formulaire
								
								echo "Vous avez effectué ".$i." changement(s)";
							}
							echo '</pre>';
							// Fin traitement post
							?>
							<div class="ui-bar ui-bar-a ui-corner-all">
								<h3>Réglage des seuils d'alerte pour les différents capteurs</h3>
							</div>
							<div class="ui-body ui-body-a ui-corner-all">
								<div data-role="collapsibleset" data-theme="a" data-content-theme="a">
									<?php
									$sqltemp1 = "SELECT * FROM `sensor`";
									$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
									if(mysqli_num_rows($resulttemp1) != 0) {
										while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
											//Traduction des noms de type
											if ($rowtemp1["type"]=="Temp"){
												$type="Température";
											}
											elseif ($rowtemp1["type"]=="Level"){
												$type="Niveau";
											}
											else {
												$type=$rowtemp1["type"];
											}
											//Réglage des mini, maxi en fonction des types de capteur
											if ($rowtemp1["type"]=="Temp"){
												$slider_min=10;
												$slider_max=30;
											}
											elseif ($rowtemp1["type"]=="Level"){
												$slider_min=0;
												$slider_max=125;
											}
											elseif ($rowtemp1["type"]=="Ph"){
												$slider_min=0;
												$slider_max=14;
											}
											else {
												$slider_min=0;
												$slider_max=100;
											}
											$sqltemp3 = "SELECT * FROM `fishtank`";
											$resulttemp3 = mysqli_query($connection, $sqltemp3) or die(mysqli_error($connection));
											if(mysqli_num_rows($resulttemp3) != 0) {
												while($rowtemp3 =mysqli_fetch_assoc($resulttemp3)){
													$co2_auto = $rowtemp3['co2_auto'];
													$warning = $rowtemp3['warning'];
												}
											}
											?>
											<div data-role="collapsible" data-collapsed-icon="gear">
												<h3><?php echo $type." - ".$rowtemp1["name"]; ?></h3>
												<div class="ui-field-contain">
													<div data-role="rangeslider" data-mini="true">
														<label for="seuil-<?php echo $rowtemp1["id"]; ?>-min">1er niveau (seuil bas):</label>
														<input type="range" name="seuil-<?php echo $rowtemp1["id"]; ?>-min" id="seuil-<?php echo $rowtemp1["id"]; ?>-min" min="<?php echo $slider_min; ?>" max="<?php echo $slider_max; ?>" value="<?php echo $rowtemp1["min"]; ?>" step=".1">
														<label for="seuil-<?php echo $rowtemp1["id"]; ?>-max">1er niveau (seuil bas):</label>
														<input type="range" name="seuil-<?php echo $rowtemp1["id"]; ?>-max" id="seuil-<?php echo $rowtemp1["id"]; ?>-max" min="<?php echo $slider_min; ?>" max="<?php echo $slider_max; ?>" value="<?php echo $rowtemp1["max"]; ?>" step=".1">
													</div>
												</div>
												<div class="ui-field-contain">
													<div data-role="rangeslider" data-mini="true">
														<label for="seuil-<?php echo $rowtemp1["id"]; ?>-min0">2ème niveau (seuil critique):</label>
														<input type="range" name="seuil-<?php echo $rowtemp1["id"]; ?>-min0" id="seuil-<?php echo $rowtemp1["id"]; ?>-min0" min="<?php echo $slider_min; ?>" max="<?php echo $slider_max; ?>" value="<?php echo $rowtemp1["min0"]; ?>" step=".1">
														<label for="seuil-<?php echo $rowtemp1["id"]; ?>-max0">2ème niveau (seuil critique):</label>
														<input type="range" name="seuil-<?php echo $rowtemp1["id"]; ?>-max0" id="seuil-<?php echo $rowtemp1["id"]; ?>-max0" min="<?php echo $slider_min; ?>" max="<?php echo $slider_max; ?>" value="<?php echo $rowtemp1["max0"]; ?>" step=".1">
													</div>
												</div>
											</div>
										<?php
										}
									}
									?>
								</div>
							</div>
							<p></p>
							<div class="ui-bar ui-bar-a ui-corner-all">
								<h3>Gestion du mode automatique</h3>
							</div>
							<div class="ui-body ui-body-a ui-corner-all">
								<div class="ui-field-contain">						
									<label for="flip-1">Contrôle co2 (auto)</label>
									<select name="co2_auto" id="flip-1" data-role="slider">
										<option value="0" <?php if($co2_auto==0){echo "selected";} ?>>Off</option>
										<option value="1" <?php if($co2_auto==1){echo "selected";} ?>>On</option>
									</select>							
								</div>
							</div>
							<p></p>
							<div class="ui-bar ui-bar-a ui-corner-all">
								<h3>Gestion d'alerte des erreurs</h3>
							</div>
							<div class="ui-body ui-body-a ui-corner-all">
								<div class="ui-field-contain">						
									<label for="flip-2">Contrôle des erreurs</label>
									<select name="warning" id="flip-2" data-role="slider">
										<option value="0" <?php if($warning==0){echo "selected";} ?>>Off</option>
										<option value="1" <?php if($warning==1){echo "selected";} ?>>On</option>
									</select>							
								</div>
							</div>
							<p></p>
							<button type="submit" data-theme="a" name="sauvegarder" value="sauvegarder" class="ui-btn-hidden" aria-disabled="false">Sauvegarder</button>
						</form>
					</div>
					<div class="ui-grid-a">
						<div class="ui-block-a"><a href="./alerte.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-l" data-ajax="false">Alertes</a></div>
						<div class="ui-block-b"><a href="./index2.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-r" data-iconpos="right"  data-ajax="false">Accueil</a></div>
					</div>
				</div>
				<!-- /Contenu -->
			<?php 
			}
 
		}
		else {
			echo 'L\'id est inexistant ! <a href="index.php">Retour à l\'accueil</a>';
		}
		mysqli_close($connection);
		?>
		<!-- Barre de pied de page -->
		<?php include_once ("./includes/footer.php"); ?>
		<!-- /Barre de pied de page -->
	</div>
	<script type="text/javascript">
	$(function() {
		Highcharts.setOptions({
			global: {
				useUTC: false
			}
		});
		chart1 = new Highcharts.Chart('graphique0', {
			chart: {
				type: 'line',
				zoomType: 'x',
				marginRight: 30
			},
			title: {
				text: 'pH',
				style:{
					color: '#4572A7',
				},
			},
			xAxis: {
				type: 'datetime',
			 },
			yAxis: {
				startOnTick: true,
				endOnTick: false,
				min: 6,
				max: 9,
				title: {
					text: null
				},
				labels: {
					format: '{value}'
				}
			},
			tooltip: {
				shared: true,
				crosshairs: true,
				borderRadius: 8,
				borderWidth: 3,
				valueSuffix: '',
			 },
			plotOptions: {
				series: {
					marker: {
						enabled: false,
					}
				}
			},
	 
			series: [{
				name: 'pH',
				color: 'blue',
				zIndex: 1,
				data: [<?php echo $liste3; ?>] // c'est ici qu'on insert les data
			}]
		});
	});
	$(document).ready(function(){
		$(".GaugeMeter").gaugeMeter();
	});
	</script>
</body>

</html>