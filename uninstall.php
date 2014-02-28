<?php

/*  Uninstall file for Zstore-Manager.   Deletes the tables from the database if the plugin is uninstalled and files deleted 
*/
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function kwikthumbs_delete_plugin() {
	delete_option( 'zstore_manager_settings' );
	
}	


kwikthumbs_delete_plugin() ;
?>
