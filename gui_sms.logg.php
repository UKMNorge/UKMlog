<?php
global $blog_id;
$qry = new SQL("SELECT 
					SUM(`t_credits`) AS `credits`,
					`pl_name`,
					(SELECT 
						SUM(`t_credits`) 
						FROM `log_sms_transactions` AS `t1` 
						WHERE `t1`.`pl_id` = `trans`.`pl_id`
						AND `t1`.`t_action` = 'sendte_sms_for'
					) AS `sendt`,
					(SELECT 
						COUNT(`t_id`) 
						FROM `log_sms_transactions` AS `t2` 
						WHERE `t2`.`pl_id` = `trans`.`pl_id`
						AND `t2`.`t_action` = 'sendte_sms_for'
					) AS `meldinger`,
					(SELECT 
						SUM(`t_credits`) 
						FROM `log_sms_transactions` AS `t4` 
						WHERE `t4`.`pl_id` = `trans`.`pl_id`
						AND `t4`.`t_action` = 'mottok'
						AND `t_credits` != '200'
					) AS `refundert_credits`,

					(SELECT 
						COUNT(`t_id`) 
						FROM `log_sms_transactions` AS `t3` 
						WHERE `t3`.`pl_id` = `trans`.`pl_id`
						AND `t3`.`t_action` = 'mottok'
						AND `t_credits` != '200'
					) AS `refunderte`
					
				FROM `log_sms_transactions` AS `trans`
				JOIN `smartukm_place` AS `place` ON (`place`.`pl_id` = `trans`.`pl_id`) "
				.($blog_id != 1 ? "WHERE `place`.`pl_id` = '#pl_id'" : "")
			."	GROUP BY `place`.`pl_id`
				ORDER BY `credits` ASC",
				array('pl_id' => get_option('pl_id')));
$res = $qry->run();
$m = new monstring($plid);
?>

<div class="wrap">
	<div id="icon-edit-pages">
		<img src="<?= UKMN_ico('mobile', 32,false)?>" style="float: left; margin-top: 10px; margin-right: 10px;" width="32" />
	</div>
	<h2>Status SMS-credits</h2>
</div>

<ul class="log">
	<li class="header">
		<div class="time">Mønstring</div>
		<div class="action">Credits</div>
		<div class="user">Forsøkt brukte credits</div>
		<div class="user">Antall feilsendinger</div>
		<div class="user">Antall ulike meldinger</div>

	</li>
<?php
while($r = mysql_fetch_assoc($res)){
	$m = new monstring($r['pl_id']);
?>
	<li class="trans">
		<div class="time"><?= utf8_encode($r['pl_name'])?></div>
		<div class="action"><?= $r['credits']?></div>
		<div class="user"><?= abs($r['sendt'])?></div>
		<div class="user"><?= $r['refundert_credits']?> credits, <?= $r['refunderte']?> mottakere</div>
		<div class="user"><?= $r['meldinger']?></div>
		<div class="clear"></div>
	</li>
<?php
}
?></ul>