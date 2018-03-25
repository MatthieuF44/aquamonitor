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
				<div data-role="header" style="height:45px;"><a href="./index2.php?id=1" data-icon="arrow-l">Accueil</a><a href="./reglages.php?id=1" data-icon="gear">Réglages</a>
					<h1><?php echo $row1["fishtank_name"];?> | pH</h1> 
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
					$sqltemp1 = "SELECT * FROM `sensor` WHERE `type`='ph'";
					$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
					if(mysqli_num_rows($resulttemp1) != 0) {
						while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
							$sqltemp2 = "SELECT * FROM `value` WHERE `id_sensor` = '".$rowtemp1["id"]."' ORDER BY `id` DESC LIMIT 0,1";
							$resulttemp2 = mysqli_query($connection, $sqltemp2) or die(mysqli_error($connection));
							if(mysqli_num_rows($resulttemp2) != 0) {
								while($rowtemp2 =mysqli_fetch_assoc($resulttemp2)){
									view_ph($rowtemp1["name"],$rowtemp2["value"],$rowtemp1["min"],$rowtemp1["max"]);
								}
							}
						}
					}
					//Affichage graphique
					$sqltemp4 = "SELECT * FROM `value` WHERE `id_sensor` = '3' ORDER BY `value`.`id` DESC LIMIT 0,280";
					$resulttemp4 = mysqli_query($connection, $sqltemp4) or die(mysqli_error($connection));
					if(mysqli_num_rows($resulttemp4) != 0) {
						while($rowtemp4 =mysqli_fetch_assoc($resulttemp4)){
							$dateD = $rowtemp4["timestamp"]*1000; //transforme la date MySQL en timestamp
							$temperature = $rowtemp4["value"];
						$liste3[] = "[$dateD, $temperature]"; // format data pour highchart [x,y],[x,y].....
						}
						$liste3 = join(',', array_reverse($liste3)); // on inverse l'ordre car la requete SQL sort le resultat a l'envers
					}
					?>
					</div>
					<div class="ui-grid-solo">					
						<div id="graphique0"></div>
					</div>
					<br />
					<div class="ui-grid-a">
						<div class="ui-block-a"><a href="./temperature.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-l" data-ajax="false">Températures</a></div>
						<div class="ui-block-b"><a href="./hauteur.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-r" data-iconpos="right"  data-ajax="false">Hauteur d'eau</a></div>
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