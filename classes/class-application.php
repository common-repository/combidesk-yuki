<?php

namespace Combidesk\Yuki;

class Application {
	
	private $title = '';
	private $menu_title = '';
	private $description = '';
	private $plugin_name = '';
	private $plugin_product_name = '';
	
	
	public function __construct() {
		$this->plugin_name         = 'combidesk-yuki';
		$this->plugin_product_name = __( 'Yuki', $this->plugin_name );
		$this->title               = sprintf( __( 'Combidesk - %s for WooCommerce', $this->plugin_name ), $this->plugin_product_name );
		$this->menu_title          = sprintf( __( 'Combidesk - %s', $this->plugin_name ), $this->plugin_product_name );
		
		//Dummy literals can't have sprintf!
		$this->description = __( 'Synchronize your WooCommerce orders automatically to Yuki', $this->plugin_name );
		$this->run();
	}
	
	public function run() {
		add_action( 'admin_menu', [ $this, 'add_combidesk_page' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_combidesk_plugin_style' ] );
		add_action( 'init', [ $this, 'load_textdomain' ] );
		add_action( 'before_woocommerce_init', [ $this, 'woo_hpos_compatibility' ] );
	}
	
	public function woo_hpos_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'combidesk-yuki/combidesk-yuki.php', true );
		}
	}
	
	function load_textdomain() {
		load_plugin_textdomain( $this->plugin_name, false, dirname( plugin_basename( __FILE__ ) ) . '/../languages' );
	}
	
	function add_combidesk_page() {
		$page_title = $this->title;
		$menu_title = $this->menu_title;
		$capability = 'manage_options';
		$menu_slug  = 'combidesk-yuki-settings';
		$function   = [ $this, 'combidesk_settings' ];
		$icon_url   = 'data:image/svg+xml;base64,' . base64_encode( '<svg width="10" height="10" viewBox="0 0 750 750" xmlns="http://www.w3.org/2000/svg">
		  <g stroke="null" id="XMLID_1_" fill="black" transform="translate(100,100)">
		   <path stroke="null" d="m332.53026,360.99324l0,236.96416l149.56391,0c63.83373,0 115.42029,-51.77212 115.42029,-115.42029l0,-149.56391l-236.77859,0c-15.58731,0 -28.2056,12.61829 -28.2056,28.02004z" class="st1" id="XMLID_570_"/>
		   <path stroke="null" d="m1.30002,597.9574l264.98419,0l0,-236.96416c0,-15.58731 -12.61829,-28.2056 -28.2056,-28.2056l-236.77859,0l0,265.16976z" class="st2" id="XMLID_567_"/>
		   <path stroke="null" d="m1.30002,117.16325l0,149.56391l236.96416,0c15.58731,0 28.2056,-12.61829 28.2056,-28.2056l0,-236.77859l-149.56391,0c-63.83373,0 -115.60585,51.77212 -115.60585,115.42029z" class="st3" id="XMLID_564_"/>
		   <path stroke="null" d="m332.53026,1.74296l0,236.96416c0,15.58731 12.61829,28.2056 28.2056,28.2056l236.96416,0l0,-265.16976l-265.16976,0z" class="st4" id="XMLID_561_"/>
		  </g>
		  </svg>' );
		$position   = 4;
		
		add_menu_page( $page_title,
			$menu_title,
			$capability,
			$menu_slug,
			$function,
			$icon_url,
			$position );
	}
	
	// Create Dashboard plugin page
	function combidesk_settings() {
		$current_user = wp_get_current_user();
		
		$email_address = (string) $current_user->user_email;
		
		echo '
		  <h1>' . esc_html( $this->title ) . '</h1>
			' . sprintf( __( 'With this integration you never have to transfer order data from WooCommerce to %s again. As a result, your data is always up-to-date, error-free and you have time to do what you do best!', $this->plugin_name ), $this->plugin_product_name ) . '
			<br><br>
			<h2>' . __( 'Key features', $this->plugin_name ) . '</h2>
			<ul>
				<li>' . __( 'This integration syncs every 60 minutes.', $this->plugin_name ) . '</li>
				<li>' . sprintf( __( 'WooCommerce orders are automatically synced to %s.', $this->plugin_name ), $this->plugin_product_name ) . '</li>
				<li>' . sprintf( __( 'Customers in WooCommerce are created as a debtor in %s based on the email address.', $this->plugin_name ), $this->plugin_product_name ) . '</li>
				<li>' . __( 'Determine when you want to sync based on the order status.', $this->plugin_name ) . '</li>
				<li>' . __( 'The income statement and VAT return are updated immediately.', $this->plugin_name ) . '</li>
				<li>' . sprintf( __( 'Existing orders will be transferred from WooCommerce to your %s account (paid functionality).', $this->plugin_name ), $this->plugin_product_name ) . '</li>
				<li>' . __( 'Order information products (product name, quantity, amount of the order line and VAT) and shipping costs are taken over.', $this->plugin_name ) . '</li>
				<li>' . __( 'Discounts, both discount codes and cart discounts are included.', $this->plugin_name ) . '</li>
				<li>' . __( 'One-Stop Shop suitable.', $this->plugin_name ) . '</li>
				<li>' . __( 'Separate article code for Shipping costs and Costs.', $this->plugin_name ) . '</li>
				<li>' . __( 'Standard general ledger account for products.', $this->plugin_name ) . '</li>
				<li>' . sprintf( __( 'WooCommerce VAT rates link to %s VAT rates.', $this->plugin_name ), $this->plugin_product_name ) . '</li>
				<li>' . __( 'Install multiple times at multiple administrations.', $this->plugin_name ) . '</li>
				<li>' . sprintf( __( 'Use %s as an affordable and simple accounting software. We recommend that billing be done from WooCommerce.', $this->plugin_name ), $this->plugin_product_name ) . '</li>
			</ul>
		  <br><br>
		  
		  <form class="combidesk__cta-form" action="https://combidesk.com/integration/woocommerce-yuki" method="GET" target="_BLANK">
			<input type="hidden" name="email_address" value="' . esc_attr( $email_address ) . '" />
			<input type="submit" value="' . __( 'Install this integration', $this->plugin_name ) . '" />
		  </form>
		  
		  <form class="combidesk__cta-form" action="https://www.yuki.nl/nl/yuki-demo/" method="GET" target="_BLANK">
			<input type="hidden" name="utm_source" value="combidesk" />
			<input type="hidden" name="utm_medium" value="referral" />
			<input type="hidden" name="utm_campaign" value="tijdbesparen" />
			<input type="hidden" name="utm_term" value="combidesk_cta" />
			<input type="hidden" name="email_address" value="' . esc_attr( $email_address ) . '" />
			<input type="submit" value="' . __( 'No %s account yet?', $this->plugin_name ) . '" />
		  </form>';
		
		echo '
		<script>
		  window.intercomSettings = {
			app_id: "vyxefwdz"
		  };
		</script>
		
		<script>
		// We pre-filled your app ID in the widget URL: \'https://widget.intercom.io/widget/vyxefwdz\'
		(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic(\'reattach_activator\');ic(\'update\',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement(\'script\');s.type=\'text/javascript\';s.async=true;s.src=\'https://widget.intercom.io/widget/vyxefwdz\';var x=d.getElementsByTagName(\'script\')[0];x.parentNode.insertBefore(s,x);};if(w.attachEvent){w.attachEvent(\'onload\',l);}else{w.addEventListener(\'load\',l,false);}}})();
		</script>';
	}
	
	function load_combidesk_plugin_style( $hook ) {
		if ( $hook != 'toplevel_page_combidesk-yuki-settings' ) {
			return;
		}
		
		wp_enqueue_style( 'custom_wp_admin_css', plugins_url( '../assets/css/combidesk.min.css', __FILE__ ) );
	}
}
