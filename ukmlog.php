<?php  
/* 
Plugin Name: UKM Logg
Plugin URI: http://www.ukm-norge.no
Description: UKM Norge admin - Viser alle loggførte rader for den gitte mønstringen
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/
date_default_timezone_set('Europe/Oslo');

require_once('UKM/monstring.class.php');
require_once('UKM/inc/ukmlog.inc.php');


## HOOK MENU AND SCRIPTS
if(is_admin()) {
	add_action('admin_menu', 'UKMlog_menu',207);
}

## CREATE A MENU
function UKMlog_menu() {
	add_menu_page('Logg', 'Logg', 'superadmin', 'UKMlog_gui', 'UKMlog_gui', 'http://ico.ukm.no/log-menu.png',501);
	$page = add_submenu_page('UKMlog_gui', 'Rapporter', 'Rapporter', 'superadmin', 'UKMlog_rapport_statistikk','UKMlog_rapport_statistikk');
	add_action( 'admin_print_styles-' . $page, 'UKMlog_scripts_and_styles' );
	$page = add_submenu_page('UKMlog_gui', 'SMS-credits', 'SMS-credits', 'superadmin', 'UKMlog_sms','UKMlog_sms');
	add_action( 'admin_print_styles-' . $page, 'UKMlog_scripts_and_styles' );
	$page = add_submenu_page('UKMlog_gui', 'SMS-meldinger', 'SMS-meldinger', 'superadmin', 'UKMlog_smsmessages','UKMlog_smsmessages');
	add_action( 'admin_print_styles-' . $page, 'UKMlog_scripts_and_styles' );
}

function UKMlog_scripts_and_styles(){
	wp_enqueue_style( 'UKMlogg_style', WP_PLUGIN_URL .'/UKMlog/style.logg.css');
}


function UKMlog_gui() {
	$place = new monstring(get_option('pl_id'));

	echo '<h3>Logg for '.$place->g('pl_name').'</h3>';

	$logg = new SQL("SELECT `log_id` FROM `log_log`	
					WHERE `log_pl_id` = '#plid'
					ORDER BY `log_id` DESC",
				array('plid'=>$place->g('pl_id')));

	$logg = $logg->run();
	while($row = mysql_fetch_assoc($logg)){
		echo UKMlog_read($row['log_id']);
	}
}

function UKMlog_rapport_statistikk(){
	require_once('gui_rapporter.logg.php');
}

function UKMlog_sms(){
	require_once('gui_sms.logg.php');
}

function UKMlog_smsmessages(){
	require_once('gui_smsmessages.logg.php');
}
?>