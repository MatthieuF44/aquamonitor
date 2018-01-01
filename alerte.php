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
					<h1><?php echo $row1["fishtank_name"];?> | Alertes</h1> 
				</div>
				<!-- /Barre d'en-tête -->
				<!-- Contenu -->
				<div data-role="content" style="text-align: center; background-image:url(./css/images/hip-square.png);" class="ui-content">
					<ul id="list" claas="ui-listview-mod" data-role="listview" data-icon="false" data-split-icon="delete">
						<!-- Suppression des alertes -->
						<?php
						if(isset($_GET['suppr']) AND $_GET['suppr']!=NULL AND is_numeric($_GET['suppr'])){
							$sqltemp1 = "DELETE FROM `warning` WHERE `id`=".$_GET['suppr'];
							$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
						}
						?>
						<!-- /Suppression des alertes -->
						<?php
						$sqltemp1 = "SELECT * FROM `warning`";
						$resulttemp1 = mysqli_query($connection, $sqltemp1) or die(mysqli_error($connection));
						if(mysqli_num_rows($resulttemp1) != 0) {
							while($rowtemp1 =mysqli_fetch_assoc($resulttemp1)){
								if ($rowtemp1["sensor"]=="Temp&eacute;rature"){
									$lien="temperature.php?id=1";
								}
								elseif ($rowtemp1["sensor"]=="Niveau"){
									$lien="hauteur.php?id=1";
								}
								elseif ($rowtemp1["sensor"]=="pH"){
									$lien="ph.php?id=1";
								}
								if ($rowtemp1["type"]=="high"){
									$type="Trop élevé(e) !";
								}
								elseif ($rowtemp1["type"]=="low"){
									$type="Trop faible !";
								}
								else{
									$type=$rowtemp1["type"];
								}
								if ($rowtemp1["active"]==1){
									$color_active="#FF0000";
									if ($rowtemp1["level"]==2){
										$color_level="#FF0000";
									}
									else{
										$color_level="#000000";
									}
								}
								else{
									$color_active="#000000";
									$color_level="#000000";
								}
								$duree_minutes=($rowtemp1["timestamp_end"]-$rowtemp1["timestamp_start"])/60;
								if ($duree_minutes>=60){
									$duree_heures=$duree_minutes/60;
									$duree_heures=round($duree_heures);
									$duree=$duree_heures." heure(s)";
									if ($duree_heures>=24){
										$duree_jours=$duree_heures/24;
										$duree_jours=round($duree_jours);
										$duree=$duree_jours." jour(s)";
									}
								}
								else{
									$duree_minutes=round($duree_minutes);
									$duree=$duree_minutes." minutes(s)";
								}
								?>
								<li class="ui-li-has-alt ui-first-child">
									<a href="./<?php echo $lien; ?>" class="ui-btn">
										<h3><font color=<?php echo $color_active; ?>><?php echo $rowtemp1["sensor"]; ?> (<?php echo $rowtemp1["name"]; ?>) : <?php echo $type; ?></font></h3>
										<p><?php echo $rowtemp1["message"]; ?></p>
										<p><i><b>Conseil :</b> <?php echo $rowtemp1["advice"]; ?></i></p>
										<p></p>
										<p><font color=<?php echo $color_level; ?>>Niveau <?php echo $rowtemp1["level"]; ?> - Durée : <?php echo $duree; ?></font></p>
									</a>
									<a href="./alerte.php?suppr=<?php echo $rowtemp1["id"]; ?>" class="delete ui-btn ui-btn-icon-notext ui-icon-delete" title="Supprimer l'alerte"></a>
								</li>
								<?php
							}
						}
						else{
							?>
							<p><b>Aucune alerte pour le moment !</b></p>
							<?php
						}
						?>
					</ul>
					<div class="ui-grid-a">&nbsp;</div><!-- Espacement -->
					<div class="ui-grid-a">
						<div class="ui-block-a"><a href="./hauteur.php?id=<?php echo $id; ?>" data-role="button" data-icon="arrow-l" data-ajax="false">Hauteur</a></div>
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
</body>

</html>