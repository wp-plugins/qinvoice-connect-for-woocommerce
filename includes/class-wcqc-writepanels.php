<?php

/**
 * Writepanel class
 */
if ( !class_exists( 'WooCommerce_Qinvoice_Connect_Writepanels' ) ) {

	class WooCommerce_Qinvoice_Connect_Writepanels {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'woocommerce_admin_order_actions_end', array( $this, 'add_listing_actions' ) );
			add_action( 'add_meta_boxes_shop_order', array( $this, 'add_meta_box' ) );
			add_action( 'admin_print_scripts', array( $this, 'add_scripts' ) );
			add_action( 'admin_footer-edit.php', array(&$this, 'bulk_actions') );
			$this->general_settings = get_option('wcqc_general_settings');
		}
		
		/**
		 * Add the scripts
		 */
		public function add_scripts() {
			if( $this->is_order_edit_page() ) {
				wp_enqueue_script( 'wcqc', WooCommerce_Qinvoice_Connect::$plugin_url . 'js/script.js', array( 'jquery' ) );
				wp_localize_script(  
					'wcqc',  
					'wcqc_ajax',  
					array(  
						'ajaxurl' => admin_url( 'admin-ajax.php' ), // URL to WordPress ajax handling page  
						'nonce' => wp_create_nonce('generate_wcqc')  
					)  
				);  
			}
		}	
			
		/**
		 * Is order page
		 */
		public function is_order_edit_page() {
			global $post_type;
			if( $post_type == 'shop_order' ) {
				return true;	
			} else {
				return false;
			}
		}	
			
		/**
		 * Add PDF actions to the orders listing
		 */
		public function add_listing_actions( $order ) {
			?>
			<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wcqc&request_type=invoice&order_ids=' . $order->id ), 'generate_wcqc' ); ?>" class="button tips wcqc" target="_blank" alt="<?php esc_attr_e( 'Invoice', 'wcqc' ); ?>" data-tip="<?php esc_attr_e( 'Invoice', 'wcqc' ); ?>">
				<img src="<?php echo WooCommerce_Qinvoice_Connect::$plugin_url . 'images/invoice.png'; ?>" alt="<?php esc_attr_e( 'Invoice', 'wcqc' ); ?>" width="16">
			</a>
			<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wcqc&request_type=quote&order_ids=' . $order->id ), 'generate_wcqc' ); ?>" class="button tips wcqc" target="_blank" alt="<?php esc_attr_e( 'Quote', 'wcqc' ); ?>" data-tip="<?php esc_attr_e( 'Quote', 'wcqc' ); ?>">
				<img src="<?php echo WooCommerce_Qinvoice_Connect::$plugin_url . 'images/quote.png'; ?>" alt="<?php esc_attr_e( 'Quote', 'wcqc' ); ?>" width="16">
			</a>
			<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wcqc&request_type=order_confirmation&order_ids=' . $order->id ), 'generate_wcqc' ); ?>" class="button tips wcqc" target="_blank" alt="<?php esc_attr_e( 'Order confirmation', 'wcqc' ); ?>" data-tip="<?php esc_attr_e( 'Order confirmation', 'wcqc' ); ?>">
				<img src="<?php echo WooCommerce_Qinvoice_Connect::$plugin_url . 'images/order_confirmation.png'; ?>" alt="<?php esc_attr_e( 'Order confirmation', 'wcqc' ); ?>" width="16">
			</a>
			<?php
		}

		/**
		 * Add the meta box on the view order page
		 */
		public function add_meta_box() {
			add_meta_box( 'wcqc-box', __( 'Send to Q-invoice.com', 'wcqc' ), array( $this, 'create_box_content' ), 'shop_order', 'side', 'default' );
		}

		/**
		 * Create the meta box content on the view order page
		 */
		public function create_box_content() {
			global $post_id;
			
			$html = '<ul class="wcqc-actions" >
						<li style="float:left;"><a href="'. wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wcqc&request_type=invoice&order_ids=' . $post_id ), 'generate_wcqc' ) .'" class="button" target="_blank" alt="'. esc_attr__( 'New invoice', 'wcqc' ) .'">'. __( 'New invoice', 'wcqc' ) .'</a></li>
						<li style="float:left;"><a href="'. wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wcqc&request_type=quote&order_ids=' . $post_id ), 'generate_wcqc' ) .'" class="button" target="_blank" alt="'. esc_attr__( 'New quote', 'wcqc' ) .'">'. __( 'New quote', 'wcqc' ) .'</a></li>
						<li style="float:left;"><a href="'. wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wcqc&request_type=order_confirmation&order_ids=' . $post_id ), 'generate_wcqc' ) .'" class="button" target="_blank" alt="'. esc_attr__( 'New order confirmation', 'wcqc' ) .'">'. __( 'New order confirmation', 'wcqc' ) .'</a></li>
					</ul>
					<br style="clear:both;"/>';
			echo $html;
		}

		/**
		 * Add actions to menu
		 */
		public function bulk_actions() {
			global $post_type;
	
			if ( 'shop_order' == $post_type ) {
				?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('<option>').val('invoice').text('<?php _e( 'Create invoices', 'wcqc' )?>').appendTo("select[name='action']");
						jQuery('<option>').val('invoice').text('<?php _e( 'Create invoices', 'wcqc' )?>').appendTo("select[name='action2']");

						jQuery('<option>').val('quote').text('<?php _e( 'Create quotes', 'wcqc' )?>').appendTo("select[name='action']");
						jQuery('<option>').val('quote').text('<?php _e( 'Create quotes', 'wcqc' )?>').appendTo("select[name='action2']");

						jQuery('<option>').val('order_confirmation').text('<?php _e( 'Create order confimations', 'wcqc' )?>').appendTo("select[name='action']");
						jQuery('<option>').val('order_confirmation').text('<?php _e( 'Create order confimations', 'wcqc' )?>').appendTo("select[name='action2']");
					});
				</script>
				<?php
			}
		}

		
	}
}