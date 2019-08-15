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
	add_action('admin_menu', 'UKMlog_menu', 400);
}

## CREATE A MENU
function UKMlog_menu() {

	$page_log = add_submenu_page(
		'UKMmonstring',
		'Logg',
		'Logg',
		'editor',
		'UKMlog_gui',
		'UKMlog_gui'
	);
	
	$page_log_innslag = add_submenu_page(
		'UKMmonstring',
		'Logg for innslag',
		'Logg for innslag',
		'editor',
		'UKMlog_band',
		'UKMlog_band'
	);

	add_action(
		'admin_print_styles-' . $page_log,
		'UKMlog_scripts_and_styles'
	);
	add_action(
		'admin_print_styles-' . $page_log_innslag,
		'UKMlog_scripts_and_styles'
	);
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
	while($row = SQL::fetch($logg)){
		echo UKMlog_read($row['log_id']);
	}
}

function UKMlog_rapport_statistikk(){
	require_once('gui_rapporter.logg.php');
}

function UKMlog_band() {
	require_once('gui_band.logg.php');
}
?>