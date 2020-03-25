<?php

use UKMNorge\Database\SQL\Query;

if( isset($_GET['year']) && is_numeric($_GET['year']) ) {
	$sesong = $_GET['year'];
}
else {
	$sesong = get_option('season');
}

$lokal = new Query("SELECT
`f_rapport` AS `rapport`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t1` 
	WHERE `t1`.`f_rapport` = `rapport` 
	AND `t1`.`f_season` = '#season'
	AND `t1`.`f_pl_id` = '#plid' 
	AND `t1`.`f_type` = 'skjerm') AS `skjerm`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t2` 
	WHERE `t2`.`f_rapport` = `rapport` 
	AND `t2`.`f_season` = '#season'
	AND `t2`.`f_pl_id` = '#plid' 
	AND `t2`.`f_type` = 'word') AS `word`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t3` 
	WHERE `t3`.`f_rapport` = `rapport` 
	AND `t3`.`f_season` = '#season'
	AND `t3`.`f_pl_id` = '#plid' 
	AND `t3`.`f_type` = 'excel') AS `excel`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t4` 
	WHERE `t4`.`f_rapport` = `rapport` 
	AND `t4`.`f_season` = '#season'
	AND `t4`.`f_pl_id` = '#plid' 
	AND `t4`.`f_type` = 'print') AS `print`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t5` 
	WHERE `t5`.`f_rapport` = `rapport` 
	AND `t5`.`f_season` = '#season'
	AND `t5`.`f_pl_id` = '#plid' 
	AND `t5`.`f_type` = 'mail') AS `mail`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t6` 
	WHERE `t6`.`f_rapport` = `rapport` 
	AND `t6`.`f_season` = '#season'
	AND `t6`.`f_pl_id` = '#plid' 
	AND `t6`.`f_type` = 'sms') AS `sms` 
FROM `log_rapporter_format` 
WHERE `f_pl_id` = '#plid' 
GROUP BY `rapport`",
				array('plid'=>get_option('pl_id'), 'season' => $sesong ));
$lokal = $lokal->run();

$nasjonalt = new Query("SELECT
`f_rapport` AS `rapport`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t1` 
	WHERE `t1`.`f_rapport` = `rapport` 
	AND `t1`.`f_season` = '#season'
	AND `t1`.`f_pl_id` != '#plid' 
	AND `t1`.`f_type` = 'skjerm') AS `skjerm`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t2` 
	WHERE `t2`.`f_rapport` = `rapport` 
	AND `t2`.`f_season` = '#season'
	AND `t2`.`f_pl_id` != '#plid' 
	AND `t2`.`f_type` = 'word') AS `word`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t3` 
	WHERE `t3`.`f_rapport` = `rapport` 
	AND `t3`.`f_season` = '#season'
	AND `t3`.`f_pl_id` != '#plid' 
	AND `t3`.`f_type` = 'excel') AS `excel`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t4` 
	WHERE `t4`.`f_rapport` = `rapport` 
	AND `t4`.`f_season` = '#season'
	AND `t4`.`f_pl_id` != '#plid' 
	AND `t4`.`f_type` = 'print') AS `print`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t5` 
	WHERE `t5`.`f_rapport` = `rapport` 
	AND `t5`.`f_season` = '#season'
	AND `t5`.`f_pl_id` != '#plid' 
	AND `t5`.`f_type` = 'mail') AS `mail`,
(SELECT COUNT(`f_id`) 
	FROM `log_rapporter_format` AS `t6` 
	WHERE `t6`.`f_rapport` = `rapport` 
	AND `t6`.`f_season` = '#season'
	AND `t6`.`f_pl_id` != '#plid' 
	AND `t6`.`f_type` = 'sms') AS `sms` 
FROM `log_rapporter_format` 
GROUP BY `rapport`",
				array('plid'=>get_option('pl_id'), 'season' => $sesong ));
$nasjonalt = $nasjonalt->run();
?>

<div class="wrap">
	<div id="icon-edit-pages">
		<img src="<?= UKMN_ico('graph', 32,false)?>" style="float: left; margin-top: 10px; margin-right: 10px;" width="32" />
	</div>
	<h2>Statistikk rapporter</h2>
</div>
<h3>Denne mønstring</h3>
<ul class="rapportvisninger">
	<li class="header">
		<div class="rapport">Rapport</div>
		<div class="skjerm">Skjermvisning</div>
		<div class="word">Lastet ned word</div>
		<div class="excel">Lastet ned excel</div>
		<div class="print">Skriv ut</div>
		<div class="mail">Send e-post</div>
		<div class="sms">Send SMS</div>
	</li>
<?php 
while($r = Query::fetch($lokal)){ ?>
	<li class="rapport">
		<div class="rapport"><?= $r['rapport']?></div>
		<div class="skjerm"><?= $r['skjerm']?></div>
		<div class="word"><?= $r['word']?></div>
		<div class="excel"><?= $r['excel']?></div>
		<div class="print"><?= $r['print']?></div>
		<div class="mail"><?= $r['mail']?></div>
		<div class="sms"><?= $r['sms']?></div>
	</li>
<?php } ?>
</ul>
<div class="clear"></div>
<h3>Nasjonalt (unntatt denne)</h3>
<ul class="rapportvisninger">
	<li class="header">
		<div class="rapport">Rapport</div>
		<div class="skjerm">Skjermvisning</div>
		<div class="word">Lastet ned word</div>
		<div class="excel">Lastet ned excel</div>
		<div class="print">Skriv ut</div>
		<div class="mail">Send e-post</div>
		<div class="sms">Send SMS</div>
	</li>
<?php 
while($r = Query::fetch($nasjonalt)){ ?>
	<li class="rapport">
		<div class="rapport"><?= $r['rapport']?></div>
		<div class="skjerm"><?= $r['skjerm']?></div>
		<div class="word"><?= $r['word']?></div>
		<div class="excel"><?= $r['excel']?></div>
		<div class="print"><?= $r['print']?></div>
		<div class="mail"><?= $r['mail']?></div>
		<div class="sms"><?= $r['sms']?></div>
	</li>
<?php } ?>
</ul>