<?php
include_once ("./includes/fonctions.php");
include_once ("./includes/sql.php");
?>
<html>
<head>
	<?php include_once ("./includes/head.php"); ?>
</head>

<body>

	<div data-role="page">
		<!-- Barre d'en-tête -->
		<div data-role="header">
			<h1>Mes aquariums</h1>
		</div>
		<!-- /Barre d'en-tête -->
		<!-- Contenu -->
		<div data-role="main" class="ui-content">
			<ul data-role="listview" >
				<?php
				$sql = "select fishtank_id, fishtank_name, fishtank_picture, fishtank_overview, fishtank_start from fishtank";
				$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

				while($row =mysqli_fetch_assoc($result)){
				?>
				  <li>
					<a href="index2.php?id=<?php echo $row["fishtank_id"]; ?>">
					<h2><?php echo $row["fishtank_name"]; ?></h2>
					<p><font color="red">Mise en route : 13/05/2017</font></p>
					<p>Nb jour : <?php echo nb_jour($row["fishtank_start"]); ?> -  Nb mois : <?php echo nb_mois($row["fishtank_start"]); ?></p>
					</a>
				  </li>
				<?php }mysqli_close($connection);?>
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