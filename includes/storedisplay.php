<?php 

require_once 'lastRSS.php';
require_once 'cacheMgr.php';





class _Zazzle_Store_Display_Basic
{
	private $options = array();
	private $gridNumber;
	private $startPage;
	private $gridPageHist;
	private $sortMethod; 
	private $sortMode;
	private $currentSort;
	private $gridSort;
	private $gridSortHist;
	private $keywordParam;
	private $gridSortHistDate;
	private $gridSortHistPopularity;
	private $showsortingText;
	private $showpaginationText;
	private $customFeedUrl;
	private $cache_dir;

	
	
	private $paginationText ;
	private $paginationBackOnePage ;
	private $paginationBackToFirstPage ;
	private $jumpToPage;
	private $ofResults ;
	private $advanceOnePageOfResults ;
	private $advanceToLastPageOfResults ;
	private $sortBy ;
	private $dateCreated ;
	private $popularity ;
	private $showingXofY ;
	private $of;
	private $viewMoreProductsFrom ;
	private $by;
	private $poweredByZazzle ;
	private $errorStringProductsUnavailable ;
	private $errorStringRSSNotFound;
	private $sortByDateTooltip;
	private $sortByPopularityTooltip;
	private $keywords;





	
	public function __construct()
    {
		
		add_shortcode( 'zStoreBasic', array( $this, 'z_store_display_func' ) );
		add_action( 'wp_enqueue_scripts', array( $this,'basic_store_display_enqueue_styles' ));
		$this->initialize_strings();

	}
	private function initialize_strings()
	{
	
		$this->paginationText = __('Go to page: ', 'zstore-manager-text-domain');
		$this->paginationBackOnePage = __('Back up one page of results', 'zstore-manager-text-domain');
		$this->paginationBackToFirstPage = __('Back to the first page of results', 'zstore-manager-text-domain');
		$this->jumpToPage = __('Jump to page', 'zstore-manager-text-domain');
		$this->ofResults = __('of results', 'zstore-manager-text-domain');
		$this->advanceOnePageOfResults = __('Advance one page of results', 'zstore-manager-text-domain');
		$this->advanceToLastPageOfResults = __('Advance to the last page of results', 'zstore-manager-text-domain');
		$this->sortBy = __('Sort', 'zstore-manager-text-domain');
		$this->dateCreated = __('newest', 'zstore-manager-text-domain');
		$this->popularity = __('popular', 'zstore-manager-text-domain');
		$this->showingXofY = __('Showing', 'zstore-manager-text-domain');
		$this->of = __('of', 'zstore-manager-text-domain');
		$this->viewMoreProductsFrom = __('View more products from ', 'zstore-manager-text-domain');
		$this->by = __('by', 'zstore-manager-text-domain');
		$this->poweredByZazzle = __('Powered by Zazzle', 'zstore-manager-text-domain');
		$this->errorStringProductsUnavailable = __('No matches found.', 'zstore-manager-text-domain');
		$this->errorStringRSSNotFound = __('Error: Feed temporarily unavailable.<br/>Please try again later.', 'zstore-manager-text-domain');
		$this->sortByDateTooltip = __('Sort results by date created', 'zstore-manager-text-domain');
		$this->sortByPopularityTooltip = __('Sort results by popularity', 'zstore-manager-text-domain');
	
	
	
	}
	public static function instance() {
		 new _Zazzle_Store_Display_Basic;
			
	}
	
