<?php
/*
Plugin Name: zStore Manager  Basic
Plugin URI: http://ikjweb.com
Description: Manager for Zazzle Store Products.  Allows a Zazzle shopkeeper or Affliate to display Zazzle products on an external website.  You can display the product name, price and description and limit the amount of products shown.  You can also use the cache so that images load faster.  Visit the <a href="options-general.php?page=z_store_basic_slug">Settings Page</a> for more options. 
Version: 2.1
Author: Ilene Johnson
Author URI: http://ikjweb.com/
Donate Link: http://ikjweb.com
Update Server:  
License: MIT License - http://www.opensource.org/licenses/mit-license.php
*/
define( 'ZAZZLE_BASIC_URL_BASE' , 'zazzle.com');
define ('ZSTORE_MANAGER', 'zstore_manager');
require_once 'includes/storedisplay.php';
require_once 'includes/cacheMgr.php';

class _Zazzle_Store_Manager_Basic
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $z_options = array();
	private $productTypes = array();

    /**
     * Start up
     */
    public function __construct()
    {
	

        add_action( 'admin_menu', array( $this, 'add_zstore_basic_plugin_page' ) );
		
        add_action( 'admin_init', array( $this, 'zstore_basic_page_init' ) );
		
		
		$this->get_product_types_list();
		
       
    }  
	private function get_product_types_list()
	{	
		$lines = file(plugins_url('producttypes.csv', __FILE__ ));

		foreach($lines as $line)
		{
			$line = trim($line);
			$this->productType[] = preg_split("/[,]+/", $line);	
			
			
		}
		
		
	
	
	
	}
	 public static function install_zstore_basic() {
            // do not generate any output here
			$zstore = array();
			$zstore['contributorHandle'] = '';
			$zstore['associateId'] = '';
			$zstore['productLineId'] = '';
			$zstore['gridCellSize'] = 'medium';
			$zstore['gridCellSpacing'] = '9';
			$zstore['gridCellBgColor'] = 'FFFFFF';
			$zstore['keyWords'] = '';
			$zstore['showHowMany'] = '20';
			$zstore['startPage'] = '1';
			$zstore['showPagination'] = 'true';
			$zstore['showSorting'] = 'true';
			$zstore['defaultSort'] = 'date_created';
			$zstore['showProductDescription'] = 'TRUE';
			$zstore['useShortDescription'] = 'false';
			$zstore['showProductTitle'] = 'TRUE';
			$zstore['showByLine'] = 'true';
			$zstore['showProductPrice'] = 'true';
			$zstore['useCaching'] = "false";
			$zstore['cacheLifetime'] = "7200";
			$zstore['use_customFeedUrl']="false";
			$zstore['customFeedUrl'] = "";
			$zstore['trackingCode']='';
			$zstore['showZoom']="TRUE";
			$zstore['newWindow']="false";
			$zstore['productType'][0]="All";
			$zstore['productType'][1]='all';
			
	
			

		add_option('zstore_basic_manager_settings', $zstore);
			
     }

    /**
     * Add options page
     */
    public function add_zstore_basic_plugin_page()
    {
        // This page will be under "Settings"
        
		add_options_page( 'zStore Basic Options',
							__('zStore Manager Basic','zstore-manager-text-domain' ),
							'manage_options',
							'z_store_basic_slug',
							array( $this, 'care_zstore_basic_admin_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this,'zstore_basic_admin_styles' ));
    }
	function zstore_basic_admin_styles() {
       /*
        * It will be called only on your plugin admin page, enqueue our stylesheet here
        */
		wp_register_script( 'ZbasicstoreTabbedScript', plugins_url( 'js/tabbed.js', __FILE__ ) );
	
	   wp_register_script( 'ZstoreBasicFormFieldsScript', plugins_url( 'js/form_fields.js', __FILE__ ) );
	   wp_register_style( 'ZStoreBasicManagerStyleSheets', plugins_url('css/style.css', __FILE__) );
		wp_register_script( 'ZstoreValidator', plugins_url( 'js/validate.js', __FILE__ ) );	
		
		 wp_enqueue_script( 'ZbasicstoreTabbedScript', plugins_url( 'js/tabbed.js', __FILE__ ) , array(), '1.0.0', true );
		 wp_enqueue_script( 'ZstoreBasicFormFieldsScript', plugins_url( 'js/form_fields.js', __FILE__ ) , array('jquery'), '1.0.0', true );
		 wp_enqueue_script( 'ZstoreValidator', plugins_url( 'js/validate.js', __FILE__ ) , array('jquery'), '1.0.0', true );
		
		wp_enqueue_style( 'ZStoreBasicManagerStyleSheets' );
		wp_localize_script( 'ZstoreBasicFormFieldsScript', 'ajax_object',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 'zstore_clear_cache') );
   }

    /**
     * Options page callback
     */
    public function care_zstore_basic_admin_page()
    {
        // Set class property
        $this->options = get_option( 'zstore_basic_manager_settings' );

		
?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php _e('zStore Basic Administration','zstore-manager-text-domain' );?> </h2>         
            <form id="zStore_admin" name="zStore_admin" method="post" action="options.php">
			<div class="tabbedPanels">
			<ul class="tabs">
				<li><a href="#panel1" tabindex="1"><?php _e('User and Store Information','zstore-manager-text-domain' );?></a></li>
				<li><a href="#panel2" tabindex="2"><?php _e('Page Layout','zstore-manager-text-domain' );?></a></li>
			</ul>
			<div class="panelContainer">
			
			
			
<?php
				
                // This prints out all hidden setting fields
					settings_fields( 'manage_zazzle_store' ); 
			?> <div id="panel1" class="panel"> 
			<?php
					do_settings_sections( 'zstore-basic-setting-admin' );
				   
            ?>
			<input type="button" id="clear_cache" class="clear_cache" name="clear_cache" value="Clear Cache" />  
		
			
			
		
			</div>
			<div id="panel2" class="panel"> 
			<?php
					do_settings_sections( 'zstore-basic-page-layout-admin' );
				
                
            ?>
		
			</div>
			
			<?php 
			submit_button(); 
			
			?>
			
			
            </form>
			
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input name="cmd" type="hidden" value="_s-xclick"  />
				<input name="hosted_button_id" type="hidden" value="R5J9P464SC4HW" />
				<input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" type="image" />
				<img src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" alt="" width="1" height="1" border="0" />
			</form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function zstore_basic_page_init()
    {        
        register_setting(
            'manage_zazzle_store', // Option group
            'zstore_basic_manager_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'user_info_settings', // ID
           __('<h2>User Information </h2>','zstore-manager-text-domain' ) , // Title
            array( $this, 'print_section_info' ), // Callback
            'zstore-basic-setting-admin' // Page
        );  
		add_settings_section(
            'page_layout_settings', // ID
           __( '<h2>Page Layout </h2>','zstore-manager-text-domain' ) , // Title
            array( $this, 'print_page_layout' ), // Callback
            'zstore-basic-page-layout-admin' // Page
        );  
	add_action( 'wp_ajax_zstore_clear_cache', array($this,'zstore_clear_cache') );
	
   
    }
	function zstore_clear_cache(){
			
			$cache = new cacheMgr_Zstore_Basic;
	
			$cache->clear_cache();
			
			
		
	
			 
		

			 
			 
	}
	
	
	
	
    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['contributorHandle'] ) )
            $new_input['contributorHandle'] = sanitize_text_field( $input['contributorHandle'] );

        if( isset( $input['associateId'] ) )
            $new_input['associateId'] = sanitize_text_field( $input['associateId'] );
			
		if( isset( $input['productLineId'] ) )
            $new_input['productLineId'] = sanitize_text_field( $input['productLineId'] );
		if( isset( $input['trackingCode'] ) )
            $new_input['trackingCode'] = sanitize_text_field( $input['trackingCode'] );
			
		
        $new_input['use_customFeedUrl'] = isset( $input['use_customFeedUrl'] )? 'true':'false';
        $new_input['showZoom'] = isset( $input['showZoom'] )? 'true':'false';
        $new_input['newWindow'] = isset( $input['newWindow'] )? 'true':'false';
		$new_input['showPagination'] = isset( $input['showPagination'] )? 'true':'false';
	    $new_input['showSorting'] = isset( $input['showSorting'] )? 'true':'false';
		
	    $new_input['useCaching'] = isset( $input['useCaching'] )? 'true':'false';
	   
			
	    $new_input['showProductDescription'] = isset( $input['showProductDescription'] )? 'true':'false';
			
       $new_input['useShortDescription'] = isset( $input['useShortDescription'] )? 'true':'false';
	   $new_input['showByLine'] = isset( $input['showByLine'] )? 'true':'false';
       $new_input['showProductPrice'] = isset( $input['showProductPrice'] )? 'true':'false';
       
	   
	   $new_input['showProductTitle'] = isset($input['showProductTitle']) ? 'true':'false';
	  
			
		if(  $_POST['gridCellSize'] )
            $new_input['gridCellSize'] = $_POST['gridCellSize'] ;
			
			
		
		
        $new_input['productType'] = $this->productType[$_POST['productType']] ;
		if(  $_POST['defaultSort'] )
            $new_input['defaultSort'] = $_POST['defaultSort'] ;
			
		if( isset( $input['cacheLifetime'] ) )
            $new_input['cacheLifetime'] = sanitize_text_field( $input['cacheLifetime'] );
			
		if( isset( $input['gridCellSpacing'] ) )
            $new_input['gridCellSpacing'] = sanitize_text_field( $input['gridCellSpacing'] );
		if( isset( $input['gridCellBgColor'] ) )
            $new_input['gridCellBgColor'] = sanitize_text_field( $input['gridCellBgColor'] );
		if( isset( $input['startPage'] ) )
            $new_input['startPage'] = sanitize_text_field( $input['startPage'] );
		if( isset( $input['keyWords'] ) )
            $new_input['keyWords'] = sanitize_text_field( $input['keyWords'] );
		
		
		if( isset( $input['customFeedUrl'] ) )
            $new_input['customFeedUrl'] = sanitize_text_field( $input['customFeedUrl'] );
		
		if( isset( $input['numRecs'] ) )
            $new_input['numRecs'] = sanitize_text_field( $input['numRecs'] );
		
		if( isset( $input['customFeedUrl'] ) )
            $new_input['customFeedUrl'] = sanitize_text_field( $input['customFeedUrl'] );
		if( isset( $input['showHowMany'] ) )
            $new_input['showHowMany'] = sanitize_text_field( $input['showHowMany'] );
		
		
		
		
        return $new_input;
    }

	public function print_page_layout()
    {
	
		add_settings_field(
            'productType', // ID
           __('Product Type:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'productType_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        ); 
		add_settings_field(
            'gridCellSize', // ID
            __('Grid Cell Size:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'gridCellSize_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        );  
		add_settings_field(
            'gridCellSpacing', // ID
            __('Grid Cell Spacing:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'gridCellSpacing_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        ); 
		add_settings_field(
            'showHowMany', // ID
            __('Show How Many:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'showHowMany_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        ); 
		add_settings_field(
            'gridCellBgColor', // ID
            __('Grid Cell Background Color:','zstore-manager-text-domain' ) ,  // Title 
            array( $this, 'gridCellBgColor_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        );  
		add_settings_field(
            'keyWords', // ID
            __('Key Words:', 'zstore-manager-text-domain' ) , // Title 
            array( $this, 'keyWords_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        ); 
		add_settings_field(
            'startPage', // ID
            __('Start Page:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'startPage_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        ); 
		
		add_settings_field(
            'showPagination', // ID
            __('Show Pagination:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'showPagination_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        );  
		add_settings_field(
            'showSorting', // ID
            __('Show Sorting:', 'zstore-manager-text-domain' ) ,// Title 
            array( $this, 'showSorting_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        ); 
		add_settings_field(
            'defaultSort', // ID
            __('Sort By:', 'zstore-manager-text-domain' ) ,// Title 
            array( $this, 'defaultSort_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        );  
		
		
		add_settings_field(
            'showProductTitle', // ID
            __('Show Product Title:','zstore-manager-text-domain' ) ,// Title 
            array( $this, 'showProductTitle_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        ); 
		add_settings_field(
            'showProductDescription', // ID
            __('Show Product Description:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'showProductDescription_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        );  
		add_settings_field(
            'useShortDescription', // ID
            __('Use Short Description:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'useShortDescription_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        );  
		add_settings_field(
            'showByLine', // ID
            __('Show By Line:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'showByLine_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        );  
		add_settings_field(
            'showProductPrice', // ID
            __('Show Product Price:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'showProductPrice_cb' ), // Callback
            'zstore-basic-page-layout-admin', // Page
            'page_layout_settings' // Section           
        );  
	
	}

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
		add_settings_field(
            'contributorHandle', // ID
            __('Contributor Handle:','zstore-manager-text-domain' ) , // Title 
            array( $this, 'contributor_handle_cb' ), // Callback
            'zstore-basic-setting-admin', // Page
            'user_info_settings' // Section           
        );      

        add_settings_field(
            'associateId', 
            __('Associate Id:','zstore-manager-text-domain' ) ,
            array( $this, 'associateId_cb' ), 
            'zstore-basic-setting-admin', 
            'user_info_settings'
        );   
		add_settings_field(
            'productLineId', 
          __('Product Line Id:', 'zstore-manager-text-domain' ) ,
            array( $this, 'productLineId_cb' ), 
            'zstore-basic-setting-admin', 
            'user_info_settings'
        ); 


		add_settings_field(
            'trackingCode', 
          __('Tracking Code:', 'zstore-manager-text-domain' ) ,
            array( $this, 'trackingCode_cb' ), 
            'zstore-basic-setting-admin', 
            'user_info_settings'
        ); 
		
		add_settings_field(
            'newWindow', // ID
            __('Open Product in New Window :','zstore-manager-text-domain' ) ,  // Title 
            array( $this, 'newWindow_cb' ), // Callback
            'zstore-basic-setting-admin', // Page
            'user_info_settings', // Section     
			array( 'label_for' => 'newWindow' )			
        );  

		add_settings_field(
            'showZoom', // ID
            __('Show Zoom:','zstore-manager-text-domain' ) ,  // Title 
            array( $this, 'showZoom_cb' ), // Callback
            'zstore-basic-setting-admin', // Page
            'user_info_settings', // Section     
			array( 'label_for' => 'showZoom' )			
        );  
		
		
		add_settings_field(
            'useCaching', 
             __('Use Cache:', 'zstore-manager-text-domain' ) ,
            array( $this, 'useCaching_cb' ), 
            'zstore-basic-setting-admin', 
            'user_info_settings'
        );  
		add_settings_field(
            'cacheLifetime', 
            __('Cache Lifetime:', 'zstore-manager-text-domain' ) , 
            array( $this, 'cacheLifetime_cb' ), 
            'zstore-basic-setting-admin', 
            'user_info_settings',
			array( 'label_for' => 'cacheLifetime' )
        );   
		add_settings_field(
            'use_customFeedUrl', // ID
            __('Use Custom Feed Url:','zstore-manager-text-domain' ) ,  // Title 
            array( $this, 'use_customFeedUrl_cb' ), // Callback
            'zstore-basic-setting-admin', // Page
            'user_info_settings', // Section     
			array( 'label_for' => 'use_customFeedUrl' )			
        ); 
		
		add_settings_field(
            'customFeedUrl', // ID
            __('Custom Feed Url:','zstore-manager-text-domain' ) ,  // Title 
            array( $this, 'customFeedUrl_cb' ), // Callback
            'zstore-basic-setting-admin', // Page
            'user_info_settings', // Section     
			array( 'label_for' => 'customFeedUrl' )			
        ); 
	/*	add_settings_field(
            'clearCache', // ID
            '', // Title 
            array( $this, 'clearCache_cb' ), // Callback
            'zstore-basic-setting-admin', // Page
            'user_info_settings', // Section     
			array( 'label_for' => 'clearCache' )			
        );  */		
			
		
    }

    /** 
     * Get the settings option array and print one of its values
     */
    
	public function contributor_handle_cb()
    {
        printf(
            '<input type="text" id="contributorHandle" name="zstore_basic_manager_settings[contributorHandle]" value="%s" />',
            isset( $this->options['contributorHandle'] ) ? esc_attr( $this->options['contributorHandle']) : ''
		
			
        );
    }
	public function gridCellSpacing_cb()
    {
        printf(
            '<input type="text"  size="4" id="gridCellSpacing"  class="digits"  name="zstore_basic_manager_settings[gridCellSpacing]" value="%s" />',
            isset( $this->options['gridCellSpacing'] ) ? esc_attr( $this->options['gridCellSpacing']) : ''
		
			
        );
    }
	public function showHowMany_cb()
    {
        printf(
            '<input type="text"  size="4" id="showHowMany" class="digits required" name="zstore_basic_manager_settings[showHowMany]" value="%s" />',
            isset( $this->options['showHowMany'] ) ? esc_attr( $this->options['showHowMany']) : ''
		
			
        );
    }
	public function gridCellSize_cb()
	{
	?>
		<select name="gridCellSize" id="gridCellSize" class="required" title="Grid Cell Size">
					<option value="tiny" <?php echo  ($this->options['gridCellSize'] == 'tiny'?'selected': ""); ?>><?php _e('Tiny','zstore-manager-text-domain' );?></option>
					<option value="small" <?php echo  ($this->options['gridCellSize'] == 'small'?'selected': ""); ?>><?php _e('Small','zstore-manager-text-domain' );?></option>
					<option value="medium" <?php echo  ($this->options['gridCellSize'] == 'medium'?'selected': ""); ?>><?php _e('Medium','zstore-manager-text-domain' );?></option>
					<option value="large" <?php echo  ($this->options['gridCellSize'] == 'large'?'selected': ""); ?>><?php _e('Large','zstore-manager-text-domain' );?></option>
					<option value="huge" <?php echo  ($this->options['gridCellSize'] == 'huge'?'selected': ""); ?>><?php _e('Huge','zstore-manager-text-domain' );?></option>
					</select>
					
	<?php
	}
	public function productType_cb()
	{
	
	?>
		<select name="productType" id="productType" class="required" title="Product Type">
				
					
					<?php 
						//foreach($this->productType as $product)
						foreach($this->productType as $key => $product)
						
					{?>
						<option value=<?php echo  $key; ?> <?php echo  $this->options['productType'][0]== $product[0]?'selected': ""; ?>><?php echo  $product[0]; ?></option>
						
						<?php
						}
					?>
					</select>
					
	<?php
	}
	public function defaultSort_cb()
	{
	?>
		<select name="defaultSort" id="defaultSort" class="required" title="Default Sorting">
					<option value="date_created" <?php echo ( $this->options['defaultSort'] == 'date_created'?'selected': ""); ?>>Date Created</option>
					<option value="popularity" <?php echo  ($this->options['defaultSort'] == 'popularity'?'selected': ""); ?>>Popularity</option>
					
					</select>
					
<?php
	}
	public function cacheLifetime_cb()
    {
        printf(
            '<input type="text" id="cacheLifetime" class="required" size="8" name="zstore_basic_manager_settings[cacheLifetime]" value="%s" />',
            isset( $this->options['cacheLifetime'] ) ? esc_attr( $this->options['cacheLifetime']) : ''
		
			
        );
    }
	public function gridCellBgColor_cb()
    {
	
        printf(
            '<input type="text" id="gridCellBgColor" size="6"  maxlength="6" name="zstore_basic_manager_settings[gridCellBgColor]" value="%s" />',
            isset( $this->options['gridCellBgColor'] ) ? esc_attr( $this->options['gridCellBgColor']) : ''
		
			
        );
    }
	public function keyWords_cb()
    {
        printf(
            '<input type="text" id="keyWords" size="35"  name="zstore_basic_manager_settings[keyWords]" value="%s" />',
            isset( $this->options['keyWords'] ) ? esc_attr( $this->options['keyWords']) : ''
		
			
        );
    }
	public function startPage_cb()
    {
        printf(
            '<input type="text" id="startPage" size="4" class="digits"  name="zstore_basic_manager_settings[startPage]" value="%s" />',
            isset( $this->options['startPage'] ) ? esc_attr( $this->options['startPage']) : ''
		
			
        );
    }
	
	
	
	
	public function customFeedUrl_cb()
    {
        printf(
            '<input type="text" id="customFeedUrl" class="url required" size="50" name="zstore_basic_manager_settings[customFeedUrl]" value="%s" />',
            isset( $this->options['customFeedUrl'] ) ? esc_attr( $this->options['customFeedUrl']) : ''
		
			
        );
    }
	
	
	public function metaKeywords_cb()
    {
        printf(
            '<input type="text" id="metaKeywords" size="50" name="zstore_basic_manager_settings[metaKeywords]" value="%s" />',
            isset( $this->options['metaKeywords'] ) ? esc_attr( $this->options['metaKeywords']) : ''
		
			
        );
    }
	
	
	public function use_customFeedUrl_cb()
    {
	
	
		?>
		<input type="checkbox" id="use_customFeedUrl"  name="zstore_basic_manager_settings[use_customFeedUrl]" <?php echo  ($this->options['use_customFeedUrl'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	
	public function showZoom_cb()
    {
	
	
		?>
		<input type="checkbox" id="showZoom"  name="zstore_basic_manager_settings[showZoom]" <?php echo  ($this->options['showZoom'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	public function newWindow_cb()
    {
	
	
		?>
		<input type="checkbox" id="newWindow"  name="zstore_basic_manager_settings[newWindow]" <?php echo  ($this->options['newWindow'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	
	public function showProductDescription_cb()
    {
	
	
		?>
		<input type="checkbox" id="showProductDescription" name="zstore_basic_manager_settings[showProductDescription]" <?php echo  ($this->options['showProductDescription'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	public function showProductTitle_cb()
    {
	
	
		?>
		<input type="checkbox" id="showProductTitle" name="zstore_basic_manager_settings[showProductTitle]" <?php echo  ($this->options['showProductTitle'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	public function useShortDescription_cb()
    {
	
	
		?>
		<input type="checkbox" id="useShortDescription" name="zstore_basic_manager_settings[useShortDescription]" <?php echo  ($this->options['useShortDescription'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	public function showByLine_cb()
    {
	
	
		?>
		<input type="checkbox" id="showByLine" name="zstore_basic_manager_settings[showByLine]" <?php echo  ($this->options['showByLine'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	public function showProductPrice_cb()
    {
	
	
		?>
		<input type="checkbox" id="showProductPrice" name="zstore_basic_manager_settings[showProductPrice]" <?php echo  ($this->options['showProductPrice'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	public function showPagination_cb()
    {
	
	
		?>
		<input type="checkbox" id="showPagination" name="zstore_basic_manager_settings[showPagination]" <?php echo  ($this->options['showPagination'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	public function showSorting_cb()
    {
	
	
		?>
		<input type="checkbox" id="showSorting" name="zstore_basic_manager_settings[showSorting]" <?php echo  ($this->options['showSorting'] == 'true'?'checked': ""); ?> /> 
       <?php
	}
	public function useCaching_cb()
    {
	
	
	?>
	<input type="checkbox" id="useCaching" name="zstore_basic_manager_settings[useCaching]" <?php echo  ($this->options['useCaching'] == 'true'?'checked': ""); ?> /> 
       <?php
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function associateId_cb()
    {
        printf(
            '<input type="text" id="associateId" name="zstore_basic_manager_settings[associateId]" value="%s" />',
            isset( $this->options['associateId'] ) ? esc_attr( $this->options['associateId']) : ''
        );
    }
	
	public function productLineId_cb()
    {
        printf(
            '<input type="text" id="productLineId" name="zstore_basic_manager_settings[productLineId]" value="%s" />',
            isset( $this->options['productLineId'] ) ? esc_attr( $this->options['productLineId']) : ''
        );
    }
	public function trackingCode_cb()
    {
        printf(
            '<input type="text" id="trackingCode" name="zstore_basic_manager_settings[trackingCode]" value="%s" />',
            isset( $this->options['trackingCode'] ) ? esc_attr( $this->options['trackingCode']) : ''
        );
    }
	
	
}



	
		$zsmb = new _Zazzle_Store_Manager_Basic();

// Register a Hook
// This will call the 'activate' function in 'dconstructingMaster' class
// when the plugin is activated
		
	register_activation_hook( __FILE__, array( '_Zazzle_Store_Manager_Basic', 'install_zstore_basic' ) );
?>