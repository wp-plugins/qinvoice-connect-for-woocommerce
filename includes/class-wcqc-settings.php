<?php

/**
 * Settings class
 */
if ( ! class_exists( 'WooCommerce_Qinvoice_Connect_Settings' ) ) {

	class WooCommerce_Qinvoice_Connect_Settings {
	
		public $options_page_hook;
		public $general_settings;
		public $template_settings;
		public $tab_name;

		public function __construct() {
			add_action( 'admin_menu', array( &$this, 'settings' ) ); // Add menu.
			add_action( 'admin_init', array( &$this, 'init_settings' ) ); // Registers settings
			
			// Add links to WordPress plugins page
			add_filter( 'plugin_action_links_'.WooCommerce_Qinvoice_Connect::$plugin_basename, array( &$this, 'wcqc_add_settings_link' ) );
			add_filter( 'plugin_row_meta', array( $this, 'add_support_links' ), 10, 2 );
			
			$this->general_settings = get_option('wcqc_general_settings');
			$this->tab_name = 'woocommerce-qinvoice-connect';
		}
	
		public function settings() {
			$parent_slug = 'woocommerce';
						
			$this->options_page_hook = add_submenu_page(
				$parent_slug,
				__( 'Q-invoice connect', 'wcqc' ),
				__( 'Q-invoice connect', 'wcqc' ),
				'manage_options',
				'wcqc_options_page',
				array( $this, 'settings_page' )
			);
		}
		
		
		/**
		 * Add a tab to the settings page
		 */
		public function add_settings_tab($tabs) {
			$tabs[$this->tab_name] = __( 'Q-invoice', 'wcqc' );
			return $tabs;
		}
		

		/**
		 * Add settings link to plugins page
		 */
		public function wcqc_add_settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=wcqc_options_page">'. __( 'Settings', 'woocommerce' ) . '</a>';
			array_push( $links, $settings_link );
			$signup_link = '<a href="https://app.q-invoice.com/signup.php" target="_blank" title="' . __( 'Create an account', 'wcqc' ) . '">' . __( 'Create an account', 'wcqc' ) . '</a>';
			array_push( $links, $signup_link );
			return $links;
		}
		
		/**
		 * Add various support links to plugin page
		 * after meta (version, authors, site)
		 */
		public function add_support_links( $links, $file ) {
			if ( !current_user_can( 'install_plugins' ) ) {
				return $links;
			}
		
			if ( $file == WooCommerce_Qinvoice_Connect::$plugin_basename ) {
				 $links[] = '<a href="mailto:support@q-invoice.com" target="_blank" title="' . __( 'Get support', 'wcqc' ) . '">' . __( 'Get support', 'wcqc' ) . '</a>';
			}
			return $links;
		}
	
		public function settings_page() {
			

			$active_tab = 'general';
			?>
	
				<div class="wrap">
					<div class="icon32" id="icon-options-general"><br /></div>
					<h2><?php _e( 'WooCommerce Q-invoice connect', 'wcqc' ); ?></h2>
					<form method="post" action="options.php">
						<?php
							settings_fields( 'wcqc_'.$active_tab.'_settings' );
							do_settings_sections( 'wcqc_'.$active_tab.'_settings' );
	
							submit_button();
						?>
	
					</form>
					<?php
					do_action( 'wcqc_after_settings_page', $active_tab ); ?>
				</div>
			<?php
		}

		
		/**
		 * User settings.
		 * 
		 */
		
		public function init_settings() {
			global $woocommerce;
	
			/**************************************/
			/*********** GENERAL SETTINGS *********/
			/**************************************/
	
			$option = 'wcqc_general_settings';
		
			// Create option in wp_options.
			if ( false == get_option( $option ) ) {
				add_option( $option );
			}
			


			// Section.
			add_settings_section(
				'general_settings',
				__( 'General settings', 'wcqc' ),
				array( &$this, 'section_options_callback' ),
				$option
			);

			add_settings_field(
				'api_url',
				__( 'API url', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'api_url',
					'size'			=> '72',
					'description'	=> sprintf(__( 'eg. %s', 'wcqc' ),'https://app.q-invoice.com/api/xml/1.1/')
				)
			);

			add_settings_field(
				'api_username',
				__( 'API username', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'api_username',
					'size'			=> '25',
					'description'	=> __( 'Your API username', 'wcqc' )
				)
			);
	
			add_settings_field(
				'api_password',
				__( 'API password', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'api_password',
					'size'			=> '25',
					'description'	=> __( 'Your API password', 'wcqc' )
				)
			);


			add_settings_field(
				'layout_code',
				__( 'Layout code', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'layout_code',
					'size'			=> '25',
					'description'	=> __( 'Your q-invoice layout code', 'wcqc' )
				)
			);

			add_settings_field(
				'invoice_remark',
				__( 'Invoice remark', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'invoice_remark',
					'size'			=> '40',
					'description'	=> __( 'eg. Your order: {order_id}. Your payment method: {method}. (you can also use {order_number} and {order_date})', 'wcqc' )
				)
			);

			add_settings_field(
				'paid_remark',
				__( 'Paid remark', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'paid_remark',
					'size'			=> '40',
					'description'	=> __( 'eg. Your payment has been received. Via {method}', 'wcqc' )
				)
			);

			add_settings_field(
				'invoice_tag',
				__( 'Invoice tag', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'invoice_tag',
					'size'			=> '25',
					'description'	=> __( 'eg. Your webshop name (also accepted: {order_number}, {method} and {order_date}', 'wcqc' )
				)
			);

			add_settings_field(
				'invoice_trigger',
				__( 'Send request on:', 'wcqc' ),
				array( &$this, 'radio_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'invoice_trigger',
					'options' 		=> array(
						'order'			=> __('Every new order','wcqc'),
						'payment'		=> __( 'After each succesfull payment' , 'wcqc' ),
						'completed'		=> __( 'When order is marked completed' , 'wcqc' ),
						'none'			=> __( 'Disable automatic invoicing' , 'wcqc' )
					),
					'description'	=> __('When to send invoice/quote request to q-invoice?'),
				)
			);

			add_settings_field(
				'invoice_action',
				__( 'After request:', 'wcqc' ),
				array( &$this, 'radio_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'invoice_action',
					'options' 		=> array(
						'0'			=> __('Save document as draft','wcqc'),
						'1'		=> __( 'Finalize and save PDF' , 'wcqc' ),
						'2'		=> __( 'Finalize and send email with PDF attached to customer' , 'wcqc' )
					),
					'description'	=> __('What to do after request has been sent?'),
				)
			);

			add_settings_field(
				'save_relation',
				__( 'Save relation:', 'wcqc' ),
				array( &$this, 'radio_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'save_relation',
					'options' 		=> array(
						'0'			=> __('No','wcqc'),
						'1'		=> __( 'Yes' , 'wcqc' )
					),
					'description'	=> __('Automatically save or update relation details?'),
				)
			);

		
			add_settings_field(
				'coupon_vat',
				__( 'Coupon vat', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'coupon_vat',
					'size'			=> '5',
					'description'	=> __( 'eg. 21 (without %)', 'wcqc' )
				)
			);

			add_settings_field(
				'default_ledger_account',
				__( 'Default ledger account', 'wcqc' ),
				array( &$this, 'text_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'default_ledger_account',
					'size'			=> '10',
					'description'	=> __( 'The default ledger account for your revenue. eg. 8000', 'wcqc' )
				)
			);

			
			
			add_settings_field(
				'calculation_method',
				__( 'Calculation method:', 'wcqc' ),
				array( &$this, 'radio_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'calculation_method',
					'options' 		=> array(
						'incl'		=> __('Prices <strong>including VAT</strong> are leading','wcqc'),
						'excl'		=> __( 'Prices <strong>excluding VAT</strong> are leading' , 'wcqc' )
					),
					'description'	=> __('Set the preferred calculation method'),
				)
			);

			add_settings_field(
				'invoice_date',
				__( 'Invoice date:', 'wcqc' ),
				array( &$this, 'radio_element_callback' ),
				$option,
				'general_settings',
				array(
					'menu'			=> $option,
					'id'			=> 'invoice_date',
					'options' 		=> array(
						'order'		=> __('Use order date for invoice date','wcqc'),
						'invoice'	=> __( 'Use date of sending request' , 'wcqc' )
					),
					'description'	=> __('Which date should be used as invoice date?'),
				)
			);

			
			$available_gateways = get_option('woocommerce_gateway_order');
									//print_r($available_gateways);

			if ( $available_gateways ) {
				
				foreach ( $available_gateways as $gateway => $v ) {
					$options_array[$gateway] = $gateway; 
				}
				add_settings_field(
					'exclude_payment_method',
					__( 'Exclude payment methods:', 'wpo_wcpdf' ),
					array( &$this, 'multiple_checkbox_element_callback' ),
					$option,
					'general_settings',
					array(
						'menu'			=> $option,
						'id'			=> 'exclude_payment_method',
						'options' 		=> $options_array,
						'description'	=> __('Exclude certain payment methods. For selected methods no request will be sent.'),
					)
				);
			} 

			// Register settings.
			register_setting( $option, $option, array( &$this, 'validate_options' ) );
		
		}

		/**
		 * Set default settings.
		 */
		public function default_settings() {
			global $wcqc;

			$default_general = array(
				'api_url'	=> 'https://app.q-invoice.com/api/xml/1.1/',
			);

			update_option( 'wcqc_general_settings', $default_general );
		}
		
		// Text element callback.
		public function text_element_callback( $args ) {
			$menu = $args['menu'];
			$id = $args['id'];
			$size = isset( $args['size'] ) ? $args['size'] : '25';
		
			$options = get_option( $menu );
		
			if ( isset( $options[$id] ) ) {
				$current = $options[$id];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}
		
			$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" size="%4$s"/>', $id, $menu, $current, $size );
		
			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<span class="description">%s</span>', $args['description'] );
			}
		
			echo $html;
		}
		
		// Text element callback.
		public function textarea_element_callback( $args ) {
			$menu = $args['menu'];
			$id = $args['id'];
			$width = $args['width'];
			$height = $args['height'];
		
			$options = get_option( $menu );
		
			if ( isset( $options[$id] ) ) {
				$current = $options[$id];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}
		
			$html = sprintf( '<textarea id="%1$s" name="%2$s[%1$s]" cols="%4$s" rows="%5$s"/>%3$s</textarea>', $id, $menu, $current, $width, $height );
		
			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}
		
			echo $html;
		}
	
	
		/**
		 * Checkbox field callback.
		 *
		 * @param  array $args Field arguments.
		 *
		 * @return string	  Checkbox field.
		 */
		public function checkbox_element_callback( $args ) {
			$menu = $args['menu'];
			$id = $args['id'];
		
			$options = get_option( $menu );
		
			if ( isset( $options[$id] ) ) {
				$current = $options[$id];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}
		
			$html = sprintf( '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1"%3$s />', $id, $menu, checked( 1, $current, false ) );
		
			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}
		
			echo $html;
		}
		
		/**
		 * Multiple Checkbox field callback.
		 *
		 * @param  array $args Field arguments.
		 *
		 * @return string	  Checkbox field.
		 */
		public function multiple_checkbox_element_callback( $args ) {
			$menu = $args['menu'];
			$id = $args['id'];
		
			$options = get_option( $menu );
		
		
			foreach ( $args['options'] as $key => $label ) {
				$current = ( isset( $options[$id][$key] ) ) ? $options[$id][$key] : '';
				printf( '<input type="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="1"%4$s /> %5$s<br/>', $menu, $id, $key, checked( 1, $current, false ), $label );
			}

			// Displays option description.
			if ( isset( $args['description'] ) ) {
				printf( '<p class="description">%s</p>', $args['description'] );
			}
		}

		/**
		 * Select element callback.
		 *
		 * @param  array $args Field arguments.
		 *
		 * @return string	  Select field.
		 */
		public function select_element_callback( $args ) {
			$menu = $args['menu'];
			$id = $args['id'];
		
			$options = get_option( $menu );
		
			if ( isset( $options[$id] ) ) {
				$current = $options[$id];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}
		
			$html = sprintf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $menu );
	
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $label );
			}
	
			$html .= '</select>';
		
			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}
		
			echo $html;
		}
		
		/**
		 * Displays a radio settings field
		 *
		 * @param array   $args settings field args
		 */
		public function radio_element_callback( $args ) {
			$menu = $args['menu'];
			$id = $args['id'];
		
			$options = get_option( $menu );
		
			if ( isset( $options[$id] ) ) {
				$current = $options[$id];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}
	
			$html = '';
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s"%4$s />', $menu, $id, $key, checked( $current, $key, false ) );
				$html .= sprintf( '<label for="%1$s[%2$s][%3$s]"> %4$s</label><br>', $menu, $id, $key, $label);
			}
			
			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}
	
			echo $html;
		}

		
		/**
		 * Section null callback.
		 *
		 * @return void.
		 */
		public function section_options_callback() {
		}
		
		/**
		 * Section null callback.
		 *
		 * @return void.
		 */
		public function custom_fields_section() {
			_e( 'These are used for the (optional) footer columns in the <em>Modern (Premium)</em> template, but can also be used for other elements in your custom template' , 'wcqc' );
		}

		/**
		 * Validate options.
		 *
		 * @param  array $input options to valid.
		 *
		 * @return array		validated options.
		 */
		public function validate_options( $input ) {
			// Create our array for storing the validated options.
			$output = array();
		
			// Loop through each of the incoming options.
			foreach ( $input as $key => $value ) {
		
				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[$key] ) ) {
					// Strip all HTML and PHP tags and properly handle quoted strings.
					if ( is_array( $input[$key] ) ) {
						foreach ( $input[$key] as $sub_key => $sub_value ) {
							$output[$key][$sub_key] = strip_tags( $input[$key][$sub_key] );
						}

					} else {
						$output[$key] = strip_tags( $input[$key] );
					}
				}
			}
		
			// Return the array processing any additional functions filtered by this action.
			return apply_filters( 'wcqc_validate_input', $output, $input );
		}

		
	
	} // end class WooCommerce_Qinvoice_Connect_Settings

} // end class_exists