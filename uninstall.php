<?php

/*  Uninstall file for Zstore-Manager-Basic.   Deletes the tables from the database if the plugin is uninstalled and files deleted 
*/
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function zmb_delete_plugin() {
	delete_option( 'zstore_basic_manager_settings' );
	
}	


zmb_delete_plugin() ;
?>
