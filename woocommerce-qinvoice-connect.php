<?php
/**
 * Plugin Name: WooCommerce Q-invoice Connect
 * Plugin URI: www.q-invoice.com
 * Description: Print order invoices directly through q-invoice
 * Version: 1.2.29
 * Author: q-invoice.com
 * License: GPLv3 or later
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: woocommerce-qinvoice-connect
 * Domain Path: /languages/
 */

/**
 * Base class
 */


if ( !class_exists( 'WooCommerce_Qinvoice_Connect' ) ) {

	class WooCommerce_Qinvoice_Connect {
	
		public static $plugin_prefix;
		public static $plugin_url;
		public static $plugin_path;
		public static $plugin_basefile;
		
		public $writepanel;
		public $settings;
		public $print;

		/**
		 * Constructor
		 */
		public function __construct() {
			self::$plugin_prefix = 'wcqc_';
			self::$plugin_basefile = plugin_basename(__FILE__);
			self::$plugin_url = plugin_dir_url(self::$plugin_basefile);
			self::$plugin_path = trailingslashit(dirname(__FILE__));
		}
		
		/**
		 * Load the hooks
		 */
		public function load() {
			// load the hooks
			//add_action( 'plugins_loaded', array($this, 'load_localisation') );
			$this->load_hooks();
			//add_action( 'init', array( $this, 'load_hooks' ) );
			add_action( 'admin_init', array( $this, 'load_admin_hooks' ) );

		}
	
		/**
		 * Load the main plugin classes and functions
		 */
		public function includes() {
			//include_once( 'classes/class-wcqc-writepanel.php' );
			include_once( 'classes/class-wcqc-settings.php' );
			include_once( 'classes/class-wcqc-api.php' );
			//include_once( 'classes/class-wcqc-print.php' );
		}

		/**
		 * Load the localisation 
		 */
		public function load_localisation() {	
			return true;
			load_plugin_textdomain( 'woocommerce-qinvoice-connect', false, dirname( self::$plugin_basefile ) . '/../../languages/woocommerce-qinvoice-connect/' );
			load_plugin_textdomain( 'woocommerce-qinvoice-connect', false, dirname( self::$plugin_basefile ) . '/languages' );
		}

		/**
		 * Load the init hooks
		 */
		public function load_hooks() {	
			//if ( $this->is_woocommerce_activated() ) {
				$this->includes();
				$this->settings = new WooCommerce_Qinvoice_Connect_Settings();
				$this->settings->load();
				$this->api = new WooCommerce_Qinvoice_Connect_API();
				$this->api->load();
			//}
		}
		
		/**
		 * Load the admin hooks
		 */
		public function load_admin_hooks() {
			if ( $this->is_woocommerce_activated() ) {
				add_filter( 'plugin_action_links_' . self::$plugin_basefile, array( $this, 'add_settings_link') );
			}

			if(is_admin()){
		       // wp_enqueue_script('qinvoiceconnect_admin','/'. dirname( self::$plugin_basefile ) .'/js/qinvoiceconnect_admin.js', array('jquery'));
		        wp_enqueue_script('qinvoiceconnect_admin', plugins_url('js/qinvoiceconnect_admin.js', __FILE__ ), array('jquery'));
		    	
		    }  
		}
		
		/**
		 * Add settings link to plugin page
		 */
		public function add_settings_link( $links ) {
			$settings = sprintf( '<a href="%s" title="%s">%s</a>' , admin_url( 'admin.php?page=woocommerce&tab=' . $this->settings->tab_name ) , __( 'Go to the settings page', 'woocommerce-qinvoice-connect' ) , __( 'Settings', 'woocommerce-qinvoice-connect' ) );
			array_unshift( $links, $settings );
			return $links;	
		}
		
		/**
		 * Check if woocommerce is activated
		 */
		public function is_woocommerce_activated() {
			$blog_plugins = get_option( 'active_plugins', array() );
			$site_plugins = get_site_option( 'active_sitewide_plugins', array() );

			if ( in_array( 'woocommerce/woocommerce.php', $blog_plugins ) || isset( $site_plugins['woocommerce/woocommerce.php'] ) ) {
				return true;
			} else {
				return false;
			}
		}
	}
}

function qinvoice_woocommerce_payment_completed( $order_id ) {
    global $wcqc;
    if(get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_trigger' ) == 'completed'){
    	if(!is_object($wcqc)){
    		$wcqc = new WooCommerce_Qinvoice_Connect();
			$wcqc->load();
    	}
    	$wcqc->api->generate_invoice($order_id, false);
    }
}

function qinvoice_woocommerce_payment_complete( $order_id ) {
    global $wcqc;
    if(get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_trigger' ) == 'payment'){
    	$payment_method = get_post_meta($order_id,'_payment_method', true);
    	if($payment_method == get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'exclude_payment_method' )){
    		return true;
    	}
    	if(!is_object($wcqc)){
    		$wcqc = new WooCommerce_Qinvoice_Connect();
			$wcqc->load();
    	}
    	$wcqc->api->generate_invoice($order_id, false);
    	
    }
}
function qinvoice_woocommerce_checkout_order_processed( $order_id ) {
    global $wcqc;
    if(get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_trigger' ) == 'order'){
    	if(!is_object($wcqc)){
    		$wcqc = new WooCommerce_Qinvoice_Connect();
			$wcqc->load();
    	}
    	$wcqc->api->generate_invoice($order_id, false);
    }
}
if ( !function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		$blog_plugins = get_option( 'active_plugins', array() );
		$site_plugins = get_site_option( 'active_sitewide_plugins', array() );

		if ( in_array( 'woocommerce/woocommerce.php', $blog_plugins ) || isset( $site_plugins['woocommerce/woocommerce.php'] ) ) {
			return true;
		} else {
			return false;
		}
	}
}



if ( !is_woocommerce_activated() ) { 
	require_once(ABSPATH .'wp-content/plugins/woocommerce/woocommerce.php');
	$woocommerce = new Woocommerce();
}

add_action( 'woocommerce_order_status_completed',
        'qinvoice_woocommerce_payment_completed' );

add_action( 'woocommerce_payment_complete',
        'qinvoice_woocommerce_payment_complete' );

add_action( 'woocommerce_checkout_order_processed', 
	'qinvoice_woocommerce_checkout_order_processed' );

//add_action( 'woocommerce_order_status_completed', 
//	'qinvoice_woocommerce_order_status_complete' );


//woocommerce_after_checkout_validation
/**
 * Instance of plugin
 */
$wcqc = new WooCommerce_Qinvoice_Connect();
$wcqc->load();


?>