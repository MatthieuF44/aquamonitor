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
		$erreur .= "- L'id est incorrect.(#temperature.php.php/18)<br/>";
	}
}
else{
	$nb_erreur++;
	$erreur .= "- L'id n'a pas été communiqué.(#temperature.php.php/23)<br/>";
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
					<h1><?php echo $row1["fishtank_name"];?> | Température</h1> 
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
						<?php
						//Affichage température actuelle
						$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='temp'";
						$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
						if(mysqli_num_rows($resulttemp1) != 0) {
							while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
								$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
								$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
								if(mysqli_num_rows($resulttemp2) != 0) {
									while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
										view_temp($rowtemp1["name"],$rowtemp2["value"],$rowtemp1["min"],$rowtemp1["max"]);
									}
								}
							}
						}
						//Affichage graphique
						$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '1' ORDER BY `value`.`id` DESC LIMIT 0,280";
						$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
						if(mysqli_num_rows($resulttemp2) != 0) {
							while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
								$dateD = $rowtemp2["timestamp"]*1000; //transforme la date MySQL en timestamp
								$temperature = $rowtemp2["value"];
							$liste1[] = "[$dateD, $temperature]"; // format data pour highchart [x,y],[x,y].....
							}
							$liste1 = join(',', array_reverse($liste1)); // on inverse l'ordre car la requete SQL sort le resultat a l'envers
						}
						$sqltemp3 = "SELECT * FROM `value` WHERE `id_sensor` = '2' ORDER BY `value`.`id` DESC LIMIT 0,280";
						$resulttemp3 = mysqli_query($connection, $sqltemp3) or die(mysqli_error($connection));
						if(mysqli_num_rows($resulttemp3) != 0) {
							while($rowtemp3 =mysqli_fetch_assoc($resulttemp3)){
								$dateD = $rowtemp3["timestamp"]*1000; //transforme la date MySQL en timestamp
								$temperature = $rowtemp3["value"];
							$liste2[] = "[$dateD, $temperature]"; // format data pour highchart [x,y],[x,y].....
							}
							$liste2 = join(',', array_reverse($liste2)); // on inverse l'ordre car la requete SQL sort le resultat a l'envers
						}
						$sqltemp4 = "SELECT * FROM `weather` ORDER BY `weather`.`id` DESC LIMIT 0,1";
						$resulttemp4 = mysqli_query($connection, $sqltemp4) or die(mysqli_error($connection));
						if(mysqli_num_rows($resulttemp4) != 0) {
							while($rowtemp4 =mysqli_fetch_assoc($resulttemp4)){
								date_default_timezone_set("Europe/Paris");
								$meteo_heure=date("d/m/y à H:i",$rowtemp4["timestamp"]);
								$meteo_image=$rowtemp4["picture"];
								$meteo_texte=$rowtemp4["text"];
								$meteo_temperature=$rowtemp4["temp"];
								?>
								<style>
								.meteo{
									width:240px;
								}
								</style>
								<div class="container1">	
									<div class="meteo">
										<div class="container2">
											<img src="./images/meteo/<?php echo $meteo_image; ?>.png" alt="<?php echo $meteo_texte; ?>" width="150px"/>
										</div>
										<?php echo $meteo_texte."<br />T° : ".$meteo_temperature."°C<br /><br /><i><font size='2'>Le ".$meteo_heure."</font></i><br /><br /><br /><br />";?>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
					<div class="ui-grid-solo">					
						<div id="graphique0"></div>
					</div>
					<br />
					<div class="ui-grid-a">
						<div class="ui-block-a"><a href="./index2.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-l">Accueil</a></div>
						<div class="ui-block-b"><a href="./ph.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-r" data-iconpos="right" data-ajax="false">pH</a></div>
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
				text: 'Temperatures',
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
				min: 19,
				max: 28,
				title: {
					text: null
				},
				labels: {
					format: '{value} °C'
				}
			},
			tooltip: {
				shared: true,
				crosshairs: true,
				borderRadius: 8,
				borderWidth: 3,
				valueSuffix: ' °C',
			 },
			plotOptions: {
				series: {
					marker: {
						enabled: false,
					}
				}
			},
	 
			series: [{
				name: 'Air',
				color: 'red',
				zIndex: 1,
				data: [<?php echo $liste1; ?>] // c'est ici qu'on insert les data
			},{
				name: 'Eau',
				color: 'green',
				zIndex: 1,
				data: [<?php echo $liste2; ?>] // c'est ici qu'on insert les data
			}]
		});
	});
	$(document).ready(function(){
		$(".GaugeMeter").gaugeMeter();
	});
	</script>
</body>

</html>