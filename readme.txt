=== zStore Manager Basic ===
Contributors: Ilene Johnson
Donate link: http://ikjweb.com/
Tags: zazzle, zstore, t-shirt product, POD
Author: Ilene Johnson 
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables Zazzle shopkeepers and people who have affiliate websites to display Zazzle store products on their own website using Zazzle's RSS feed.

== Description ==

zStore Manager Basic gives a Zazzle shopkeeper or Zazzle Affiliate site the ability to easily display Zazzle products on another domain. 
zStore Manager Basic can be configured using a setup page.  The user can configure the shop name, associate's id, custom url, and the product layout.  Users can
also use the cache so that images load faster. The user can also use a shortcode on a page which will override the settings in the Settings page, except
for the cache and the custom url.   

== Installation ==

1.  Download the plugin to the plugins directory. 
2.  Activate the plugin 
3.  Fill in the information in the settings tab. 
4.  Add the shortcode, [zStoreBasic]  to a page.  If you simply want to use the configuration from the Settings page, no further action is necessary. 


== Frequently Asked Questions ==

= How do I add new products to the products list.  =

open the producttype.csv file that is located in the root directory of the plugin.  Add 1 product per line in the following format: 
	productname, zazzle_product_code. 

For instance, to add t-shirts this would be 
	T-Shirts,zazzle_shirt.  


== Screenshots ==

1. User and Store Information 
2. Individual Page Information 
3. View of Products

== Changelog ==

= 1.2 =
*  Added Plugin Icon 
*  Changed compatability to 4.0


= 1.1 =

* Added tracking code feature.   Puts tc=somecode in the link 
* Fixed bug - show sorting and show paging were still appearing at the bottom even though that selection was unchecked in settings
* Fixed problem with images appearing in a column in some templates.  

= 1.0 =

* First public release.

== Upgrade Notice ==

= 1.1 =

Added tracking code feature. Fixed formatting issues that appeared on some templates where products would appear in one column

= 1.0 =

* First Public Release


== To use via Shortcode ==

For shortcode documentation  [go to the website](http://ikjweb.com) .


== Adding product types==

Zazzle adds new products often and you will want them to show up in zStore Manager Basic.  To add a product to zStore Manager Basic, 
open the producttype.csv file that is located in the root directory of the plugin.  Add 1 product per line in the following format: 
	productname, zazzle_product_code. 

For instance, to add t-shirts this would be 
	T-Shirts,zazzle_shirt.  



