=== Qinvoice Connect for Woocommerce ===
Contributors: q-invoice
Donate link: n/a
Tags: billing, invoicing, woocommerce, packing, packingslip
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: 2.0.6
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

Dutch: http://help.q-invoice.nl/woocommerce-facturatie-plugin-instellen/

- more languages will follow and this document will be updated accordingly -

== Screenshots ==



== Changelog ==

= 2.0.6 
* Added support for currency
* Added 'quote' option 
* Fixed issue with product meta

= 2.0.5
* Added support for EU vat number plugin 
* Added bulk actions for invoices
* Fixed order date as invoice date
* Fixed company name

= 2.0.4
* Fixed coupon VAT
* Fixed coupon description

= 2.0.3
* Minor bugfixes

= 2.0.2
* Minor bugfixes

= 2.0.1 =
* Added support for Woocommerce 2.2.2

= 1.2.31 =
* Added support for fees
* Modified calculation method of discount (now relying on WC_Order::get_total_discount())

= 1.2.30 =
* Now using SKU as product code for matching within q-invoice products

= 1.2.29 =
* Added default ledger account support for organizing revenue
* Added product discount support

= 1.2.28 =
* Added payment method support for use in request

= 1.2.27 =
* Minor fixes and tweaks

= 1.2.26 =
* Formatted variation key in product description
* Changed coupon_vat to numeric input (was: select)
* Added delivery date 

= 1.2.25 =
* Added order date to invoice remark field: {order_date}

= 1.2.24 =
* Added option to exclude certain payment methods
* Added alert before generating invoice for already invoiced order

= 1.2.23 =
* Fixed bug @ apply_before_tax
* Added phone field to request

= 1.2.22 =
* Added support for order number (over order id)

= 1.2.21 =
* Added 2nd address line to request for both billing as invoicing
* Only send fixed tag if it contains a value (prevent ghost tags)

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

