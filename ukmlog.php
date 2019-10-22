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

require_once('UKM/Autoloader.php');

class UKMlog extends UKMNorge\Wordpress\Modul {
    public static $action = 'home';
    public static $path_plugin = null;

    public static function hook() {
        add_action('admin_menu', ['UKMlog','meny'], 400);
    }

    public static function meny() {
        $page = add_submenu_page(
            'UKMmonstring',
            'Logg',
            'Logg',
            'editor',
            'UKMlog',
            ['UKMlog','renderAdmin']
        );

        add_action(
            'admin_print_styles-' . $page,
            ['UKMlog','scripts_and_styles']
        );
    }

    public static function scripts_and_styles() {
        wp_enqueue_script('WPbootstrap3_js');
        wp_enqueue_style('WPbootstrap3_css');
    }
}

UKMlog::init(__DIR__);
UKMlog::hook();
?>