<?php
/**
 * Classe gérant les actions principales de l'application.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.0.0
 * @version 6.4.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les actions principales de l'application.
 */
class Digirisk_Action {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 */
	public function __construct() {
		// Initialises ses actions que si nous sommes sur une des pages réglés dans le fichier digirisk.config.json dans la clé "insert_scripts_pages".
		$page = ( ! empty( $_REQUEST['page'] ) ) ? sanitize_text_field( $_REQUEST['page'] ) : '';

		if ( in_array( $page, \eoxia\Config_Util::$init['digirisk']->insert_scripts_pages_css, true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts_css' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_css' ), 11 );
			add_action( 'admin_print_scripts', array( $this, 'callback_admin_print_scripts_css' ) );
		}

		if ( in_array( $page, \eoxia\Config_Util::$init['digirisk']->insert_scripts_pages_js, true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts_js' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_js' ), 11 );
			add_action( 'admin_print_scripts', array( $this, 'callback_admin_print_scripts_js' ) );
		}

		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );

		add_action( 'wp_ajax_close_change_log', array( $this, 'callback_close_change_log' ) );
	}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 *
	 * @since 6.0.0
	 * @version 6.3.1
	 */
	public function callback_before_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_media();
		add_thickbox();
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin DigiRisk.
	 *
	 * @return void nothing
	 *
	 * @since 6.0.0
	 * @version 6.4.0
	 */
	public function callback_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'signature-pad', 'https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js', array( 'jquery' ), \eoxia\Config_Util::$init['digirisk']->version, false );
		wp_enqueue_script( 'digi-script-datetimepicker-script', PLUGIN_DIGIRISK_URL . 'core/assets/js/jquery.datetimepicker.full.js', array(), \eoxia\Config_Util::$init['digirisk']->version );
		wp_enqueue_script( 'digi-script', PLUGIN_DIGIRISK_URL . 'core/assets/js/backend.min.js', array(), \eoxia\Config_Util::$init['digirisk']->version, false );
		wp_enqueue_script( 'digi-script-owl-carousel', PLUGIN_DIGIRISK_URL . 'core/assets/js/owl.carousel.min.js', array(), \eoxia\Config_Util::$init['digirisk']->version, false );
		wp_enqueue_script( 'digi-script-treetable', PLUGIN_DIGIRISK_URL . 'core/assets/js/jquery.treetable.js', array(), \eoxia\Config_Util::$init['digirisk']->version, false );
	}

	/**
	 * Initialise en php le fichier permettant la traduction des variables string JavaScript.
	 *
	 * @return void nothing
	 *
	 * @since 6.0.0
	 * @version 6.5.0
	 */
	public function callback_admin_print_scripts_js() {
		require PLUGIN_DIGIRISK_PATH . '/core/assets/js/define-string.js.php';
	}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 *
	 * @since 6.0.0
	 * @version 6.5.0
	 */
	public function callback_before_admin_enqueue_scripts_css() {}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin DigiRisk.
	 *
	 * @return void nothing
	 *
	 * @since 6.0.0
	 * @version 6.3.0
	 */
	public function callback_admin_enqueue_scripts_css() {
		wp_register_style( 'digi-style', PLUGIN_DIGIRISK_URL . 'core/assets/css/style.min.css', array(), \eoxia\Config_Util::$init['digirisk']->version );
		wp_enqueue_style( 'digi-style' );

		wp_enqueue_style( 'digi-datepicker', PLUGIN_DIGIRISK_URL . 'core/assets/css/jquery.datetimepicker.css', array(), \eoxia\Config_Util::$init['digirisk']->version );
		wp_enqueue_style( 'digi-treetable', PLUGIN_DIGIRISK_URL . 'core/assets/css/jquery.treetable.css', array(), \eoxia\Config_Util::$init['digirisk']->version );
		wp_enqueue_style( 'digi-treetable-default', PLUGIN_DIGIRISK_URL . 'core/assets/css/jquery.treetable.theme.default.css', array(), \eoxia\Config_Util::$init['digirisk']->version );
		wp_enqueue_style( 'digi-owl-carousel', PLUGIN_DIGIRISK_URL . 'core/assets/css/owl.carousel.min.css', array(), \eoxia\Config_Util::$init['digirisk']->version );
	}

	/**
	 * Initialise en php le fichier permettant la traduction des variables string JavaScript.
	 *
	 * @return void nothing
	 *
	 * @since 6.0.0
	 * @version 6.5.0
	 */
	public function callback_admin_print_scripts_css() {}

	/**
	 * Initialise le fichier MO
	 *
	 * @since 6.0.0
	 * @version 6.4.0
	 */
	public function callback_plugins_loaded() {
		load_plugin_textdomain( 'digirisk', false, PLUGIN_DIGIRISK_DIR . '/core/assets/languages/' );

		/** Set capability to administrator by default */
		$administrator_role = get_role( 'administrator' );
		if ( ! $administrator_role->has_cap( 'manage_digirisk' ) ) {
			$administrator_role->add_cap( 'manage_digirisk' );
		}
	}

	/**
	 * Définition du menu dans l'administration de WordPress pour Digirisk
	 *
	 * @since 6.0.0
	 * @version 6.5.0
	 */
	public function callback_admin_menu() {
		$digirisk_core = get_option( \eoxia\Config_Util::$init['digirisk']->core_option );

		if ( ! empty( $digirisk_core['installed'] ) ) {
			add_menu_page( __( 'DigiRisk', 'digirisk' ), __( 'DigiRisk', 'digirisk' ), 'manage_digirisk', 'digirisk-simple-risk-evaluation', array( Digirisk_Class::g(), 'display' ), PLUGIN_DIGIRISK_URL . 'core/assets/images/favicon2.png', 4 );
		}
	}

	/**
	 * Lors de la fermeture de la notification de la popup.
	 * Met la metadonnée '_wpdigi_user_change_log' avec le numéro de version actuel à true.
	 *
	 * @return void
	 */
	public function callback_close_change_log() {
		check_ajax_referer( 'close_change_log' );

		$version = ! empty( $_POST['version'] ) ? sanitize_text_field( $_POST['version'] ) : '';

		if ( empty( $version ) ) {
			wp_send_json_error();
		}

		$meta = get_user_meta( get_current_user_id(), '_wpdigi_user_change_log', true );

		if ( empty( $meta ) ) {
			$meta = array();
		}

		$meta[ $version ] = true;
		update_user_meta( get_current_user_id(), '_wpdigi_user_change_log', $meta );

		wp_send_json_success( array() );
	}
}

new Digirisk_Action();
