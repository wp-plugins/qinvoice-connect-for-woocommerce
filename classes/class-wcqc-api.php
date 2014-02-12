<?php

if ( !class_exists( 'qinvoice' ) ) {

	class qinvoice{

		protected $gateway = '';
		private $username;
		private $password;

		public $companyname;
		public $contactname;
		public $email;
		public $address;
		public $city;
		public $country;
		public $delivery_address;
	    public $delivery_zipcode;
	    public $delivery_city;
	    public $delivery_country;
	    public $vatnumber;
	    public $copy;
	    public $remark;
	    public $paid;
	    public $date;
	    public $action;
		public $saverelation = false;
		public $calculation_method;
		
		public $layout;
		
		private $tags = array();
		private $items = array();
		private $files = array();
		private $recurring;

		function __construct($username, $password, $url){
			$this->username = $username;
			$this->password = $password;
			if(substr($url, -1) != '/'){
				$url .= '/';
			}
			$this->gateway = $url;
			$this->recurring = 'none';
		}

		public function addTag($tag){
			$this->tags[] = $tag;
		}

		public function setRecurring($recurring){
			$this->recurring = strtolower($recurring);
		}

		public function addItem($params){
			$item['code'] = $params['code'];
			$item['description'] = $params['description'];
			$item['price'] = $params['price'];
			$item['price_incl'] = $params['price_incl'];
			$item['price_vat'] = $params['price_vat'];
			$item['vatpercentage'] = $params['vatpercentage'];
			$item['discount'] = $params['discount'];
			$item['quantity'] = $params['quantity'];
			$item['categories'] = $params['categories'];
			$this->items[] = $item;
		}
		
		public function addFile($name, $url){
			$this->files[] = array('url' => $url, 'name' => $name);
		}

		public function sendRequest() {
	        $content = "<?xml version='1.0' encoding='UTF-8'?>";
	        $content .= $this->buildXML();

	        $headers = array("Content-type: application/atom+xml");
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $this->gateway);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	        $data = curl_exec($ch);
	        //echo('res:'. $data);
	        if (curl_errno($ch)) {
	            print curl_error($ch);
	        } else {
	            curl_close($ch);
	        }

	        if($data == 1){
	        	return true;
	        }else{
	        	return false;
	        }
	        
	    }

		private function buildXML(){
			$string = '<request>
							<login mode="newInvoice">
								<username><![CDATA['.$this->username.']]></username>
								<password><![CDATA['.$this->password.']]></password>
							</login>
							<invoice>
								<companyname><![CDATA['. $this->companyname .']]></companyname>
								<contactname><![CDATA['. $this->contactname .']]></contactname>
								<email><![CDATA['. $this->email .']]></email>
								<address><![CDATA['. $this->address .']]></address>
								<zipcode><![CDATA['. $this->zipcode .']]></zipcode>
								<city><![CDATA['. $this->city .']]></city>
								<country><![CDATA['. $this->country .']]></country>
								<vat><![CDATA['. $this->vatnumber .']]></vat>
								<recurring><![CDATA['. $this->recurring .']]></recurring>
								<remark><![CDATA['. $this->remark .']]></remark>
								<layout><![CDATA['. $this->layout .']]></layout>
								<copy><![CDATA['. $this->copy .']]></copy>
								<date><![CDATA['. $this->date .']]></date>
								<paid><![CDATA['. $this->paid .']]></paid>
	                            <action><![CDATA['. $this->action .']]></action>
	                            <saverelation><![CDATA['. $this->saverelation .']]></saverelation>
	                            <calculation_method><![CDATA['. $this->calculation_method .']]></calculation_method>
								<tags>';
			foreach($this->tags as $tag){
				$string .= '<tag>'. $tag .'</tag>';
			}
						
			$string .= '</tags>
						<items>';
			foreach($this->items as $i){

			    $string .= '<item>
			    	<code><![CDATA['. $i['code'] .']]></code>
			    	<quantity><![CDATA['. $i['quantity'] .']]></quantity>
			        <description><![CDATA['. $i['description'] .']]></description>
			        <price><![CDATA['. $i['price'] .']]></price>
			        <price_incl><![CDATA['. round($i['price_incl']) .']]></price_incl>
			        <price_vat><![CDATA['. ($i['price_vat']) .']]></price_vat>
			        <vatpercentage><![CDATA['. $i['vatpercentage'] .']]></vatpercentage>
			        <discount><![CDATA['. $i['discount'] .']]></discount>
			        <categories><![CDATA['. $i['categories'] .']]></categories>
			        
			    </item>';
			}
						   
			$string .= '</items>
						<files>';
			foreach($this->files as $f){
				$string .= '<file url="'.$f['url'].'">'.$f['name'].'</file>';
			}
			$string .= '</files>
					</invoice>
				</request>';
			return $string;
		}
	}
}
/* USAGE EXAMPLE; you can copy this code and modify it 

$invoice = new q-invoice('your_username','your_password');

$invoice->companyname = ''; 		// Your customers company name
$invoice->contactname = ''; 		// Your customers contact name
$invoice->email = ''; 				// Your customers emailaddress (invoice will be sent here)
$invoice->address = ''; 				// Self-explanatory
$invoice->zipcode = ''; 				// Self-explanatory
$invoice->city = ''; 					// Self-explanatory
$invoice->country = ''; 				// 2 character country code: NL for Netherlands, DE for Germany etc
$invoice->vat = ''; 					// Self-explanatory
$invoice->remark = 'Thanks for you order!'; 					// Self-explanatory

// OPTIONAL: Only add this to make the invoice recurring
$invoice->setRecurring('yearly');		// daily, weekly, monthly, quarterly or yearly

// OPTIONAL: Add tags
$invoice->addTag('test');
$invoice->addTag('invoice');
$invoice->addTag('via');
$invoice->addTag('XML-api');

// Repeat this block for each product
$params = array(	'description' => '',		// Item description
					'price' => '',				// Item price, multiplied by 100: EUR 10 becomes 1000
					'vatpercentage' => '',		// Item vat percentage, multiplied by 100: 19% becomes 1900 (without '%')
					'discount' => '',			// Discount percentage, also multiplied by 100 without '%'
					'quantity' => ''			// Item quantity, again multiplied by 100 (1.75 becomes 175, 1 becomes 100)
					);

$invoice->addItem($params);
// End repeat

// OPTIONAL: Add a file to this invoice (an orderconfirmation for example) and give any name to it
// In this example we assume the file is in http://www.yourdomain.com/files/
// Change this accordingly! 
// The file should be reachable without limitations like login
$invoice->addFile('orderconfirmation.pdf', 'http://www.yourdomain.com/files/21204598_1004_order.pdf');

$result =  $invoice->sendRequest();
if($result == 1){
	echo 'Invoice generated!';
}else{
	echo 'Uhoh. Something went wrong: '. $result;
}

EXAMPE END */

