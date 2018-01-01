		<?php
		$json_source = file_get_contents('input.json');
		$json_data = json_decode($json_source);
		$status_sapin = $json_data->sapin->state;
		$status_lumiere = $json_data->lumiere->state;
		$status_air = $json_data->air->state;
		?>
		<div data-role="footer" style="text-align:center; height:95px;" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li style="font-size: 14px;">Sapin
						<div id="sapin_switch">  
							<select name="sapin" id="sapin" data-role="flipswitch" data-mini="true" class="switch">
								<option value="off" myTag="off" <?php if ($status_sapin=="off"){echo('selected=""');}?>>Off</option>
								<option value="on" myTag="on" <?php if ($status_sapin=="on"){echo('selected=""');}?>>On</option>
							</select>
						</div>
					</li>
					<li style="font-size: 14px;">Lumière
						<div id="lumiere_switch">  
							<select name="lumiere" id="lumiere" data-role="flipswitch" data-mini="true">
								<option value="off" myTag="off" <?php if ($status_lumiere=="off"){echo('selected=""');}?>>Off</option>
								<option value="on" myTag="on" <?php if ($status_lumiere=="on"){echo('selected=""');}?>>On</option>
							</select>
						</div>
					</li>
					<li style="font-size: 14px;">Air
						<div id="air_switch">  
							<select name="air" id="air" data-role="flipswitch" data-mini="true">
								<option value="off" myTag="off" <?php if ($status_air=="off"){echo('selected=""');}?>>Off</option>
								<option value="on" myTag="on" <?php if ($status_air=="on"){echo('selected=""');}?>>On</option>
							</select>
						</div>
					</li>
				</ul>
			</div>
			<h4>Aquamonitor v1 - &copy;2018 mattdevue</h4>
		</div>
	<?php
	$fichier= explode("/",$_SERVER['PHP_SELF']);
	$i=count($fichier)-1;
	?>
	<script type="text/javascript">
	$('#sapin_switch').on('click', function() {
	var checkStatus = $('option:selected', this).attr('mytag');
	window.location.href = "<?php echo $fichier[$i]; ?>?id=<?php echo $id; ?>&sapin_swicth=" + checkStatus; 
	});
	$('#lumiere_switch').on('click', function() {
	var checkStatus = $('option:selected', this).attr('mytag');
	window.location.href = "<?php echo $fichier[$i]; ?>?id=<?php echo $id; ?>&lumiere_swicth=" + checkStatus; 
	});
	$('#air_switch').on('click', function() {
	var checkStatus = $('option:selected', this).attr('mytag');
	window.location.href = "<?php echo $fichier[$i]; ?>?id=<?php echo $id; ?>&air_swicth=" + checkStatus; 
	});
	</script>