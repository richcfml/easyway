<?php
/*
Plugin Name: Social Share Button
Plugin URI: 
Description: Fully responsive and mobile ready meet the ssb showcase plugin for wordpress.
Version: 1.6
Author: projectW
Author URI: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;

define('ssb_plugin_url', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
define('ssb_plugin_dir', plugin_dir_path( __FILE__ ) );
define('ssb_wp_url', 'http://wordpress.org/plugins/social-share-button/' );
define('ssb_pro_url', '' );
define('ssb_demo_url', '' );
define('ssb_conatct_url', '' );
define('ssb_qa_url', 'http://wordpress.org/support/plugin/social-share-button' );
define('ssb_plugin_name', 'Social Share Button' );
define('ssb_share_url', 'https://wordpress.org/plugins/social-share-button/' );



require_once( plugin_dir_path( __FILE__ ) . 'themes/icons-body.php');
require_once( plugin_dir_path( __FILE__ ) . 'themes/icons-style.php');

require_once( plugin_dir_path( __FILE__ ) . 'includes/ssb-functions.php');



//Themes php files
//require_once( plugin_dir_path( __FILE__ ) . 'themes/flat/index.php');




function ssb_init_scripts()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('ssb_js', plugins_url( '/js/ssb-scripts.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script('ssb_js', 'ssb_ajax', array( 'ssb_ajaxurl' => admin_url( 'admin-ajax.php')));
		
		wp_enqueue_style('ssb-css', ssb_plugin_url.'css/ssb-style.css');
		wp_enqueue_style('ssb-admin', ssb_plugin_url.'css/ssb-admin.css');
		
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'ssb-color-picker', plugins_url('/js/ssb-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		

		
		// Style for themes
		//wp_enqueue_style('ssb-style-flat', ssb_plugin_url.'themes/flat/style.css');
	

		
	}
add_action("init","ssb_init_scripts");




register_activation_hook(__FILE__, 'ssb_activation');
register_uninstall_hook(__FILE__, 'ssb_uninstall');

function ssb_activation(){
		$ssb_version= "1.6";
		update_option('ssb_version', $ssb_version); //update plugin version.
		
		$ssb_customer_type= "free"; //customer_type "free"
		update_option('ssb_customer_type', $ssb_customer_type); //update plugin version.
	}




function ssb_uninstall()
	{
		
		delete_post_meta_by_key( 'ssb_post_sites' ); //delete post meta from post
		
		delete_option( 'ssb_share_version' ); //delete option from database.
		delete_option( 'ssb_share_filter_posttype' ); //delete option from database.
		delete_option( 'ssb_share_content_display' ); //delete option from database.	
		delete_option( 'ssb_share_target_tab' ); //delete option from database.			
		delete_option( 'ssb_share_content_themes' ); //delete option from database.	
		delete_option( 'ssb_share_content_position' ); //delete option from database.			
		delete_option( 'ssb_share_content_icon_margin' ); //delete option from database.			
	
	}


add_action('wp_head', 'ssb_open_graph');

function ssb_open_graph()
	{
		$open_graph = '';
			
		if ( is_singular() ) 
			{
				$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
				$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
				$open_graph .= '<meta property="og:image" content="'.$post_thumbnail_url.'" />';
			} 
		else 
			{

			}
			
		echo $open_graph;
	}





add_action('admin_menu', 'ssb_menu_init');
add_action('admin_init', 'ssb_options_init' );


function ssb_options_init(){
	register_setting('ssb_plugin_options', 'ssb_share_content_display');
	register_setting('ssb_plugin_options', 'ssb_share_filter_posttype');	
	register_setting('ssb_plugin_options', 'ssb_share_target_tab');	
	register_setting('ssb_plugin_options', 'ssb_share_content_themes');	
	register_setting('ssb_plugin_options', 'ssb_share_content_position');		
	register_setting('ssb_plugin_options', 'ssb_share_content_icon_margin');		

    }



function ssb_menu_help(){
	include('ssb-help.php');	
	}


function ssb_menu_settings(){
	include('ssb-settings.php');	
	}



function ssb_menu_init() {
	add_menu_page(__('ssb','ssb'), __('SSB Settings','ssb'), 'manage_options', 'ssb_menu_settings', 'ssb_menu_settings');
	
	add_submenu_page('ssb_menu_settings', __('Help & Upgrade','menu-ssb'), __('Help & Upgrade','menu-ssb'), 'manage_options', 'ssb_menu_help', 'ssb_menu_help');
	}



?>