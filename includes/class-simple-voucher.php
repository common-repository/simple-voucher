<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.danielsusanu.com
 * @since      1.0.0
 *
 * @package    Simple_Voucher
 * @subpackage Simple_Voucher/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simple_Voucher
 * @subpackage Simple_Voucher/includes
 * @author     Daniel Susanu <d.susanu.development@gmail.com>
 */
class Simple_Voucher {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Simple_Voucher_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SIMPLE_VOUCHER_VERSION' ) ) {
			$this->version = SIMPLE_VOUCHER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'simple-voucher';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		//$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Simple_Voucher_Loader. Orchestrates the hooks of the plugin.
	 * - Simple_Voucher_i18n. Defines internationalization functionality.
	 * - Simple_Voucher_Admin. Defines all hooks for the admin area.
	 * - Simple_Voucher_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-voucher-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-voucher-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-voucher-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-simple-voucher-public.php';

		$this->loader = new Simple_Voucher_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Simple_Voucher_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Simple_Voucher_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Simple_Voucher_Admin( $this->get_plugin_name(), $this->get_version() );
		// add custom api endpoints
		$this->loader->add_filter( 'rest_api_init', $plugin_admin, 'simple_voucher_register_api_routes' );

		if(is_admin()){
			
			// register general settings
			$this->loader->add_action( 'admin_init', $plugin_admin, 'simple_voucher_register_customer_settings' );
			
			// add css and js
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			// add menu items
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'simple_voucher_menu' );		

			// add create voucher modal
			$this->loader->add_action( 'admin_footer', $plugin_admin, 'add_craate_voucher_modal' );
			
			// add voucher button to order items
			$this->loader->add_filter( 'woocommerce_admin_order_actions', $plugin_admin, 'add_custom_order_status_actions_button', 100, 2 );
			//$this->loader->add_action( 'manage_shop_order_posts_custom_column' ,  $plugin_admin, 'add_custom_order_values_to_actions_column', 20, 2 );
			
			// add action button on order details page
			$this->loader->add_action( 'woocommerce_admin_order_data_after_order_details', $plugin_admin, 'add_custom_order_create_voucher_button' );
			//$this->loader->add_action( 'woocommerce_order_item_add_action_buttons', $plugin_admin, 'add_custom_order_create_voucher_button' );
			
			// add settings link
			$this->loader->add_filter( 'plugin_action_links_simple-voucher/simple-voucher.php', $plugin_admin, 'plugin_action_links'  );
			//$this->loader->add_action( 'activated_plugin', $plugin_admin, 'activation_redirect' );

			// admin notice to add simple_voucher account details
			$this->loader->add_action('admin_notices', $plugin_admin, 'add_notice_account_details');
			// check for updates
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'simple_voucher_check_for_update', 10, 2 );

			// run the action 		
			//$this->loader->add_filter( 'manage_shop_order_posts_columns', $plugin_admin, 'simple_voucher_add_shop_order_column' );
			//$this->loader->add_action('manage_shop_order_posts_custom_column', $plugin_admin, 'simple_voucher_add_shop_order_column_content',10, 2);
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	// private function define_public_hooks() {

	// 	$plugin_public = new Simple_Voucher_Public( $this->get_plugin_name(), $this->get_version() );

	// 	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
	// 	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	// }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Simple_Voucher_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