	function basic_store_display_enqueue_styles()
	{
		wp_register_style( 'ZStoreBasicDisplayStyleSheets', plugins_url('css/pagestyle.css', dirname(__FILE__)) );
		
		wp_enqueue_style( 'ZStoreBasicDisplayStyleSheets');
		
		

		
	}
	private function get_zstore_sort_methods()
	{
			// init sort variable and some 'showing' variables we use later for pagination
			$this->sortMethod = !isset($_GET['st']) ? "st=date_created" : 'st=popularity';
	        $this->currentSort = isset($_GET['currentSort']) ?  $_GET['currentSort'] : "";
			if($this->options['defaultSort'] == 'popularity') {
				$this->sortMethod = 'st=popularity';
				$this->sortMode = 'popularity';
			}
			if($this->options['defaultSort'] == 'date_created') {
				$this->sortMethod = 'st=date_created';
				$this->sortMode = 'date_created';
			}
			if($this->currentSort == 'popularity') {
				$this->sortMethod = 'st=popularity';
					$this->sortMode = 'popularity';
			}
			if($this->currentSort == 'date_created') {
				$this->sortMethod = 'st=date_created';
				$this->sortMode = 'date_created';
			}

	
	
	
	}
	public function get_grid_cell_size()
	{
	
	
			switch( $this->options['gridCellSize']) {
			case 'tiny':
				$gridCellSize = 50;
				break;
			case 'small':
				$gridCellSize = 92;
				break;
			case 'medium':
				$gridCellSize = 152;
				break;
			case 'large':
				$gridCellSize = 210;
				break;
			case 'huge':
				$gridCellSize = 328;
				break;
			default:
				if (is_numeric($gridCellSize)) {
					$gridCellSize = $gridCellSize;
				} else {
					$gridCellSize = 152;
				}
				;
			
		}
		
			return $gridCellSize;
	
	}
	private function get_start_pages(&$pageinationStart,&$paginationEnd,&$paginationBack,&$pageinationFwd,&$showing, &$showingEnd, $totalPages)
	{
	
	
		$showing = (( $this->options['showHowMany'] * $this->startPage) - $this->options['showHowMany'])+1;
		$showingEnd = $this->options['showHowMany'] * $this->startPage;
	
		// Figure out where to start and stop the pagination page listing
        $paginationStart = $this->startPage - 5;
        $paginationEnd = $this->startPage + 5;

        $paginationBack = $this->startPage - 1;
        $paginationFwd = $this->startPage + 1;

        if($paginationStart < 1) $paginationStart = 1;
        if($paginationBack < 1) $paginationBack = 1;

        if($paginationEnd > $totalPages) $paginationEnd = $totalPages;
        if($paginationFwd > $totalPages) $paginationFwd = $totalPages;
		
		
		
	
	}
	private function get_grid_number()
	{
		if (!isset($gridNumber)) {
			$this->gridNumber = 0;
		}
		if($this->gridNumber >= 1) {
			$this->gridNumber++;
		}
		else {
			$this->gridNumber = 1;
}
	
	
	
	}
	private function get_grid_sort()
	{
			$gS = '';
			if (isset($_GET['gridSort']))
				$gS = $_GET['gridSort'];
			$this->gridSort = array();
			if($gS != '') {
				$gS = preg_replace('~[^a-z0-9,_]+~i', '', $gS); // removes XSS characters
				if(!strstr($gS, ',')) {
					$this->gridSort = array( $gS);
				}
				else {
					$this->gridSort = preg_split("/[,]+/", $gS);
				}
			}



			foreach( $this->gridSort as $gridSortVal) {
       
			$gridSortValueParts = preg_split("/[_]+/", $gridSortVal);

			if($gridSortValueParts[0] == $this->gridNumber) {
				switch( $gridSortValueParts[1]) {
                case 'date':
                    $this->sortMethod = 'st=date_created';
                    $this->sortMode = 'date_created';
                    break;
                case 'popularity':
                 default:
                     $this->sortMethod = 'st=popularity';
                    $this->sortMode = 'popularity';
				}
			}
		}
	
	
	}
	


