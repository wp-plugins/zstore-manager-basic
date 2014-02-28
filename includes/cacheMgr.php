<?php
if (!class_exists ('cacheMgr_Zstore_Basic')) {
class cacheMgr_Zstore_Basic {

// how long is the cache lifetime - in seconds? 
var $cachelife = 7200;
var $cache_dir;

/*
* Constructor 
*	- verifies that cache dir exists and is writeable
*	- sets up images and pages dirs
*	
*/
function __construct() {
clearstatcache();
	
	
	$this->cache_dir = plugin_dir_path( __FILE__ )  . 'c';
	
	// verify that the cache dir exists andis writeable...

	if(!file_exists( $this->cache_dir ) && !mkdir($this->cache_dir, 0777, true)) {
		echo 'Cache directory "c" needs to be created for cache support to work';
		exit;
	}
	if(file_exists( $this->cache_dir ) && !is_writeable( $this->cache_dir)) {
		echo  'Cache directory "c" exists, but is not writeable' ;
		exit;
	}
}

/*
* Checks to see if an image id is cached 
*
* @param $cacheid -  the image file to test - (ex: abcdefg.jpg)
*
* @return true if image exists in cache, false otherwise
*
*/
function is_image_cached( $cacheid ) {
	


	if( file_exists( $this->cache_dir . '/'. $cacheid )) {

		$cachetime = time() - $this->cachelife;
		if(  filemtime( $this->cache_dir. '/' . $cacheid ) < $cachetime ) {  // expired - blast it
			unlink( $this->cache_dir . '/' . $cacheid );
			return false;
		}
		return true;
	}
	return false;
}
/* clear the cache whether its time or not */
function clear_cache()
{
	$dh = opendir( $this->cache_dir );
	while (false !== ($fname = readdir($dh) ) ) {
		if( is_dir( $fname )) continue;  // ignore '.' and '..'
		unlink( $this->cache_dir . '/' . $fname );
	
	
	
	}

		closedir( $dh );

}
/*
* Iterate over cached resources, removing any that have expired
*
* @return - true if the cache cleanup completes sucessfully
*
*/
function clean_cache( ) {

	// images first..
	$dh = opendir( $this->cache_dir );
	
	$cachetime = time() - $this->cachelife;
	
	while (false !== ($fname = readdir($dh) ) ) {
	
		if( is_dir( $fname )) continue;  // ignore '.' and '..'
		
		if( filemtime( $this->cache_dir . '/' . $fname ) < $cachetime ) {  // expired -- blast it.
			unlink( $this->cache_dir . '/' . $fname );
			
		}	
	}
	
	closedir( $dh );
		
	// now the rss cache file
	$dh = opendir( $this->cache_dir );
	$cachetime = time() - $this->cachelife;
	while (false !== ($fname = readdir($dh) ) ) {
	
		if( is_dir( $fname )) continue;  // ignore '.' and '..'
		
		if(strstr($fname, "rsscache" )) {
			unlink( $this->cache_dir . '/' .$fname);	
		} 	
	
	}
	
	return true;
}

/*
* Set the lifetime of the cache (in seconds) 
*
* @param $lifetime - seconds to keep the cache alive (3600 = 1hr)
*
*/ 
	function set_lifetime( $lifetime ) {
		$this->cachelife = $lifetime;
	}

/*
* Get the lifetime of the cache (in seconds)
*
* @return $lifetime - returns the number of seconds to keep the cache alive (3600 = 1hr)
*
*/
	function get_lifetime() {
		return $this->cachelife;
	}


}
}

?>