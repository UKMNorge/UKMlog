<?php
global $blog_id;
$qry = new SQL("SELECT 
					`pl_name`,
					`t_comment`,
					`t_credits`,
					(SELECT COUNT(`tr_id`)
							FROM `log_sms_transaction_recipients` AS `t1`
							WHERE `t1`.`t_id` = `trans`.`t_id`) AS `mottakere`
				FROM `log_sms_transactions` AS `trans`
				JOIN `smartukm_place` AS `place` ON (`place`.`pl_id` = `trans`.`pl_id`)
				WHERE `t_action` = 'sendte_sms_for' "
				.($blog_id != 1 ? "WHERE `place`.`pl_id` = '#pl_id'" : "")
				."ORDER BY `t_id` DESC
				LIMIT 250",
				array('pl_id' => get_option('pl_id')));
$res = $qry->run();
$m = new monstring($plid);
?>

<div class="wrap">
	<div id="icon-edit-pages">
		<img src="<?= UKMN_ico('mobile', 32,false)?>" style="float: left; margin-top: 10px; margin-right: 10px;" width="32" />
	</div>
	<h2>Status SMS-meldinger/h2>
	<p>Viser siste 250 meldinger</p>
</div>

<ul class="log">
	<li class="header">
		<div class="time">Mønstring</div>
		<div class="user">Kostnad <br />(eks. refunderinger)</div>
		<div class="user">Antall Mottakere</div>
		<div class="message">Melding</div>
	</li>
<?php
while($r = mysql_fetch_assoc($res)){
	$m = new monstring($r['pl_id']);
?>
	<li class="trans">
		<div class="time"><?= utf8_encode($r['pl_name'])?></div>
		<div class="action"><?= abs($r['t_credits'])?> credits</div>
		<div class="user"><?= $r['mottakere']?></div>
		<div class="message"><?= utf8_encode($r['t_comment'])?></div>
		<div class="clear"></div>
	</li>
<?php
}
?></ul>