	private function get_grid_pages()
	{
		$gridPages = array();
		$gridPage =  $_GET['gridPage'] = isset($_GET['gridPage']) ?  htmlspecialchars($_GET['gridPage'],ENT_QUOTES) : "";

		if($gridPage != '') {
			$gridPage = preg_replace('~[^0-9,_]+~i', '', $gridPage); // removes XSS characters from gridPage
			if(!strstr( $gridPage,  ',')) {  // only one grid page passed
				$gridPages[] = $gridPage;

			}
			else { // list of multiple grid pages

				
				 $gridPages = preg_split("/[,]+/", $gridPage);
			}

			foreach( $gridPages as $gridPage) {

            // Is this our gridNumber request?
          		$gridNumberParts  = preg_split("/[_]+/", $gridPage);

			
				if($gridNumberParts[0] == $this->gridNumber) {
                // yes - this is our gridPage request - override
                $this->startPage = $gridNumberParts[1];
				}
			}
		}
		$this->gridPageHist = '';
		foreach( $gridPages as $pg) {
				
				$gridNumberParts  = preg_split("/[_]+/", $pg);
				if($gridNumberParts[0] != $this->gridNumber) {
					$this->gridPageHist .= ',' . $pg;
				}
        }
	
	}
	private function format_pagination($rs)
	{
		
		
		
		$totalNum = isset($rs['opensearch:totalResults']) ? $rs['opensearch:totalResults'] : "";
		$totalPages = ceil( $totalNum/$this->options['showHowMany']);
        $this->get_start_pages($paginationStart,$paginationEnd,$paginationBack,$paginationFwd,$showing, $showingEnd,$totalPages);

        if ( $showingEnd > $totalNum) {
			$showingEnd = $totalNum;  // can't show more results than we have
		}
		
			
        
		$sortingText="";
		$pagination="";
		if ($this->options['showSorting'] == 'true' && $this->options['use_customFeedUrl'] === 'false')
		{
		
		
		$this->gridSortHist = '';
		 foreach( $this->gridSort as $sort) {
				
          //  $gridSortNumberParts = split( '_', $sort);
			$gridSortNumberParts = preg_split("/[_]+/", $sort);
            if($gridSortNumberParts[0] == '') continue;
            if($gridSortNumberParts[0] != $this->gridNumber) {
                if($this->gridSortHist != '') $this->gridSortHist .= ',';
                $this->gridSortHist .= $sort;
            }
        }

        $this->gridSortHistDate = $this->gridSortHist . ",{$this->gridNumber}_date";
        $this->gridSortHistPopularity = $this->gridSortHist . ",{$this->gridNumber}_popularity";

        // strip any leading commas
        $this->gridSortHistDate = preg_replace("/^[\,]*/", '', $this->gridSortHistDate);
        $this->gridSortHistPopularity = preg_replace("/^[\,]*/", '', $this->gridSortHistPopularity);
		
		
		 
			if ( $this->sortMode == 'date_created') {
					$this->showsortingText= "<span class=\"sortLinks\">$this->sortBy: <a href=\"?gridPage={$this->gridNumber}_$this->startPage{$this->gridPageHist}&amp;gridSort={$this->gridSortHistDate}$this->keywordParam\" class=\"selectedSort\" title=\"{$this->sortByDateTooltip}\" rel=\"nofollow\"><strong>$this->dateCreated</strong></a> | <a href=\"?st=1&amp;gridPage={$this->gridNumber}_$this->startPage{$this->gridPageHist}&amp;gridSort={$this->gridSortHistPopularity}$this->keywordParam\" title=\"{$this->sortByPopularityTooltip}\" rel=\"nofollow\">$this->popularity</a></span>";
					$sortingText=$this->gridSortHistDate;
			} else {
					$this->showsortingText ="<span class=\"sortLinks\">$this->sortBy: <a href=\"?gridPage={$this->gridNumber}_$this->startPage{$this->gridPageHist}&amp;gridSort={$this->gridSortHistDate}$this->keywordParam\" title=\"{$this->sortByDateTooltip}\" rel=\"nofollow\">$this->dateCreated</a> | <a href=\"?st=1&amp;gridPage={$this->gridNumber}_{$this->startPage}{$this->gridPageHist}&amp;gridSort={$this->gridSortHistPopularity}$this->keywordParam\" class=\"selectedSort\" title=\"{$this->sortByPopularityTooltip}\" rel=\"nofollow\"><strong>$this->popularity</strong></a></span>";
					$sortingText=$this->gridSortHistPopularity;
				}
				$this->get_grid_sort_hist();
		}
		
		

        if($this->startPage > 1) {
            $pagination .= "<small><a class=\"paginationArrows\" title=\"$this->paginationBackToFirstPage\" href=\"?gridPage={$this->gridNumber}_1{$this->gridPageHist}&amp;gridSort={$sortingText}$this->keywordParam\">&lt;&lt;</a></small> "; // back to start

            $pagination .= "<small><a class=\"paginationArrows\" title=\"$this->paginationBackOnePage\" href=\"?gridPage={$this->gridNumber}_$paginationBack{$this->gridPageHist}&amp;&amp;gridSort={$sortingText}$this->keywordParam\">&lt;</a></small> "; // back one page
        }
	          
        for ( $i=$paginationStart; $i<=$paginationEnd; $i++) {
            if($totalPages <= 1) continue;
            if($i == $this->startPage) $pagination .= '<span class="current" ><strong>' . $i . '</strong> </span>';
           // else $pagination .= "<a title=\"$jumpToPage $i $ofResults\" href=\"?gridPage={$this->gridNumber}_{$i}{$this->gridPageHist}&amp;gridSort={$this->gridSortHist}$this->keywordParam\" class=\"default\">".$i."</a> ";
            else $pagination .= "<a title=\"$this->jumpToPage $i $this->ofResults\" href=\"?gridPage={$this->gridNumber}_{$i}{$this->gridPageHist}&amp;gridSort={$this->gridSortHist}$this->keywordParam\" class=\"default\">".$i."</a> ";
           // else $pagination .= "<a title=\"$this->jumpToPage $i $this->ofResults\" href=\"?gridPage={$this->gridNumber}_{$i}{$this->gridPageHist}&amp;gridSort={$sortingText}$this->keywordParam\" class=\"default\">".$i."</a> ";
        }

        if($this->startPage < $totalPages ) {
            $pagination .= "<small><a class=\"paginationArrows\" title=\"$this->advanceOnePageOfResults\" href=\"?gridPage={$this->gridNumber}_" . $paginationFwd . "{$this->gridPageHist}&amp;gridSort={$sortingText}$this->keywordParam\">&gt;</a></small> ";
            $pagination .= "<small><a class=\"paginationArrows\" title=\"$this->advanceToLastPageOfResults\" href=\"?gridPage={$this->gridNumber}_" .  $totalPages  . "{$this->gridPageHist}&amp;gridSort={$sortingText}$this->keywordParam\">&gt;&gt;</a></small> ";
        }
		
		
		
		$this->showpaginationText = "&nbsp;&nbsp;&nbsp;&nbsp;<span>$this->showingXofY  $showing - $showingEnd $this->of ".$totalNum." products.</span>&nbsp;&nbsp;".$pagination;

		
	}
	private function get_keywords()
	{
	
		$this->keywords = strtolower($this->options['keyWords']);
		
		if (strpos($this->keywords,"+")) {
			$this->keywords = str_replace(" ","",$this->keywords);
			$this->keywords = str_replace("+",",and,",trim($this->keywords));
		} else {
			if (strpos($this->keywords,",")) {
				$this->keywords = str_replace(" ","",$this->keywords);
				$this->keywords = str_replace(",",",or,",$this->keywords);
			} else {
				if (strpos($this->keywords," or ")) {
					$this->keywords = str_replace(" or ",",or,",$this->keywords);
				} else {
					if (strpos($this->keywords," and ")) {
						$this->keywords = str_replace(" and ",",and,",$this->keywords);
					} else {
						if (strpos($this->keywords," ")) {
							$this->keywords = str_replace(" ",",or,",$this->keywords);
						}
					}
				}
			}
		}
	}
	private function camel_convertor($defaults, $atts) {
	
		$out = array();
	
		foreach($defaults as $key => $default) {
		
			$lkey = strtolower($key);
			
			if(isset($atts[$lkey])) {
			
			
				$out[$key] = $atts[$lkey];
			
			}
		
		}
	
		return $out;
	
	}
	private function get_grid_sort_hist()
	{
		$this->gridSortHist = '';
        foreach( $this->gridSort as $sort) {
            if($sort == '') continue;
           // $gridSortNumberParts = split( '_', $sort);
			 $gridSortNumberParts = preg_split("/[_]+/", $sort);
                if($this->gridSortHist != '') $this->gridSortHist .= ',';
                $this->gridSortHist .= $sort;
        }

        // strip any leading commas
        $this->gridSortHist = preg_replace("/\,+$/", '', $this->gridSortHist);
	}
	private function writeToCache($externalUrl,$localFilename){
	
		$ch = curl_init( rawurldecode( $externalUrl));
		$cdir = plugin_dir_path( __FILE__ )  . 'c';
		//$fh = fopen($this->cache_dir. '/' . $localFilename, "w");
		$fh = fopen($cdir. '/' . $localFilename, "w");

	
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        $fdata = curl_exec( $ch);
        fwrite( $fh, $fdata);
        curl_close( $ch);
        fclose( $fh);

	
	
	}
	private function get_image_src($imageUrl,$cache)
	{
	
		if($this->options['useCaching'] == 'true') {
			
			$imageUrl = preg_replace( '/amp;/','', $imageUrl);  // un-escape the ampersands
			 $imageSrc = ''; // we'll use this to set the image's initial src url

                    // build our product image url
                    
             $productFile = str_replace("http://rlv.zcache.com/","",$imageUrl);
			 
			
			$str = substr($productFile, 0, strpos($productFile, '?'));
			if (strlen($str) > 0)		
				$productFile = $str;
		
			
			if($cache->is_image_cached($productFile)) {   // yes - override image url with local url
					
              
                 $imageSrc = $this->cache_dir. '/' . $productFile;
			

            }else {  // no - go get the image from the server and cache
                        // get product image - this will fail if your version of php is not curl-enabled
				
						$this->writeToCache($imageUrl,$productFile);
               
                        // override the remote url with the cached versions so we point at the local copies
                       
                        $imageSrc = $this->cache_dir. '/' . $productFile;
					
              }
				
					
		}
				
			else{
				
					// no caching yet;
			$imageSrc = $imageUrl;
		}
	
	
		return $imageSrc;
	}
	
