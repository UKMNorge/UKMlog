<?php

###
# Logg-visning for innslag
# Skrevet 12.02.2016 av Asgeir S. Hustad.
# asgeirsh@ukmmedia.no
###

require_once('UKM/inc/ukmlog.inc.php');
require_once('UKM/innslag.class.php');
$pl_id = get_option('pl_id');
// Hvis b_id eller action_id er et parameter, 
// last kun inn logg-hendelser som relaterer til det innslaget og / eller den hendelsen.
if ((isset($_GET['b_id']) && !empty($_GET['b_id'])) || (isset($_GET['action_id'])  && !empty($_GET['action_id']) ) ) {
	$b_id = false;
	$band = false;
	if (isset($_GET['b_id'])) {
		$b_id = $_GET['b_id'];
		$band = new innslag($b_id, false);
	}
	$band_qry = ($b_id ? "AND `l`.`log_the_object_id` = ".$b_id : "");
	$action_id = false;
	if (isset($_GET['action_id']) && !empty($_GET['action_id'])) 
		$action_id = $_GET['action_id'];
	$action = ($action_id ? 
					"`l`.`log_action` = ".$action_id : 
					"`l`.`log_action` > 300 AND 	
					 `l`.`log_action` < 400");
	
	$qry = new SQL("SELECT *
					FROM `log_log` AS `l` 
					LEFT JOIN `log_actions` AS `la` ON (`l`.`log_action` = `la`.`log_action_id`)
					WHERE 	
					".$action."
					AND 	`l`.`log_pl_id` = '".$pl_id."'
					".$band_qry."
					ORDER BY `l`.`log_time` DESC
					;");	
	$res = $qry->run();
} else {
	// Hvis ingen get-parametere, 
	// last kun inn logg-hendelser som relaterer til innslag.
	$qry = new SQL("SELECT *
				FROM `log_log` AS `l` 
				LEFT JOIN `log_actions` AS `la` ON (`l`.`log_action` = `la`.`log_action_id`)
				WHERE 	`l`.`log_action` > 300 
				AND 	`l`.`log_action` < 400
				AND 	`l`.`log_pl_id` = '".$pl_id."'
				ORDER BY `l`.`log_time` DESC
				;");
	$res = $qry->run();
}
?>
<div class="wrap">
	<h3>Logg for innslag</h3>
	<form>
		<input type="hidden" name="page" value="UKMlog_band">
		<label for="b_id">B-id:</label>
		<input id="b_id" name="b_id" class="form-control" value=<?php if(isset($b_id)) echo '"'.$b_id.'"'; ?> >
		<label for="action_id">Vis:</label>
		<select id="action_id" name="action_id">
			<option value="">Alle hendelser</option>
			<option value="311" <?php if($action_id == "311") echo 'selected'; ?> >Meldte av innslaget</option>
			<option value="301" <?php if($action_id == "301") echo 'selected'; ?> >Endret innslagsnavn</option>
			<option value="302" <?php if($action_id == "302") echo 'selected'; ?> >Endret kontaktperson</option>
			<option value="303" <?php if($action_id == "303") echo 'selected'; ?> >Skal vise beskrivelse</option>
			<option value="304" <?php if($action_id == "304") echo 'selected'; ?> >Endret innslagsstatus</option>
			<option value="305" <?php if($action_id == "305") echo 'selected'; ?> >Endret kategori</option>
			<option value="306" <?php if($action_id == "306") echo 'selected'; ?> >Endret sjanger</option>
			<option value="307" <?php if($action_id == "307") echo 'selected'; ?> >Endret kommune</option>
			<option value="308" <?php if($action_id == "308") echo 'selected'; ?> >Endret tekniske behov</option>
			<option value="309" <?php if($action_id == "309") echo 'selected'; ?> >Endret beskrivelse</option>
			<option value="310" <?php if($action_id == "310") echo 'selected'; ?> >Endret beskrivelse (konferansier)</option>
			<option value="312" <?php if($action_id == "312") echo 'selected'; ?> >Opprettet innslaget</option>
			<option value="313" <?php if($action_id == "313") echo 'selected'; ?> >Endret innslagstype</option>
			<option value="314" <?php if($action_id == "314") echo 'selected'; ?> >Påmeldingstidspunkt</option>
			<option value="315" <?php if($action_id == "315") echo 'selected'; ?> >SMS-validert av</option>
			<option value="316" <?php if($action_id == "316") echo 'selected'; ?> >UKMdelta-bruker</option>
			<option value="317" <?php if($action_id == "317") echo 'selected'; ?> >Innslagsstatus-tekst</option>
		</select>
		<input type="submit" class="btn btn-primary" value="Filtrer">
	</form>
	<p>Logg-hendelser som ikke starter med et navn er som regel UKMdelta-brukere. Ikke synlig pga annen logg-struktur.</p>
	<?php 
		if ($b_id) echo '<h4>Innslags-ID: '.$b_id.'</h4>'; 
		if ($b_id) echo '<h4>Innslags-navn: '.$band->get('b_name').'</h4>'; 
	?>

	<?php if (!$b_id) echo '<h4>Logg for alle innslag:</h4>'; ?>


	<?php
		while($row = mysql_fetch_assoc($res)) {
			echo UKMlog_read($row['log_id']);
		}
	?>
</div>