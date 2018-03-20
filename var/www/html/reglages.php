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
						<form>
							<div class="ui-corner-all custom-corners">
								<div class="ui-bar ui-bar-a">
									<h3>Réglage des seuils d'alerte pour les différents capteurs</h3>
								</div>
								<div class="ui-body ui-body-a">
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
							</div>
							<p></p>
							<div class="ui-bar ui-bar-a">
								<h3>Gestion du mode automatique)</h3>
							</div>
							<div class="ui-body ui-body-a">
								<div class="ui-field-contain">
									<label for="slider-flip-m">Contrôle du co2</label>
									<select name="slider-flip-m" id="slider-flip-m" data-role="slider">
										<option value="on">Auto</option>
										<option value="off" selected="">Manuel</option>
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="ui-grid-solo">					
						<div id="graphique0"></div>
					</div>
					<br />
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