	private function product_description($description,$gridCellSize)
	{
		$desc = "";
		if($this->options['showProductDescription']  == 'true') {
			$shortdescription="";
			$shortdescription = preg_replace( "/<[^>]+>/", '', $shortdescription);
			$description = preg_replace( "/\.\.\./", '... ', $description);
			$description = preg_replace( "/\,/", ', ', $description);
			$descriptionWords = preg_split("/[\s]+/", $description);
				
					
			for( $i = 0; $i <= 10; ++$i) {
				if (isset($descriptionWords[$i])){
					$shortdescription .= $descriptionWords[$i] . ' ';
				}
			}
			if(sizeof( $descriptionWords) > 10) 
				$shortdescription .= '...';
				
					
					
			if ($this->options['useShortDescription'] == 'true') {
				$desc =  "<p style=width:".$gridCellSize ."px >"  . $shortdescription . "</p>";
			} else {
				$desc =  "<p style=width:".$gridCellSize."px >"  . $description . "</p>";
			}
		}
		return $desc;
	}
	private function make_cache_dir_path()
	{
		
		if($this->options['useCaching'] == 'true') {
 
			$this->cache_dir = plugins_url( 'includes/c' , dirname(__FILE__));

			$rss->cache_dir = $this->cache_dir;
			$rss->cache_time = $this->options['cacheLifetime'];
        // create a cache manager object for image caching
			$cache = new cacheMgr_Zstore_Basic;
			
			$cache->set_lifetime( $this->options['cacheLifetime']);
			return $cache;
		}
	
	
	}
	function z_store_display_func($atts, $content="" )
	{
		
	
		$y = '';

		$defaults = get_option( 'zstore_basic_manager_settings' );

		if ($defaults['productType'] == NULL)
		{
			$defaults['productType'][0]='All';
			$defaults['productType'][1]='all';
		}
		
		$args = shortcode_atts($defaults, $atts,'zStoreBasic');
		;

	
		$args_to_merge = self::camel_convertor($defaults, $atts);
	
		$this->options = array_merge($args, $args_to_merge);
		

		$this->get_grid_number();
		if(!$this->options['startPage'])
			$this->startPage = 1;
		else 
			$this->startPage = $this->options['startPage'];
		
		
	
		$this->keywordParam = "";
		if(isset($this->options['productType'])){
	
			if (is_array($this->options['productType']))
				
			 $productType = $this->options['productType'][1];
			else 
			{
				$productType=$this->options['productType'];
			
			}
			
	
			if ($productType != "") {
			
				$this->keywordParam .= "&".$productType;
			}
		
		}

		if (isset($this->options['keyWords']))
		{
			
				$this->keywords = htmlspecialchars($this->options['keyWords'],ENT_QUOTES);
				$this->keywordParam .= "&qs=".urlencode($this->keywords);
			
			
		}
		if (isset($this->options['customFeedUrl'])){
			$this->customFeedUrl = htmlspecialchars($this->options['customFeedUrl'],ENT_QUOTES);
	
		}
	
	
	
	
	
		$rss = new lastRSS;

		$rss->CDATA = 'content';
		$rss->items_limit = 0;
		$cache= $this->make_cache_dir_path();


	
		
		$this->get_zstore_sort_methods();
		$this->get_grid_pages();
		$this->get_grid_sort();
	
		// product line id
		$cg="";
	    $cg = "&cg=".$this->options['productLineId'];
		$gridCellSize = $this->get_grid_cell_size();
		$this->get_keywords();


		$associateID=isset($this->options['associateId'])?$this->options['associateId']:"";
		$gridCellBgColor=isset($this->options['gridCellBgColor'])?$this->options['gridCellBgColor']:"";
		
		$dataURLBase = $this->options['contributorHandle']!="" ? 'http://feed.'. ZAZZLE_BASIC_URL_BASE .'/'.$this->options['contributorHandle'].'/feed' : 'http://feed.'.ZAZZLE_BASIC_URL_BASE.'/feed';
// $feedUrl = $dataURLBase . '?'.$sortMethod.'&at='.$associateId.'&isz='.$gridCellSize.'&bg='.$gridCellBgColor.'&src=zstore&pg='.$startPage . $cg . '&ps='.$showHowMany.'&ft=gb&opensearch=true&qs='.$this->keywords.'&pt='.$productType;

		if ($this->options['use_customFeedUrl'] === 'false')
		{
			$feedUrl = $dataURLBase 
				. '?'.$this->sortMethod.'&at='
				.$associateID.'&isz='
				.$gridCellSize.'&bg='
				.$gridCellBgColor.'&src=zstore&pg='
				.$this->startPage . $cg . '&ps='.$this->options['showHowMany']
				.'&ft=gb&opensearch=true&qs='.$this->keywords.'&pt='.$productType;
				
				
		} else 
			$feedUrl = $this->options['customFeedUrl'];



		 if ( $rs = $rss->get( $feedUrl)) {
			$id = 0;
			if ( $rs['items_count'] > 0) {
				$content="";
				$this->showsortingText = '';
				$this->showpaginationText = '';
	
				if ( ($this->options['showPagination'] || $this->options['showSorting'] ) && $this->options['use_customFeedUrl'] === 'false'){
					$this->format_pagination($rs);
					$content.= "<p>";
					if ($this->options['showSorting'] == 'true')
						$content.= $this->showsortingText;
					if ($this->options['showPagination'] == 'true')
						$content.= $this->showpaginationText;
					$content.= "</p>";
				}
				$content .= '<ul class="products" >';
				
			
				foreach( $rs as $key=>$val)  {
	 				 if ( $key=="items") { 
					
						foreach( $val as $index => $value) {
							$link = $value['link'];
							$link = str_replace( "&amp;ZCMP=gbase", "", $link);
							$title = urldecode( $value['title']);
							$description = isset($value['description']) ? htmlspecialchars_decode($value['description'], ENT_NOQUOTES) : "";
							$imageUrl = $value['g:image_link'];
							$productId = $value['g:id'];
							$price = $value['g:price'];
							$pubDate = $value['pubDate'];
							$artist = $value['artist'];
							$specificProductType = htmlentities(str_replace("\"","",$value['g:product_type']));
					
						
						$nofollow = "rel=\"nofollow\"";  
					
						if (isset($this->options['associateId']))
							$associateIdParam = $this->options['associateId'] != "YOURASSOCIATEIDHERE" ? "?rf=".$this->options['associateId']: "";
						else 
							$associateIdParam = "";
						$galleryUrl = "http://www.". ZAZZLE_BASIC_URL_BASE ."/".$artist.$associateIdParam;
			
						if (isset($this->options['trackingCode'] )){
						
							$galleryUrl .="&tc=". $this->options['trackingCode'];
							$link .="&tc=". $this->options['trackingCode'];
						}
			
						if($this->options['showProductTitle']== 'true') {
							$displaytitle = "<p><a href=\"$link\" style=width:".$gridCellSize ."px $nofollow class=\"z_productTitle\" title=\"$title\" >$title</a></p>";
					
						}
						$desc = $this->product_description($description,$gridCellSize);

						if ( $this->options['showByLine'] == 'true') {
							$byline = "<p style=width:".$gridCellSize ."px >	 by <a rel=\"nofollow\" href=" . $galleryUrl . " title=" . $this->viewMoreProductsFrom . "" .  $artist . "\>" . $artist. "</a></p>";
			

					}

						if($this->options['showProductPrice'] == 'true') {
							$displayprice = "<p style=width:".$gridCellSize ."px >" .  $price . "</p>";
						}
						$imageSrc = $this->get_image_src($imageUrl,$cache);

						//$x = "<li id=itemid_".$id ." style=\"margin-right:" . $this->options['gridCellSpacing'] . "px;\"><a href="		. $link  . ' '   .  $nofollow . ">";
						$x = "<li id=itemid_".$id ." style=\"margin-right:" . $this->options['gridCellSpacing'] . "px; width:".$gridCellSize ."px\"><a href="		. $link  . ' '   .  $nofollow . ">";
						
						$y = "<img src=\"" . $imageSrc . '"  alt=' . $title. ' title="" #' . $gridCellBgColor . ' ; /> ';
					 
						$id++;
			
						
						$content .= $x;
						$content .= $y; 
						$content .="</a>";
						if (isset($displaytitle))
							$content .=   $displaytitle ;
						if (isset($desc))
							$content .= $desc;
						if (isset($byline))
							$content .= $byline;
						if (isset($displayprice))
							$content .= $displayprice;
					
						$content .= '</li>'				   ;
				
					
						
			}
			
		  }

		}	
					
			$content.= "<p>";
				if ( ($this->options['showPagination'] || $this->options['showSorting'] ) && $this->options['use_customFeedUrl'] === 'false'){
					if ($this->options['showSorting'] == 'true')
						$content.= $this->showsortingText;
					if ($this->options['showPagination'] == 'true')
						$content.= $this->showpaginationText;
					$content.= "</p>";
				}
					
		  
			} else {

			// no - rss socket is not responding
				_e( "<br /><div class=\"error\">$this->errorStringProductsUnavailable</div>",'zstore-manager-text-domain' );

			}
	 }else {
		 
		  // fatal error - no cached RSS, no socket
      die ( $errorStringRSSNotFound);
		
		  
		 
		 
		}
		 return "$content";
	
	}

}
function zsmb_init_zazzle_store_display(){
	
		
	
	return _Zazzle_Store_Display_Basic::instance();
	
	
	
	}
add_action( 'plugins_loaded',  'zsmb_init_zazzle_store_display'  );

?>
