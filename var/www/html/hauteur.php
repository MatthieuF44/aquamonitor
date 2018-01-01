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
		$erreur .= "- L'id est incorrect.(#hauteur.php.php/18)<br/>";
	}
}
else{
	$nb_erreur++;
	$erreur .= "- L'id n'a pas été communiqué.(#hauteur.php.php/23)<br/>";
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
					<h1><?php echo $row1["fishtank_name"];?> | Hauteur d'eau</h1> 
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
					$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='level'";
					$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
					if(mysqli_num_rows($resulttemp1) != 0) {
						while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
							$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
							$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
							if(mysqli_num_rows($resulttemp2) != 0) {
								while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
									view_water_level($rowtemp1["id"],$rowtemp1["name"],$rowtemp2["value"],$rowtemp1["min"],$rowtemp1["max"]);
								}
							}
						}
					}
					//Affichage graphique
					$sqltemp5 = "SELECT * FROM `value` WHERE `id_sensor` = '4' ORDER BY `value`.`id` DESC LIMIT 0,280";
					$resulttemp5 = mysqli_query($connection, $sqltemp5) or die(mysqli_error($connection));
					if(mysqli_num_rows($resulttemp5) != 0) {
						while($rowtemp5 =mysqli_fetch_assoc($resulttemp5)){
							$dateD = $rowtemp5["timestamp"]*1000; //transforme la date MySQL en timestamp
							$temperature = $rowtemp5["value"];
						$liste4[] = "[$dateD, $temperature]"; // format data pour highchart [x,y],[x,y].....
						}
						$liste4 = join(',', array_reverse($liste4)); // on inverse l'ordre car la requete SQL sort le resultat a l'envers
					}
					?>
					</div>
					<div class="ui-grid-solo">					
						<div id="graphique0"></div>
					</div>
					<br />
					<div class="ui-grid-a">
						<div class="ui-block-a"><a href="./ph.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-l" data-ajax="false">pH</a></div>
						<div class="ui-block-b"><a href="./alerte.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-r" data-iconpos="right"  data-ajax="false">Alertes</a></div>
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
				text: 'Niveau',
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
				min: 60,
				max: 100,
				title: {
					text: null
				},
				labels: {
					format: '{value} l'
				}
			},
			tooltip: {
				shared: true,
				crosshairs: true,
				borderRadius: 8,
				borderWidth: 3,
				valueSuffix: ' l',
			 },
			plotOptions: {
				series: {
					marker: {
						enabled: false,
					}
				}
			},
	 
			series: [{
				name: 'Niveau',
				color: 'black',
				zIndex: 1,
				data: [<?php echo $liste4; ?>] // c'est ici qu'on insert les data
			}]
		});
	});
	$(document).ready(function(){
		$(".GaugeMeter").gaugeMeter();
	});
	</script>
</body>

</html>