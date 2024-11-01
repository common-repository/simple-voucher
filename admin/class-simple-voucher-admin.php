<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.danielsusanu.com
 * @since      1.0.0
 *
 * @package    Simple_Voucher
 * @subpackage Simple_Voucher/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Voucher
 * @subpackage Simple_Voucher/admin
 * @author     Daniel Susanu <d.susanu.development@gmail.com>
 */
class Simple_Voucher_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $api_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api_domain = 'https://simple-voucher-api.herokuapp.com';
		$this->api_hermes_endpoint_login = "/hermes_api/kernel/login";		

		//$this->log_message('###################################');
		//$this->log_message('###################################');
		//$this->log_message('Simple_Voucher_Admin initialized');
		//$this->log_message('###################################');
		//$this->log_message('###################################');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Voucher_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Voucher_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-voucher-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$this->log_message('simple_voucher_register_customer_settings');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-voucher-admin-bundle.js', array( 'jquery' ), $this->version, 'all' );		
	}

	/**
	 * Add Custom Menu
	 *
	 * @since    1.0.0
	 */
	public function simple_voucher_menu(){			
		add_submenu_page( 'woocommerce'      , 'Simple Voucher'    , 'Simple Voucher'    , 'edit_others_posts'  , 'simple-voucher-page', array($this, 'simple_voucher_admin_page' ) );	
	}

	
	/**
	 * Return Simple Voucher admin dispaly page
	 *
	 * @since    1.0.0
	 */
	public function simple_voucher_admin_page(){
		$this->add_secret_key();
		// return view
		require_once 'partials/simple-voucher-admin-display.php';
	}

	/**
	 * Add the Simple Voucher modal, only html part
	 *
	 * @since    1.0.0
	 */
	public function add_craate_voucher_modal(){
		$this->log_message('add_craate_voucher_modal ====================');
		//get the current screen		
		$screen = get_current_screen();
		$screen_id = $screen->id;      
		if($screen_id == 'woocommerce_page_wc-orders' || $screen_id == 'shop_order' || $screen_id == 'edit-shop_order'){			
            //$this->log_message('add_craate_voucher_modal:screen_id =' . $screen_id);
			$this->add_secret_key();
			include('partials/simple-voucher-create-voucher-modal-display.php');
		}		

		//error_log("test log error", 0);
	}

	function add_secret_key(){
		update_option( 'simple_voucher_secret_key', $this->generateRandomString(20) );		
	}

	/**
	 * Register Simple Voucher customer settings
	 *
	 * @since    1.0.0
	 */
	public function simple_voucher_register_customer_settings(){
		//simple_vouchersettings: field settigns are saved under
		$this->log_message('simple_voucher_register_customer_settings');

		register_setting( 'simple_voucher_settings', 'simple_voucher_username' );
		register_setting( 'simple_voucher_settings', 'simple_voucher_password' );
		register_setting( 'simple_voucher_settings', 'simple_voucher_api_domain' );		
		register_setting( 'simple_voucher_settings', 'simple_voucher_api_domain_id' );
		register_setting( 'simple_voucher_settings', 'simple_voucher_pdf_ids' );
		register_setting( 'simple_voucher_settings', 'simple_voucher_route_code' );
		register_setting( 'simple_voucher_settings', 'simple_voucher_update_order_status' );
		register_setting( 'simple_voucher_settings', 'simple_voucher_secret_key' );
		register_setting( 'simple_voucher_settings', 'simple_voucher_courier_name' );	
		register_setting( 'simple_voucher_settings', 'simple_voucher_show_voucher_pdf');
		register_setting( 'simple_voucher_settings', 'simple_voucher_courier_require_service_area');		
		//register_setting( 'simple_voucher_settings', 'simple_voucher_courier_additional_charges');		
	}

	/**
	 * Add Simple Voucher voucher button next to each order item
	 *
	 * @since    1.0.0
	 */
	public function add_custom_order_status_actions_button( $actions, $the_order ) {
				
		$simple_voucherUsername= get_option( 'simple_voucher_username' );
		$screen = get_current_screen();


		if($simple_voucherUsername != ''){
			$action_slug = 'simple_voucher-voucher-button';
			$order_id = method_exists($the_order, 'get_id') ? $the_order->get_id() : $the_order->id;

			
			// Set the action button
			$actions[$action_slug] = array(
				'url'       => '#id_' . $order_id,
				'name'      => __( 'Simple Voucher', 'woocommerce' ),
				'action'    => $action_slug
			);
		}

		return $actions;
	}

	function add_custom_order_values_to_actions_column( $column, $post_id ) {
		global $the_order, $post;
		$order_id = method_exists($the_order, 'get_id') ? $the_order->get_id() : $the_order->id;
		$country = $the_order->get_billing_country();
		$state = $the_order->get_billing_state();
		$billinStateName = 	 WC()->countries->get_states( $country )[$state];
		$country = $the_order->get_shipping_country();
		$state = $the_order->get_shipping_state();
		$shippingStateName = 	 WC()->countries->get_states( $country )[$state];
				
		if ( 'wc_actions' == $column ) {        	
			//echo esc_html('<textara style="display:none;" id="simple_voucher_order_textarea_' . $order_id . '"  billingState="' . $billinStateName .'" shippingState="' . $shippingStateName .'" > ' . $the_order . '</textara>');		
			
			$textareaId= 'simple_voucher_order_textarea_' . $order_id;
			woocommerce_wp_text_input( array(
				'id'            =>  $textareaId,				
				'value'         => $the_order,
				'style'			=> 'display:none;',
				'type'			=> 'hidden',
				'class'			=> 'simple_voucher_order_textarea'
			) );
		}	
	}

	

	/**
	 * Add create voucher button in the order details page
	 *
	 * @since    1.0.0
	 */
	public function add_custom_order_create_voucher_button($the_order){
		$simple_voucherUsername= get_option( 'simple_voucher_username' );
		//get the current screen
		$screen = get_current_screen();

		if($simple_voucherUsername != ''){
			$order_id = method_exists($the_order, 'get_id') ? $the_order->get_id() : $the_order->id;

			// $country = $the_order->get_billing_country();
			// $state = $the_order->get_billing_state();
			// $billinStateName = 	 WC()->countries->get_states($country)[$state];
			// $country = $the_order->get_shipping_country();
			// $state = $the_order->get_shipping_state();
			// $shippingStateName = 	 WC()->countries->get_states($country)[$state];

			//echo '<textara style="display:none;" id="simple_voucher_order_textarea_' . $order_id . '"  billingState="' . $billinStateName .'" shippingState="' . $shippingStateName .'" > ' . $the_order . '</textara>';
			// $textareaId= 'simple_voucher_order_textarea_' . $order_id;
			// woocommerce_wp_text_input( array(
			// 	'id'            =>  $textareaId,				
			// 	'value'         => $the_order,
			// 	'style'			=> 'display:none;',
			// 	'type'			=> 'hidden',
			// 	'class'			=> 'simple_voucher_order_textarea'
			// ) );

			woocommerce_wp_text_input(
				array(
					'id'            => '#id_' . $order_id,
					'value'         => 'Simple Voucher',
					'type'    		=> 'button',
					'wrapper_class' => 'form-field form-field-wide',
					'data-href'		=> '#id_' . $order_id,
					'class'			=> 'button-secondary simple_voucher-voucher-button',
                    'label'         => 'simple voucher'
				)
			);
			//echo '<p class="form-field form-field-wide"><a href="#id_'. $order_id . '" class="button-secondary simple_voucher-voucher-button">Simple Voucher</a></p>';		
		}
		
	}

	/**
	 * Add settings link to the plugin
	 *
	 * @since    1.0.0
	 */
	public function plugin_action_links($links_array){
		//'.esc_url( self::get_page_url() ).'
		$settings_link = '<a href="admin.php?page=simple-voucher-page" class="settings"> Ρυθμίσεις </a>';
  		array_unshift(  $links_array, $settings_link ); 
  		return  $links_array; 
	}
		

	public function simple_voucher_add_shop_order_column($columns){
		return array_merge($columns, ['verified' => 'Verified']);
	}

	public function simple_voucher_add_shop_order_column_content($column_key, $post_id){
		if ($column_key == 'verified') {
			$verified = get_post_meta($post_id, 'verified', true);
			if ($verified) {
				echo '<span style="color:green;">'; _e('Yes', 'textdomain'); echo '</span>';
			} else {
				echo '<span style="color:red;">'; _e('No', 'textdomain'); echo '</span>';
			}
		}
	}

	public function simple_voucher_check_for_update(){
		wp_update_plugins();
		$update_plugins = get_site_transient( 'update_plugins' );
		$simple_voucher_plugin = null;
		if ( ! empty( $update_plugins->response ) ){
			foreach ($update_plugins->response as $plugin) {
				if($plugin->slug == 'simple-voucher'){
					$simple_voucher_plugin = $plugin;
				}
			}
		}

		if($simple_voucher_plugin != null){
			$message = 'Υπάρχει μια νέα έκδοση του plugin (' . $simple_voucher_plugin->new_version . '), παρακαλώ κάντε αναβάθμιση!';
			$this->add_admin_notice($message, 'warning');
		}		
	}
	


	/**
	 * Register simple_voucher api endpoints
	 *
	 * @since    1.0.0
	 */
	public function simple_voucher_register_api_routes(){
		$this->log_message('simple_voucher_register_api_routes ');	

		register_rest_route( 'simple_voucher/v1', '/api', array(
			'methods' => 'POST',
			'callback' => array($this,'apiAuthMiddleware'),
            'permission_callback' => array($this, 'api_permission_check')
		));

		// register_rest_route( 'simple_voucher/v1', '/login', array(
		// 	'methods' => 'POST',
		// 	'callback' => array($this,'simple_voucher_login_customer')
		//   ) );

		// register_rest_route( 'simple_voucher/v1', '/voucher', array(
		// 	'methods' => 'POST',
		// 	'callback' =>  array($this,'simple_voucher_create_voucher'),
		// ));  

		// register_rest_route( 'simple_voucher/v1', '/make-zonearea', array(
		// 	'methods' => 'POST',
		// 	'callback' =>  array($this,'simple_voucher_make_zonearea'),
		// ));  

		// register_rest_route( 'simple_voucher/v1', '/site', array(
		// 	'methods' => 'POST',
		// 	'callback' =>  array($this,'simple_voucher_api_site'),
		// ));  

		// register_rest_route( 'simple_voucher/v1', '/options', array(
		// 	'methods' => 'POST',
		// 	'callback' =>  array($this,'get_options'),
		// ));  	

		// register_rest_route( 'simple_voucher/v1', '/order', array(
		// 	'methods' => 'POST',
		// 	'callback' =>  array($this,'get_order'),
		// ));  		

	}

    /**
	 * All api requests should go through this method if they need authorisation.
	 *
	 * @since    1.2.4
	 */
    public function api_permission_check(){
        return true;
    }

	/**
	 * All api requests should go through this method if they need authorisation.
	 *
	 * @since    1.1.4
	 */
	public function apiAuthMiddleware(WP_REST_Request $request){
		$this->log_message('apiAuthMiddleware ================');	

		// $request_secret_key = $request->get_param('secret_key');
		// $secret_key = get_option('simple_voucher_secret_key');
		
		// // Checked if request is authorised
		// if($request_secret_key != $secret_key){						
		// 	$response =  new WP_REST_Response(array(
		// 		'code' => 403,
		// 		'message' => 'Το token δεν ήταν σωστό'
		// 	  ));
		// 	$response->set_status(403);
		// 	return $response;
		// }

		$action = $request->get_param('action');
		
		switch ($action) {
			case 'ORDER':				
				return $this->get_order($request);
				break;
			case 'OPTIONS':
				return $this->get_options($request);
				break;	
			case 'LOGIN':
				return $this->simple_voucher_login_customer($request);
				break;
			case 'VOUCHER':
				return $this->simple_voucher_create_voucher($request);
				break;	
			case 'MAKE_ZONEAREA':
				return $this->simple_voucher_make_zonearea($request);
				break;	
			case 'register_site':
				return $this->simple_voucher_api_site($request);
				break;			
			default:
				$response =  new WP_REST_Response(array(
					'code' => 404,
					'message' => 'Δεν βρέθηκε διαδρομή που να ταιριάζει με  τη διεύθυνση URL και τη μέθοδο του αιτήματος.'
				));
				$response->set_status(404);
				return $response;
				break;
		}

	}


	/**
	 * /options endpoint
	 *
	 * @since    1.0.0
	 */
	public function get_options(WP_REST_Request $request){
		$this->log_message('get_options ================');		

		$api_domain = get_option('simple_voucher_api_domain');
		$username = get_option('simple_voucher_username' );				
		$pdf_ids = get_option('simple_voucher_pdf_ids' );
		$route_code = get_option('simple_voucher_route_code' );
		$show_voucher_pdf = get_option('simple_voucher_show_voucher_pdf' );
		$courier_name = 'GT Elite Courier';
		$require_service_area= get_option( 'simple_voucher_courier_require_service_area');		
		
		// default to true
		if(strlen($show_voucher_pdf) == 0){
			$show_voucher_pdf = "true";
		}

		return new WP_REST_Response(
			array(
			  'courierApiDomain' => $api_domain,
			  'username' => $username,
			  'pdfs' => $pdf_ids,
			  'routeCode' => $route_code,
			  'showVoucherPdf' => $show_voucher_pdf,
			  'courierName' => $courier_name,
			  'requireServiceArea' => $require_service_area
			)
		);						
	}

	/**
	 * /options endpoint
	 *
	 * @since    1.0.0
	 */
	public function get_order(WP_REST_Request $request){
		$this->log_message('get_order ================');
				
		$order_id = $request->get_param('order_id');
		$order = wc_get_order( absint( $order_id ) );

		if ( $order ) {
			return new WP_REST_Response(
				array(
					'success'=> true,					
					'data' => $order->get_data()
				)
			);						
		}


		$response =  new WP_REST_Response(array(
			'code' => 404,
			'message' => 'Order id is not valid'
		  ));
		$response->set_status(404);
		return $response;

		
	}
	

	/**
	 * /save-details-and-login endpoint
	 *
	 * @since    1.0.0
	 */
	public function simple_voucher_login_customer(WP_REST_Request $request){
		$this->log_message('simple_voucher_login_customer ================');

		$response_data = new stdClass();
		$apiHermesEndpointLogin= $this-> api_hermes_endpoint_login;
		$existingCourierDomain= get_option('simple_voucher_api_domain'); 
		$isExistingCourierDomain = $existingCourierDomain != '';	
		$newCourierDomain=$request->get_param( 'courierApiDomain' );
		$force_get_courier_data=$request->get_param( 'forceGetCourierData' );

		$this->log_message('simple_voucher_login_customer ================' . $newCourierDomain);

		$isNewCourierDomain= $newCourierDomain != $existingCourierDomain; 
		
		// get details for the courier from simple-voucher api. 

        $response_data->courier='GT Elite Courier';

		$url = 'https://' . $newCourierDomain . $apiHermesEndpointLogin;

		$this->log_message('url: ' . $url);

		$username= get_option('simple_voucher_username'); 
		$password= get_option('simple_voucher_password'); 

		if($isNewCourierDomain){
			$username= $request->get_param( 'username' );
			$password= $request->get_param( 'password' );
		}
		
		$response = wp_remote_post( $url, array(
			'method'      => 'POST',
			'body' => array(
				"username" => $username,
				"password" => $password
			)
		));

		$responseBody  = wp_remote_retrieve_body($response);
		$response_code = wp_remote_retrieve_response_code($response);

		//echo 'url ' . $url;
		//echo ': responseBody ' . $responseBody;
		//echo ': response code ' . $response_code;

		$this->log_message('Login response ' . $responseBody);

		if($response_code != 200){
			$response_data->code=401;
			return $response_data;
		}

		$body = json_decode( $responseBody );

		$sid = $body->data->sid;
		//$cookieExpiration= time()+3600 * 1;
		//setcookie('sv_sid', $sid, $cookieExpiration , "/"); // 1 hours
		
		$response_data->code=$body->code;
		$response_data->data=$body->data;

		return $response_data;		

	}


	/**
	 * Create voucher endpoint
	 *
	 * @since    1.0.0
	 */
	public function simple_voucher_create_voucher(WP_REST_Request $request){
		$apiDomain= get_option( 'simple_voucher_api_domain' ); 
		$apiEndpoint='/hermes_api/courier/r18';
		$url = 'https://' . $apiDomain . $apiEndpoint;

				
		$sid= $request->get_param( 'sid' );
		$order_id= $request->get_param( 'orderId' );		

		//echo 'url ' . $url;
		//echo 'sid ' . $sid;

		$response = wp_remote_post( $url, array(
			'method'      => 'POST',
			'body' => $request->get_params(),
			'cookies'     => array(
				'PHPSESSID' => $sid
			)
			));
		
		$responseBody  = wp_remote_retrieve_body($response);
		$body = json_decode( $responseBody );
		$voucher = $body->data->p01;
		$voucherId = $body->data->nr01;

		update_post_meta( $order_id, 'voucher', $voucher );
		update_post_meta( $order_id, 'voucherId', $voucherId );

		$updateOrderStatus= get_option( 'simple_voucher_update_order_status' ); 		
		// TODO Complete order 
		if($updateOrderStatus != "false"){
			if($updateOrderStatus == "wc-completed"){
				$this->log_message('update order status to ' . $updateOrderStatus);
				$order = wc_get_order($order_id);		
				$order->update_status( $updateOrderStatus, '', true );
				do_action( 'woocommerce_order_edit_status', $order->get_id(), $updateOrderStatus ); 
			}
		}
		

		//setcookie('PHPSESSID', $sid, 'session', "/");
		//$request->get_header('cookie')

		return $body;		
	}

	/**
	 * Get zonearea endpont
	 *
	 * @since    1.0.0
	 */
	public function simple_voucher_make_zonearea(WP_REST_Request $request){

		$apiDomain= get_option( 'simple_voucher_api_domain' ); //'https://simple_voucher.extranet4u.com';
		$apiEndpoint='/hermes_api/courier/make_zonearea';
		$url = 'https://' . $apiDomain . $apiEndpoint;

		$sid= $request->get_param( 'sid' );
		$requestCokies= array();

		if ($sid == ""){
			$response =  new WP_REST_Response(array(
				'code' => 404,
				'message' => 'Order id is not valid'
			  ));
			$response->set_status(404);
			return $response;
		}

		$requestCokies= array('PHPSESSID' => $sid);			


		$response = wp_remote_post( $url, array(
			'method'      => 'GET',
			// 'body'        => array(
			// 	'username' => $username,
			// 	'password' => $password
			// ),
			'body' => $request->get_params(),
			'cookies'     => $requestCokies			
			));
		
		$responseBody  = wp_remote_retrieve_body($response);
		$body = json_decode( $responseBody );
		//$sid = $body->data->sid;

		//$cookieExpiration= time()+3600 * 12;

		return $body;		
	}

	public function simple_voucher_api_site(WP_REST_Request $request){

		$simpleVoucherApiDomain= $this->api_domain . '/sites';
				
		$domain= $request->get_param( 'domain' );
		$courierId = $request->get_param( 'courierId' );

		$request_data = new stdClass();
		$request_data->domain=$domain;

		 $courier = new stdClass();
		 $courier->_id=$courierId;
		 $courier->id=$courierId;

		$request_data->couriers=array($courier);

		$response = wp_remote_post( $simpleVoucherApiDomain, array(
			'method'      => 'POST',
			'blocking'    => true,
			'headers' => array(
				'content-type' => 'application/json'
			),			
			'body' => json_encode($request_data),
			));

		$responseBody  = wp_remote_retrieve_body($response);
		$body = json_decode( $responseBody );

		return $body;		
	}


	/**
	 * Add alert if no account details
	 *
	 * @since    1.0.0
	 */
	public function add_notice_account_details(){
		$simple_voucherUsername= get_option( 'simple_voucher_username' );

		//get the current screen
		$screen = get_current_screen();
 
		//return if plugin settings page 
		//To get the exact your screen ID just do var_dump($screen)
		if ( $screen->id == 'woocommerce_page_simple-voucher-page') return;
		
		if($simple_voucherUsername == ''){
			$this->add_admin_notice('Παρακαλώ εισάγετε τα στοιχεία του λογαριασμού σας για να δημιουργήσετε voucher.','warning');
		}		
	}


	public function get_courier_details($courierDomain){
		//echo ' courierDomain ' . $courierDomain;

		$simpleVoucherApiDomain= $this->api_domain;
		$msg="Η Courier που προσπαθείτε να συνδεθείτε δεν βρεθηκε στο σύστημα μας. ";

		$requestUrl= $simpleVoucherApiDomain . "/couriers?_where[0][domain]=" . $courierDomain;
		$response= wp_remote_post( $requestUrl, array(
			'method'      => 'GET',
			'headers' => array(
				'content-type' => 'application/json'
			)
		));

		$response_code = wp_remote_retrieve_response_code($response);

		$response_data = new stdClass();

		if($response_code != 200){
			$response_data->msg=$msg;
			$response_data->code=404;
			return $response_data;
		}
		
		// get data from response
		$responseBody= wp_remote_retrieve_body($response);
		$responseObj= json_decode( $responseBody );		

		 //if result array length is zero, return message and status 404
		if(sizeof($responseObj) == 0){			
			$response_data->msg=$msg;
			$response_data->code=404;
			return $response_data;
		}

		$response_data->courier=$responseObj[0];
		$response_data->code = 200;
		
		return $response_data;
	}

	

	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';+
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {			
			$randomString .= $characters[rand(0, $charactersLength - 1)];
			if ($i % 4 == 0) {
				$randomString .= '-';
			}
		}
		return $randomString;
	}

	function log_message($msg){
		//error_log('LOG.simple-voucher: ' . $msg, 0);
	}

	function add_admin_notice($message, $type) {
		?>
		<div class="notice notice-<?php echo $type?> is-dismissible">
			<p><b>Simple Voucher: <?php _e($message) ?></b></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">Dismiss this notice.</span>
			</button>
		</div>
		<?php
	}

}
