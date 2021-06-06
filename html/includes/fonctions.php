<?php
//Fonction pour index.php
function nb_jour($timestamp_start){
	$nbSec = time()-$timestamp_start;
	$nbJours = ($nbSec) / (3600 * 24);
	return abs(round($nbJours));
}
function nb_mois($timestamp_start){
	$nbSec = time()-$timestamp_start;
	$nbMois = ($nbSec) / (3600 * 24 * 30.5);
	return abs(round($nbMois));
}
//Fonction pour affichage température
function view_temp($name,$temp,$temp_min,$temp_max){
	echo'
	<div class="container1">	
		<div class="container2">	
			<div class="de">
				<div class="den">
				  <div class="dene">
					<div class="denem">
						<p>'.$name.'</p>
						<div class="deneme">
							<span>'.$temp.'</span><strong>&deg;C</strong>
						</div>
					</div>
				  </div>
				</div>
			</div>
		</div>
		<p>T°mini : '.$temp_min.'°c - T°maxi : '.$temp_max.'°c</p>
	</div>';
}
//Fonction pour affichage pH
function view_ph($name,$ph,$ph_min,$ph_max){
	echo'
	<div class="container1">	
		<div class="container2">	
			<div class="de">
				<div class="den">
				  <div class="dene">
					<div class="denem">
						<p>'.$name.'</p>
						<div class="deneme">
							<span>'.$ph.'</span>
						</div>
					</div>
				  </div>
				</div>
			</div>
		</div>
		<p>pH mini : '.$ph_min.' - pH maxi : '.$ph_max.'</p>
	</div>';
}
//Fonction pour affichage hautur eau
function view_water_level($id,$name,$level,$level_min,$level_max){
	echo'
	<div class="GaugeMeter" id="GaugeMeter'.$id.'" 
		data-text="'.$name.'"
		data-total="'.$level_max.'"
		data-used="'.$level.'"
		data-append="%"
		data-size="250"
		data-width="20"
		data-style="Semi"
		data-theme="Red-Gold-Green"
		data-animate_gauge_colors="1"
		data-animate_text_colors="1"
		data-label="'.$name.'"
		data-stripe="2">
	</div>
	<p>0% : '.$level_min.'l - 100% : '.$level_max.'l</p>';
}
?>