/**
 * Print class
 */
if ( ! class_exists( 'WooCommerce_Qinvoice_Connect_API' ) ) {

	class WooCommerce_Qinvoice_Connect_API{

		private $order;
		
		public $template_type;
		public $order_id;

		/**
		 * Constructor
		 */
		public function __construct() {					
			global $woocommerce;

			
			if(file_exists(ABSPATH .'wp-content/plugins/woocommerce/includes/class-wc-order.php')){
				require_once(ABSPATH .'wp-content/plugins/woocommerce/includes/class-wc-order.php');
			}

			if(file_exists(ABSPATH .'wp-content/plugins/woocommerce/classes/class-wc-order.php')){
				require_once(ABSPATH .'wp-content/plugins/woocommerce/classes/class-wc-order.php');
			}
		
			$this->order = new WC_Order();
		}
		
		/**
		 * Load the class
		 */
		public function load() {
			add_action( 'admin_init', array( $this, 'load_hooks' ) );
		}

		/**
		 * Load the admin hooks
		 */
		public function load_hooks() {
			add_action( 'woocommerce_admin_order_actions_end', array( $this, 'add_invoice_actions' ) );
			add_action( 'wp_ajax_generate_invoice', array( $this, 'generate_invoice_ajax'));	
		}

		public function get_action( $order_id){
			//mail('casper@newday.sk','hook called',$order_id);
			$this->generate_invoice( $order_id );
		}

		/**
		 * Generate the XML request
		 */
		public function generate_invoice(  $order_id, $output = true ) {

			//mail('casper@newday.sk','woocommerce','generate_invoice called');

			// if(get_post_meta($order_id, '_invoiced', true) == true){
			// 	_e('Invoice already generated','woocommerce-qinvoice-connect');
			// 	return false;
			// }
			//$this->template_type = $template_type;
			$this->order_id = $order_id;
			$this->order->get_order( $this->order_id );

			// echo '<pre>';
			// print_r($this->order);
			// echo '</pre>';
			

			$invoice = new qinvoice(get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'api_username' ) ,get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'api_password' ),get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'api_url' ) );

			$invoice->companyname = $this->order->billing_company; 		// Your customers company name
			$invoice->contactname = $this->order->billing_first_name .' '. $this->order->billing_last_name; 		// Your customers contact name
			$invoice->email = $this->order->billing_email;				// Your customers emailaddress (invoice will be sent here)
			//$invoice->email = '';				// NO EMAIL
			$invoice->address = $this->order->billing_address_1; 				// Self-explanatory
			$invoice->zipcode = $this->order->billing_postcode; 				// Self-explanatory
			$invoice->city = $this->order->billing_city; 					// Self-explanatory
			$invoice->country = $this->order->billing_country; 				// 2 character country code: NL for Netherlands, DE for Germany etc
			
			$invoice->delivery_address = $this->order->shipping_address_1; 				// Self-explanatory
			$invoice->delivery_zipcode = $this->order->shipping_postcode; 				// Self-explanatory
			$invoice->delivery_city = $this->order->shipping_city; 					// Self-explanatory
			$invoice->delivery_country = $this->order->shipping_country; 				// 2 character country code: NL for Netherlands, DE for Germany etc
			

			$invoice->action = (int)get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_action' );
			$invoice->saverelation = (int)get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'save_relation' );
			$invoice->layout = (int)get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'layout_code' );
			
			$invoice->calculation_method = get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'calculation_method' );

			$invoice->vat = ''; 					// Self-explanatory
			$remark = get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_remark' );
			$remark = str_replace('{order_id}', $order_id, $remark);
			$paid_date = get_post_meta($order_id,'_paid_date', true);

			$paid = 0;
			if($paid_date > 0000-00-00){
				$paid = 1;
			}
			if($paid){
				$method = get_post_meta($order_id,'_payment_method_title', true);
				$paidremark = get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'paid_remark' );
				$remark .= ' '. str_replace('{method}', $method, $paidremark);
			}
			$order_date = explode(" ",$this->order->order_date);
			$invoice->paid = $paid;
			$invoice->remark = $remark;
			$invoice->date = $order_date[0];

			$invoice->copy = get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_copy' );

			// OPTIONAL: Add tags
			$invoice->addTag($order_id);
			$invoice->addTag(get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_tag' ));

			$products_total = 0;
			foreach($this->get_order_items() as $item){ // Repeat this block for each product
				
				if($item['quantity'] == 0 || $item['quantity'] == ''){
					// skip 
					continue;
				}
				//echo '<hr/>'. $item['categories'] .'<hr/>';
				if($item['price_excl'] == 0 || $item['price_excl'] == ''){
					$vatp = 0;
				}else{
					$vatp = ($item['line_tax']/$item['quantity']) / $item['price_excl'];
				}
				
				$vatp = $vatp * 10000;
				$vatp = $item['tax_rate']*100;
				if($this->order->order_tax == 0){
					// overwrite tax
					$vatp = 0;
				}
				//echo '<pre>';
				//print_r($item['item']['item_meta']);
				$item_desc = '';
				foreach($item['item']['item_meta'] as $key=>$val){
					if(substr($key,0,1) != '_'){
						$item_desc .= "\n". $key .' : '. $val[0];
					}
				}
				//echo '</pre>';
				//die();
				$price = ($item['line_subtotal']/$item['quantity'])*100;
				$params = array(	'code' => $item['sku'],
									'description' => $item['name'] . $item_desc,		// Item description
									'price' => $price,			// Item price, multiplied by 100: EUR 10 becomes 1000
									'price_incl' => (($item['line_subtotal'] + $item['line_subtotal_tax'])/$item['quantity'])*100,
									'price_vat' => round(($item['line_tax']/$item['quantity'])*100),
									'vatpercentage' => $vatp,		// Item vat percentage, multiplied by 100: 19% becomes 1900 (without '%')
									'discount' => 0,			// Discount percentage, also multiplied by 100 without '%'
									'quantity' => $item['quantity']*100,			// Item quantity, again multiplied by 100 (1.75 becomes 175, 1 becomes 100)
									'categories' => $item['categories']			// Categories
								);

				$invoice->addItem($params);
				$products_total += $price;
			}
			if($this->order->get_shipping() > 0){
				$vatp = $this->order->get_shipping_tax() / $this->order->get_shipping();
				$vatp = round($vatp*100) * 100;
				$params = array(	'code' => '',
									'description' => $this->order->get_shipping_method(),		// Item description
									'price' => $this->order->get_shipping()*100,				// Item price, multiplied by 100: EUR 10 becomes 1000
									'price_incl' => '',
									'price_vat' => '',
									'vatpercentage' => round($vatp),		// Item vat percentage, multiplied by 100: 19% becomes 1900 (without '%')
									'discount' => 0,			// Discount percentage, also multiplied by 100 without '%'
									'quantity' => 100,			// Item quantity, again multiplied by 100 (1.75 becomes 175, 1 becomes 100)
									'categories' => 'shipping'			// Categories
								);

				$invoice->addItem($params);
			}
			// echo '<pre>';
			// print_r($this->order->get_taxes());
			// echo '</pre>';

			//die();
			foreach($this->order->get_used_coupons() as $code){
				 $coupon = new WC_Coupon( $code );
                
                 if($coupon->type == 'percent'){
                 	$amount = ($coupon->coupon_amount/100) * ($products_total/100);
                 }else{
                 	 $amount = $coupon->coupon_amount;
                 }
                 if($coupon->apply_before_tax == 0){
                 	$vatp = 0;
                 }else{
                 	$vatp = get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'coupon_vat' );
                 }
                 $params = array( 	'code' => '',
                 					'description' => $code,
                 					'price' => $amount*-100,
                 					'price_incl' => '',
									'price_vat' => '',
                 					'vatpercentage' => $vatp*100,
                 					'discount' => 0,
                 					'quantity' => 100,
                 					'categories' => 'discount'

                 				);
                 $invoice->addItem($params);
			}


			// if($this->order->get_cart_discount() > 0){
			// 	$vatp = $this->order->get_shipping_tax() / $this->order->get_shipping();
			// 	$vatp = round($vatp*100) * 100;
			// 	$params = array(	'description' => $this->order->get_shipping_method(),		// Item description
			// 						'price' => $this->order->get_shipping()*100,				// Item price, multiplied by 100: EUR 10 becomes 1000
			// 						'vatpercentage' => round($vatp),		// Item vat percentage, multiplied by 100: 19% becomes 1900 (without '%')
			// 						'discount' => 0,			// Discount percentage, also multiplied by 100 without '%'
			// 						'quantity' => 100,			// Item quantity, again multiplied by 100 (1.75 becomes 175, 1 becomes 100)
			// 						'categories' => 'shipping'			// Categories
			// 					);

			// 	$invoice->addItem($params);
			// }
			//print_r($this->order->items());
			//print_r($this->order->get_items( 'tax' ));
			$result =  $invoice->sendRequest();
			if($result == true){
				add_post_meta($order_id, '_invoiced', true, true); 
				if($output == true){
					_e('Invoice generated.','woocommerce-qinvoice-connect');
				}else{
					return true;
				}
			}else{
				if($output == true){
					_e('Uhoh. Something went wrong.','woocommerce-qinvoice-connect');
				}else{
					return false;
				}
			}
			if($output == true){
				echo ' <a href="javascript:window.close();">'. __('close this window.','woocommerce-qinvoice-connect') .'</a>';
			}

		}
		
		
			
		/**
		 * Get the current order
		 */
		public function get_order() {
			return $this->order;
		}

		/**
		 * Get the current order items
		 */
		public function get_order_items() {
			global $woocommerce;
			global $_product;

			$items = $this->order->get_items();
			$tax = $this->order->get_items( 'tax' );
			$data_list = array();
		
			if( sizeof( $items ) > 0 ) {
				//print_r($items);
				//print_r($tax);
				foreach ( $items as $item ) {
					// Array with data for the printing template
					$data = array();

					
					
					// Set the id
					$data['product_id'] = $item['product_id'];
					$data['variation_id'] = $item['variation_id'];
										
					// Set item name
					$data['name'] = $item['name'];
					
					// Set item quantity
					$data['quantity'] = $item['qty'];

					// Set the subtotal for the number of products
					$data['line_total'] = $item['line_total'];
					$data['line_tax'] = $item['line_tax'];
					
					// Set the final subtotal for all products
					$data['line_subtotal'] = $item['line_subtotal'];
					$data['line_subtotal_tax'] = $item['line_subtotal_tax'];
					$data['formatted_line_subtotal'] = $this->order->get_formatted_line_subtotal( $item );
					$data['price'] = $data['formatted_line_subtotal'];
					
					// Set item meta and replace it when it is empty
					$meta = new WC_Order_Item_Meta( $item['item_meta'] );	
					$data['meta'] = $meta->display( false, true );
					$WC_Tax = new WC_Tax();
					

					// Pass complete item array
	                $data['item'] = $item;
					
					// Create the product to display more info
					$data['product'] = null;
					
					$product = $this->order->get_product_from_item( $item );
					
					// Checking fo existance, thanks to MDesigner0 
					if(!empty($product)) {	
						//print_r($product);
						// Set the single price
						$data['single_price'] = $product->get_price();
										
						// Set item SKU
						$data['sku'] = $product->get_sku();
		
						// Set item weight
						$data['weight'] = $product->get_weight();
						
						
						// Set item dimensions
						$data['dimensions'] = $product->get_dimensions();
					
						// Pass complete product object
						$data['product'] = $product;

						$data['categories'] = '';

						$data['price_excl'] = $product->get_price_excluding_tax(1);

						//$data['tax_class'] = $product->get_tax_status();

						$data['categories'] = get_post_meta($product->id, 'categories', true );
						//echo '<hr />'. $product->get_tax_class();
						$rates = ($WC_Tax->get_rates($product->get_tax_class()));
						//print_r($rates);
						foreach($rates as $r){
							$data['tax_rate'] = $r['rate'];
						}
						
						//print_r($data);
					
					}

					$data_list[] = apply_filters( 'wcdn_order_item_data', $data );
				}
			}

			return apply_filters( 'wcdn_order_items_data', $data_list );
		}
		
		/**
		 * Get order custom field
		 */
		public function get_order_field( $field ) {
			if( isset( $this->get_order()->order_custom_fields[$field] ) ) {
				return $this->get_order()->order_custom_fields[$field][0];
			} 
			return;
		}

		/**
		 * Load and generate the template output with ajax
		 */
		public function generate_invoice_ajax() {		
			// Let the backend only access the page
			if( !is_admin() ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			// Check the user privileges
			if( !current_user_can( 'manage_woocommerce_orders' ) && !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			// Check the nonce
			if( empty( $_GET['action'] ) || !check_admin_referer( $_GET['action'] ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			// Check if all parameters are set
			if(  empty( $_GET['order_id'] ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			// Generate the output
			$this->generate_invoice( $_GET['order_id'] );
			
			exit;
		}
	
		/**
		 * Add action to the orders listing
		 */
		public function add_invoice_actions( $order ) {
			$btnTxt = __('Invoice', 'woocommerce-qinvoice-connect');

			if(get_post_meta($order->id, '_invoiced', true) == true){
				$btnTxt = __('Invoice', 'woocommerce-qinvoice-connect');
			}
			$btnIcon = '<img src="'. ABSPATH .'/woocommerce/assets/images/icons/print.png">';
			$html = '<a href="'. wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_invoice&order_id=' . $order->id ), 'generate_invoice' ) .'" class="button tips" target="_blank" alt="'. $btnTxt .'">';
			$html .= $btnTxt;
			$html .= '</a>';
			//$html .= '<img src="'. admin_url( 'images/wpspin_light.gif' ) .'" class="loading" alt="">';
			echo $html;
			
		}
	}
}

?>