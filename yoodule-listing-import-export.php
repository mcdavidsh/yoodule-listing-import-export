<?php

/*
Plugin Name: Yoodule Listing Import Export
Plugin URI: https://github.com/mcdavidsh/yd-listing-import-export
Description: A brief description of the Plugin.
Version: 1.0
Author: Mcdavid Obioha
Author URI: https://github.com/mcdavidsh
License: A "Slug" license name e.g. GPL2
*/

include plugin_dir_path( __FILE__ ) . 'includes/pages.php';

if ( !class_exists( 'YooduleListImportExport' ) ) {
	define( 'YD_PLUGIN_FILE', __FILE__ );
	require( plugin_dir_path( __FILE__ ) . 'functions.php' );
}

if ( !function_exists( "yd_scripts" ) ) {
	add_action( "wp_enqueue_scripts", "yd_scripts" );
	function yd_scripts() {
		wp_enqueue_style( "yd_css", plugins_url( "assets/css/listing.css", __FILE__ ) );
		wp_enqueue_style( "yd_css_detail", plugins_url( "assets/css/details.css", __FILE__ ) );
        wp_enqueue_script( 'yd_js_script', plugin_dir_url(__FILE__). 'assets/js/main.js', array(), '1.0', true );
	}
}

if ( !function_exists( "yd_admin_scripts" ) ) {
    add_action("admin_enqueue_scripts","yd_admin_scripts");
    function yd_admin_scripts(){
        wp_enqueue_style("yd_admin_style", plugin_dir_url(__FILE__). "assets/css/admin-style.css");
    }

}


