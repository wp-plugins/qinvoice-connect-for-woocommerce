=== Qinvoice Connect for Woocommerce ===
Contributors: q-invoice
Donate link: n/a
Tags: billing, invoicing, woocommerce, packing, packingslip
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 1.2.20
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connects your Woocommerce installation to q-invoice for automatic invoicing.

== Description ==

Easily generate and send professional looking invoices for every Woocommerce order through q-invoice. 
Invoices can be saved as draft for later editing or sent directly to your customers' emailaddress. 
Packingslips can be generated for each invoice individually or for multiple at once. Different layouts can be used for invoices, estimates and packing slips.

A subscription for q-invoice.com is required. 

== Installation ==

Install the plugin by uploading the zipfile in your WP admin interface or via FTP:

1. Upload the folder `qinvoice-connect` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Find the configuration page and fill out your settings and preferences

== Frequently Asked Questions ==

= Can I use multiple stores within one q-invoice account =

Yes, definitely.

= Do you offer support =

Sure we do. Contact us at info@q-invoice.com or by phone: +31 70 220 62 33

More information about the setup can be found here: 

Dutch: http://www.q-invoice.nl/help/webshop/woocommerce-plugin-instellen/ 

- more languages will follow and this document will be updated accordingly -

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.2.20 =
* Fixed compatibility issue with WC 2.1.0+ (new file/folder structure)
* Restored 'Generate invoice' button to orders for WP 3.8+

= 1.2.19 =
* Check if class exists before loading qinvoice class
* Added calculation method selector (leading price incl/excl VAT)

= 1.2.18 =
* Added trailing slash to API url when missing 

= 1.2.17 =
* Check function_exists is_woocommerce_activated 

= 1.2.16 =
* Improved fallback when Woocommerce is not loaded (yet) or not loaded completely

= 1.2.15 =
* Minor changes in VAT calculation

= 1.2.14 =
* Added option to save or update relation details

= 1.2.13 =
* Added Discount VAT setting for selecting VAT percentage to be used with (cart-)discounts

== Upgrade Notice ==

No upgrade notices apply.

== Arbitrary section ==

