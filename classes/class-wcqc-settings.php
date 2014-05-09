<?php

/**
 * Settings class
 */
if ( ! class_exists( 'WooCommerce_Qinvoice_Connect_Settings' ) ) {

	class WooCommerce_Qinvoice_Connect_Settings {
	
		public $tab_name;
		public $hidden_submit;
		
		/**
		 * Constructor
		 */
		public function __construct() {			
			$this->tab_name = 'woocommerce-qinvoice-connect';
			$this->hidden_submit = WooCommerce_Qinvoice_Connect::$plugin_prefix . 'submit';
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
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ) );
			add_action( 'woocommerce_settings_tabs_' . $this->tab_name, array( $this, 'create_settings_page' ) );
			add_action( 'woocommerce_update_options_' . $this->tab_name, array( $this, 'save_settings_page' ) );
		}
		
		
		/**
		 * Check if we are on settings page
		 */
		public function is_settings_page() {
			if( isset( $_GET['page'] ) && isset( $_GET['tab'] ) && $_GET['tab'] == $this->tab_name ) {
				return true;
			} else {
				return false;
			}
		}
		
		
		/**
		 * Add a tab to the settings page
		 */
		public function add_settings_tab($tabs) {
			$tabs[$this->tab_name] = __( 'Q-invoice', 'woocommerce-qinvoice-connect' );
			
			return $tabs;
		}
		
		
		/**
		 * Create the settings page content
		 */
		public function create_settings_page() {
			?>
			<h2><?php _e( 'Q-invoice.com API settings', 'woocommerce-qinvoice-connect' ); ?></h2>
			<h3><?php _e('API userdata'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>api_url"><?php _e( 'API Url', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>api_url" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'api_url' ) ); ?>"/>
							<span class="description">
								<?php echo sprintf(__( 'eg. %s', 'woocommerce-qinvoice-connect' ),'https://app.q-invoice.com/api/xml/1.1/'); ?>
							</span>
						</td>
					</tr>
					
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>api_username"><?php _e( 'API Username', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>api_username" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'api_username' ) ); ?>"/>
							<span class="description">
								<?php _e( 'Your API username', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>api_password"><?php _e( 'API Password', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						<td>
							<input type="password" name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>api_password" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'api_password' ) ); ?>"/>
							<span class="description">
								<?php _e( 'Your API Password.', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>
					</tbody>
			</table>
			<h3><?php _e( 'Invoice settings', 'woocommerce-qinvoice-connect' ); ?></h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>layout_code"><?php _e( 'Layout code', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>layout_code" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'layout_code' ) ); ?>"/>
							<span class="description">
								<?php _e( 'Your q-invoice layout code (leave empty for default layout)', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_remark"><?php _e( 'Invoice remark', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_remark" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_remark' ) ); ?>"/>
							<span class="description">
								<?php _e( 'eg. Your order: {order_id} (you can also use {order_number} or {order_date})', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>paid_remark"><?php _e( 'Paid remark', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>paid_remark" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'paid_remark' ) ); ?>"/>
							<span class="description">
								<?php _e( 'eg. Your payment has been received. Via {method}', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_tag"><?php _e( 'Invoice tag', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_tag" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_tag' ) ); ?>"/>
							<span class="description">
								<?php _e( 'eg. Your webshop name.', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_trigger"><?php _e( 'Send request on', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						<td>
							<?php $selected = wp_kses_stripslashes( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_trigger' ) ); ?>
							<select name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_trigger">
								<option value="order" <?php if($selected == 'order'){ echo 'SELECTED'; } ?>><?php _e('Every order','woocommerce-qinvoice-connect'); ?></option>
								<option value="payment" <?php if($selected == 'payment'){ echo 'SELECTED'; } ?>><?php _e('Every successful payment','woocommerce-qinvoice-connect'); ?></option>
								<option value="completed" <?php if($selected == 'completed'){ echo 'SELECTED'; } ?>><?php _e('When order is marked completed','woocommerce-qinvoice-connect'); ?></option>
							</select>
							<span class="description">
								<?php _e( 'Which event to use', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_action"><?php _e( 'After request?', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						
						<td>
							<?php $selected = ( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_action' ) ); ?>
							<select name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_action">
								<option value="0" <?php if($selected == '0'){ echo 'SELECTED'; } ?>><?php _e('Save as concept','woocommerce-qinvoice-connect'); ?></option>
								<option value="1" <?php if($selected == '1'){ echo 'SELECTED'; } ?>><?php _e('Finalize invoice','woocommerce-qinvoice-connect'); ?></option>
								<option value="2" <?php if($selected == '2'){ echo 'SELECTED'; } ?>><?php _e('Finalize and send to customer','woocommerce-qinvoice-connect'); ?></option>
							</select>
							<span class="description">
								<?php _e( 'What to do after request has been sent', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
			<h3><?php _e( 'Advanced settings', 'woocommerce-qinvoice-connect' ); ?></h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>save_relation"><?php _e( 'Save/update relation?', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						
						<td>
							<?php $selected = ( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'save_relation' ) ); ?>
							<select name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>save_relation">
								<option value="0" <?php if($selected == '0'){ echo 'SELECTED'; } ?>><?php _e('No, don\'t save','woocommerce-qinvoice-connect'); ?></option>
								<option value="1" <?php if($selected == '1'){ echo 'SELECTED'; } ?>><?php _e('Yes, add to relations','woocommerce-qinvoice-connect'); ?></option>
							</select>
							<span class="description">
								<?php _e( 'Save or update customer contact details', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>

					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>coupon_vat"><?php _e( 'Coupon VAT (optional)', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						
						<td>
							<?php $selected = ( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'coupon_vat' ) ); ?>
							<select name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>coupon_vat">
								<option value="0" <?php if($selected == '0'){ echo 'SELECTED'; } ?>><?php _e('0%','woocommerce-qinvoice-connect'); ?></option>
								<option value="6" <?php if($selected == '6'){ echo 'SELECTED'; } ?>><?php _e('6%','woocommerce-qinvoice-connect'); ?></option>
								<option value="21" <?php if($selected == '21'){ echo 'SELECTED'; } ?>><?php _e('21%','woocommerce-qinvoice-connect'); ?></option>
							</select>
							<span class="description">
								<?php _e( 'VAT over coupon discounts', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>

					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>calculation_method"><?php _e( 'Preferred calculation method', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						
						<td>
							<?php $selected = ( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'calculation_method' ) ); ?>
							<select name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>calculation_method">
								<option value="excl" <?php if($selected == 'excl'){ echo 'SELECTED'; } ?>><?php _e('VAT Excluded','woocommerce-qinvoice-connect'); ?></option>
								<option value="incl" <?php if($selected == 'incl'){ echo 'SELECTED'; } ?>><?php _e('VAT Included','woocommerce-qinvoice-connect'); ?></option>
							</select>
							<span class="description">
								<?php _e( 'Which price is leading for calculations?', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>

					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_date"><?php _e( 'Invoice date', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						
						<td>
							<?php $selected = ( get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'invoice_date' ) ); ?>
							<select name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>invoice_date">
								<option value="order" <?php if($selected == 'order'){ echo 'SELECTED'; } ?>><?php _e('Same as order date','woocommerce-qinvoice-connect'); ?></option>
								<option value="invoice" <?php if($selected == 'invoice'){ echo 'SELECTED'; } ?>><?php _e('Use invoice request date','woocommerce-qinvoice-connect'); ?></option>
							</select>
							<span class="description">
								<?php _e( 'Choose the leading date for your invoices', 'woocommerce-qinvoice-connect' ); ?>
							</span>
						</td>
					</tr>

					<tr>
						<th>
							<label for="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>exclude_payment_method"><?php _e( 'Exclude Payment method', 'woocommerce-qinvoice-connect' ); ?></label>
						</th>
						
						<td>
								<?php
									//global $woocommerce;
									//$WC = new WC_Payment_Gateways();
									//$cart = WC()->cart;
									//$gateways = WC()->payment_gateways->get_available_payment_gateways();

									$available_gateways = get_option('woocommerce_gateway_order');
									//print_r($available_gateways);

									if ( $available_gateways ) {
										?>
											<select name="<?php echo WooCommerce_Qinvoice_Connect::$plugin_prefix; ?>exclude_payment_method">
											<option value="-1"><?php _e('Exclude none'); ?></option>
										<?php

										foreach ( $available_gateways as $gateway => $v ) {
											?>

											<option value="<?php echo $gateway; ?>" <?php  if(get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . 'exclude_payment_method' ) == $gateway){ echo 'SELECTED'; } ?>><?php echo $gateway; ?></option>
												
											<?php
										}
										?>
											</select>	
										<?php
									} else {

										echo '<p>' . __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) . '</p>';

									}
								?>	
													
						</td>
					</tr>

					
					
					<tr>
						<th>
						</th>
						<td>
							<?php 
							// show template preview links when an order is available	
							$args = array(
								'post_type' => 'shop_order',
								'posts_per_page' => 1
							);
							$query = new WP_Query( $args );
						
							if($query->have_posts()) : ?>
								<?php
								$results = $query->get_posts();
								$test_id = $results[0]->ID;
								$invoice_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_print_content&template_type=invoice&order_id=' . $test_id ), 'generate_print_content' );
								?>
								
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
			
			
			<input type="hidden" name="<?php echo $this->hidden_submit; ?>" value="submitted">
			<?php
		}
		
		/**
		 * Get the content for an option
		 */
		public function get_setting( $name ) {
			return get_option( WooCommerce_Qinvoice_Connect::$plugin_prefix . $name );
		}
		
		/**
		 * Save all settings
		 */
		public function save_settings_page() {
			if ( isset( $_POST[ $this->hidden_submit ] ) && $_POST[ $this->hidden_submit ] == 'submitted' ) {
				foreach ( $_POST as $key => $value ) {
					if ( $key != $this->hidden_submit && strpos( $key, WooCommerce_Qinvoice_Connect::$plugin_prefix ) !== false ) {
						if ( empty( $value ) ) {
							delete_option( $key );
						} else {
							if ( get_option( $key ) && get_option( $key ) != $value ) {
								update_option( $key, $value );
							}
							else {
								add_option( $key, $value );
							}
						}
					}
				}
			}
		}
	
	}
	
}

?>