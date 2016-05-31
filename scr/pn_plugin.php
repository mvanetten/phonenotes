<?php
/*
Plugin Name: Phone Notes
Plugin URI: http://www.sunnus.nl
Description: Register Phone Notes
Version: 1.4.2
Author: Mark van Etten
Author URI: http://www.sunnus.nl
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require("pn_bootstrap.php");


/**
 * phone_notes_register_menu_page
 * Add pages to admin menu
 */ 
function phone_notes_register_menu_page() 
{		
	add_menu_page('Phone Notes','Phone Notes', 'edit_others_pages', 'phone_notes','phone_notes_pages',plugins_url( 'phone_notes/images/icon.png' ),null);
	add_submenu_page( 'Phone Notes', 'List', 'List Notes','edit_others_pages', 'list');
	add_submenu_page( 'phone_notes', 'Create', 'Create Note','edit_others_pages', 'phone_notes_create_page','phone_notes_create_page');
}
add_action( 'admin_menu', 'phone_notes_register_menu_page' );


/**
 * phone_notes_pages
 * Page action handler
 */ 
function phone_notes_pages()
{
	if ($_GET['action'] == 'delete' && is_numeric($_GET['id']))
	{
		$noteid = filter_var ( $_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		$note = phone_notes_get_note($noteid);
		phone_notes_delete_page($note);
	}
	elseif ($_GET['action'] == 'edit' && is_numeric($_GET['id']))
	{
		$noteid = filter_var ( $_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		$note = phone_notes_get_note($noteid);
		phone_notes_edit_page($note);
	}
	else
	{
		$notes = phone_notes_get_notes();
		phone_notes_index_page($notes);
	}
}

/**
 * phone_notes_add_my_styles
 * Add stylesheets to admin header
 */ 
function phone_notes_add_my_styles() 
{
    wp_register_style( 'intimidatetime_css', plugin_dir_url( __FILE__ ) .'css/Intimidatetime.min.css', false, '0.2.0' );
    wp_enqueue_style( 'intimidatetime_css' );
}
add_action( 'admin_enqueue_scripts', 'phone_notes_add_my_styles' );


/**
 * phone_notes_add_my_scripts
 * Add javascripts to admin header
 */ 
function phone_notes_add_my_scripts() 
{
    wp_enqueue_script('phone_notes', plugin_dir_url( __FILE__ ) . '/scripts/phone_notes.js');
	wp_enqueue_script( 'intimidatetime_script', plugin_dir_url( __FILE__ ) .'scripts/Intimidatetime.min.js',false, '1.0.0');
}
add_action( 'admin_enqueue_scripts', 'phone_notes_add_my_scripts' );


/**
 * phone_notes_process_post
 * Process incomming POST data before wp is fullyloaded
 */ 
function phone_notes_process_post() 
{
    $redirecturl = admin_url('admin.php?page=phone_notes');
	
	if(isset($_POST) && isset($_POST["action"]))
	{
		$pn_name = filter_var ( $_POST["pn_name"], FILTER_SANITIZE_STRING);
		$pn_number = filter_var ( $_POST["pn_number"], FILTER_SANITIZE_NUMBER_INT);
		$pn_date = filter_var ( $_POST["pn_date"], FILTER_SANITIZE_STRING);
		$pn_notes = filter_var ( $_POST["pn_notes"], FILTER_SANITIZE_STRING);
		$pn_id = filter_var ( $_POST["pn_id"], FILTER_SANITIZE_NUMBER_INT);
		
		//Construct Model Note ($id = null,$name,$number,$datetime,$notes)
		$Note = new Note($pn_id,$pn_name,$pn_number,$pn_date,$pn_notes);
		
		if ($_POST["action"] == "downloadpdf")
		{
			phone_notes_download_pdf();
		}
		
		if ($_POST["action"] == "sendpdf")
		{
			if (phone_notes_send_pdf())
			{
				add_action( 'admin_notices', 'phone_notes_admin_notice__success');
			}
			else
			{
				add_action( 'admin_notices', 'phone_notes_admin_notice__error');
			}
			
		}
		
		if ($_POST["action"] == "phone_notes_put_note")
		{
			if ($Note->IsVallid())
			{
				if (phone_notes_put_note($Note))
				{
					add_action( 'admin_notices', 'phone_notes_admin_notice__success' );
					wp_redirect( $redirecturl );
				}
				else
				{
					add_action( 'admin_notices', 'phone_notes_admin_notice__error' );	
				}
			}
			else
			{
				add_action( 'admin_notices', 'phone_notes_admin_notice__warning' );	
			}
			
		}
		if ($_POST["action"] == "phone_notes_get_note" && is_numeric($Note->getId()))
		{
			if (phone_notes_put_note($Note->getId()))
			{
				add_action( 'admin_notices', 'phone_notes_admin_notice__success' );
				wp_redirect( $redirecturl );				
			}
			else
			{
				add_action( 'admin_notices', 'phone_notes_admin_notice__error' );
			}
		}
		if ($_POST["action"] == "phone_notes_delete_note" && is_numeric($Note->getId()))
		{
			if (phone_notes_delete_note($Note->getId()))
			{
				add_action( 'admin_notices', 'phone_notes_admin_notice__success' );	
				wp_redirect( $redirecturl );	
			}
			else
			{
				add_action( 'admin_notices', 'phone_notes_admin_notice__error' );
			}
		}
	}
}
add_action( 'admin_init', 'phone_notes_process_post' );




/**
 * phone_notes_admin_notice__error
 * Create a error message to admin page
 */ 
function phone_notes_admin_notice__error() 
{
	$class = 'notice notice-error';
	$message = __( "<strong>Yep! An error has occurred.</strong>", "sample-text-domain" );
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}

/**
 * phone_notes_admin_notice__warning
 * Create a warning message to admin page
 */ 
function phone_notes_admin_notice__warning() 
{
	$class = 'notice notice-warning';
	$message = __( "<strong>Validation Warning</strong>", "sample-text-domain" );
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}

/**
 * phone_notes_admin_notice__success
 * Create a success message to admin page
 */ 
function phone_notes_admin_notice__success() 
{
	$class = 'notice notice-success is-dismissible';
	$message = __( "<strong>Success!</strong>", "sample-text-domain" );
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}



/**
 * phone_notes_install
 * Install script for this plugin. 
 */ 
function phone_notes_install() 
{
	global $wpdb;
	global $wp_version;
	$php_version = PHP_VERSION;
	
	$min_version_wp = '4.5.2';
	$min_version_php = '5.3.0';
	if (version_compare($php_version, $min_version_php) >= 0 && version_compare($wp_version, $min_version_wp) >= 0)
	{
		$table_name = $wpdb->prefix . 'phone_notes';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				id int(20) NOT NULL AUTO_INCREMENT,
				name varchar(60) NOT NULL,
				number text(20) NOT NULL,
				date datetime NOT NULL,
				notes varchar(1024) NOT NULL,
				user_id int(20) NOT NULL,
				create_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				update_timestamp timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				UNIQUE  KEY id (id),
				KEY id_2 (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	else
	{
		deactivate_plugins( basename( __FILE__ ) );
		wp_die("<p>The <strong>Phone Notes</strong> plugin requires WordPress $min_version_wp or greater (<i>current: $wp_version</i>) and PHP $min_version_php or greater (<i>current: $php_version</i>).</p>","Plugin Activation Error. Please upgrade Wordpress and PHP to latest version.",  array( "response"=>200, "back_link"=>TRUE ) );
	}
	
	
}
register_activation_hook( __FILE__, 'phone_notes_install' );



?>