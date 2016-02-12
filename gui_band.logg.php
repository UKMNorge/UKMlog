<?php
require_once('UKM/inc/ukmlog.inc.php');
require_once('UKM/innslag.class.php');
$pl_id = get_option('pl_id');
// Hvis b_id er et parameter, 
// last kun inn logg-hendelser som relaterer til det innslaget.
if (isset($_GET['b_id'])) {
	$b_id = $_GET['b_id'];
	$band = new innslag($b_id, false);
	#var_dump($band);
	$qry = new SQL("SELECT *
					FROM `log_log` AS `l` 
					LEFT JOIN `log_actions` AS `la` ON (`l`.`log_action` = `la`.`log_action_id`)
					WHERE 	`l`.`log_action` > 300 
					AND 	`l`.`log_action` < 400
					AND 	`l`.`log_pl_id` = '".$pl_id."'
					AND 	`l`.`log_the_object_id` = '".$b_id."'
					ORDER BY `l`.`log_time`
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
				ORDER BY `l`.`log_time`
				;");
	$res = $qry->run();
}
#var_dump($res);
// GROUP BY `l`.`log_the_object_id`
?>
<div class="wrap">
	<h3>Logg for innslag</h3>
	<form>
		<input type="hidden" name="page" value="UKMlog_band">
		<label for="b_id">B-id:</label><input id="b_id" name="b_id" class="form-control">
		<input type="submit" class="btn btn-primary" value="Filtrer">
	</form>
	<p>Logg-hendelser som ikke starter med et navn er som regel UKMdelta-brukere. Ikke synlig pga annen logg-struktur.</p>
	<?php 
		if (isset($b_id)) echo '<h4>Innslags-ID: '.$b_id.'</h4>'; 
		if (isset($band)) echo '<h4>Innslags-navn: '.$band->get('b_name').'</h4>'; 
	?>

	<?php if (!isset($b_id)) echo '<h4>Logg for alle innslag:</h4>'; ?>


	<?php
		while($row = mysql_fetch_assoc($res)) {
			echo UKMlog_read($row['log_id']);
		}
	?>
</div>