<?php
/*
* Plugin Name: Simple Alert Bar
* Plugin URI: http://github.com/megmorsie/rose-alertbar/
* Description: This plugin will display an alert bar at the top of your website. 
* Version: 1.0.2
* Author: Megan Morsie
* Author URI: http://megabyterose.com/
* License: GPL2
*/

// Plugin Globals
if (!defined('ROSE_ALERT_BAR_THEME_DIR'))
    define('ROSE_ALERT_BAR_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('ROSE_ALERT_BAR'))
    define('ROSE_ALERT_BAR', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('ROSE_ALERT_BAR_PLUGIN_DIR'))
    define('ROSE_ALERT_BAR_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . ROSE_ALERT_BAR);

if (!defined('ROSE_ALERT_BAR_PLUGIN_URL'))
    define('ROSE_ALERT_BAR_PLUGIN_URL', WP_PLUGIN_URL . '/' . ROSE_ALERT_BAR);

// Version Globals
if (!defined('ROSE_ALERT_BAR_VERSION_KEY'))
    define('ROSE_ALERT_BAR_VERSION_KEY', 'rose_alert_bar_version');

if (!defined('ROSE_ALERT_BAR_VERSION_NUM'))
    define('ROSE_ALERT_BAR_VERSION_NUM', '1.0.2');

add_option(ROSE_ALERT_BAR_VERSION_KEY, ROSE_ALERT_BAR_VERSION_NUM);

// Display menu item and settings page
if ( is_admin() ){ 
  add_action( 'admin_menu', 'rose_alert_bar_menu' );
  add_action( 'admin_init', 'rose_alert_bar_register_settings' );
} else {}

// Enqueue scripts/styles
add_action( 'wp_enqueue_scripts', 'rose_alert_bar_css' );
add_action( 'wp_enqueue_scripts', 'rose_font_awesome' );

function rose_alert_bar_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style', $plugin_url . 'css/alert-bar.css' );
    wp_enqueue_style('font-awesome', $plugin_url . 'css/font-awesome.min.css' );
    wp_enqueue_script('alert-bar-js', $plugin_url . 'js/alert-bar.js' );
}

function rose_font_awesome() {
	global $wp_styles, $is_IE;
	wp_enqueue_style( 'rose-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.min.css', array(), '3.2.0' );
	if ( $is_IE ) {
		wp_enqueue_style( 'rose-font-awesome-ie', '//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome-ie7.min.css', array('rose-font-awesome'), '3.2.0' );
		// Add support for IE 7 and older
		$wp_styles->add_data( 'rose-font-awesome-ie', 'conditional', 'lte IE 7' );
	}
}

// Add alert bar to top of all pages
add_action('wp_head', 'rose_rose_alert_bar_content');

// Add menu page
function rose_alert_bar_menu() {
	add_menu_page( 'Alert Options', 'Alert Bar', 'manage_options', 'rose-alert-bar-page', 'rose_alert_bar_options' );
}

// Add options page
function rose_alert_bar_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<h2>Alert Bar Settings</h2>';
	echo '<p>Use the settings below to display an alert bar message at the top of the portal.</p><hr /><br />';
	echo '<form method="post" action="options.php">';
	settings_fields( 'rose-alert-bar-group' );
	do_settings_sections( 'rose-alert-bar-group' );
	echo '<input type="checkbox" name="show_rose_alert_bar" value="1" ' . checked( 1, get_option( 'show_rose_alert_bar' ), false ) . ' /> Check if you want to display the alert bar.<br /><br />';
	echo '<input type="checkbox" name="rose_alert_bar_test_mode" value="1" ' . checked( 1, get_option( 'rose_alert_bar_test_mode' ), false ) . ' /> Check for <strong>test mode</strong>. <em>Alert Bar will only show to administrator accounts.</em><br /><br />';
	echo '<p><strong>Alert Bar Content:</strong></p><textarea name="rose_alert_bar_content" class="large-text">' . esc_attr( get_option('rose_alert_bar_content') ) . '</textarea>';
	submit_button();
	echo '</form>';
	echo '<hr /><br /><small>Version ' . ROSE_ALERT_BAR_VERSION_NUM . '</small></div>';
}

// Add fields for form
function rose_alert_bar_register_settings() {
	register_setting( 'rose-alert-bar-group', 'rose_alert_bar_content' );
	register_setting( 'rose-alert-bar-group', 'show_rose_alert_bar' );
	register_setting( 'rose-alert-bar-group', 'rose_alert_bar_test_mode' );
}

// Echo out alert box if show checkbox is checked
function rose_rose_alert_bar_content() {
	$test = get_option( 'rose_alert_bar_test_mode' );
	// Show to admins if test mode is checked
	if ($test['rose_alert_bar_test_mode'] == '1') {
		if ( current_user_can( 'manage_options' ) )  {
			$content = esc_attr( get_option('rose_alert_bar_content') );
			// Convert any links
			$content = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target=\"_blank\">\\0</a>", $content);
			echo '<div class="alert-bar" style="display: none;"><i class="fa fa-exclamation-triangle"></i> ' . $content . ' (You are in Test Mode.) <i class="fa fa-times"></i></div><div class="mini-alert"><i class="fa fa-exclamation-triangle"></i></div>';
		}
	}
	$show = get_option( 'show_rose_alert_bar' );
	if ($show['show_rose_alert_bar'] == '1' && ($test['rose_alert_bar_test_mode'] !== '1')) {
		$content = esc_attr( get_option('rose_alert_bar_content') );
		// Convert any links
		$content = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target=\"_blank\">\\0</a>", $content);
		echo '<div class="alert-bar" style="display: none;"><i class="fa fa-exclamation-triangle"></i> ' . $content . ' <i class="fa fa-times"></i></div><div class="mini-alert"><i class="fa fa-exclamation-triangle"></i></div>';
	}
}

?>