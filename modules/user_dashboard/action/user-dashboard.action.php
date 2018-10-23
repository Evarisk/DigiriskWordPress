<?php
/**
 * Les actions relatives aux utilisateurs dans la page "utilisateur" de WordPress.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.0.0
 * @version 6.4.4
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux utilisateurs dans la page "utilisateur" de WordPress.
 */
class User_Shortcode_Action extends \eoxia001\Singleton_Util {

	/**
	 * Le constructeur appelle les actions suivantes:
	 * admin_menu (Pour déclarer le sous menu dans le menu utilisateur de WordPress)
	 *
	 * @since 6.0.0
	 * @version 6.2.4
	 */
	protected function construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );

		add_action( 'wp_ajax_save_user', array( $this, 'ajax_save_user' ) );
		add_action( 'wp_ajax_load_user', array( $this, 'ajax_load_user' ) );
		add_action( 'wp_ajax_delete_user', array( $this, 'ajax_delete_user' ) );
	}

	/**
	 * Créer la page "Digirisk" dans le menu "Utilisateurs" de WordPress
	 *
	 * @since 6.0.0
	 * @version 6.2.4
	 */
	public function callback_admin_menu() {
		add_users_page( __( 'Utilisateurs DigiRisk', 'digirisk' ), __( 'Utilisateurs DigiRisk', 'digirisk' ), 'manage_digirisk', 'digirisk-users', array( $this, 'callback_users_page' ) );
	}

	/**
	 * Le callback de "add_users_page" qui permet d'afficher la vue pour le rendu de la page.
	 *
	 * @return void
	 *
	 * @since 6.0.0
	 * @version 6.2.4
	 */
	public function callback_users_page() {
		\eoxia001\View_Util::exec( 'digirisk', 'user_dashboard', 'main' );
	}

	/**
	 * Enregistres un utilisateur avec les paramètres reçu par le formulaire.
	 *
	 * @return void
	 *
	 * @since 6.0.0
	 * @version 6.4.4
	 */
	public function ajax_save_user() {
		check_ajax_referer( 'ajax_save_user' );

		$update_state = User_Digi_Class::g()->update( $_POST );

		$error = is_wp_error( $update_state->id );

		ob_start();
		User_Dashboard_Class::g()->display_list_user();
		wp_send_json_success( array(
			'namespace'        => 'digirisk',
			'module'           => 'userDashboard',
			'callback_success' => 'savedUserSuccess',
			'template'         => ob_get_clean(),
			'error'            => $error,
			'object'           => $update_state,
		) );
	}

	/**
	 * Charges un utilisateur et renvoie dans la réponse JSON la vue.
	 *
	 * @return void
	 *
	 * @since 6.0.0
	 * @version 6.2.9
	 */
	public function ajax_load_user() {
		check_ajax_referer( 'ajax_load_user' );

		if ( 0 === (int) $_POST['id'] ) {
			wp_send_json_error( );
		} else {
			$id = (int) $_POST['id'];
		}

		$user = User_Digi_Class::g()->get( array( 'id' => $id ) );
		$user = $user[0];

		ob_start();
		\eoxia001\View_Util::exec( 'digirisk', 'user_dashboard', 'item-edit', array( 'user' => $user ) );
		wp_send_json_success( array(
			'namespace' => 'digirisk',
			'module' => 'userDashboard',
			'callback_success' => 'loadedUserSuccess',
			'template' => ob_get_clean(),
		) );
	}

	/**
	 * Supprimes un utilisateur.
	 *
	 * @return void
	 *
	 * @since 6.0.0
	 * @version 6.2.9
	 */
	public function ajax_delete_user() {
		check_ajax_referer( 'ajax_delete_user' );

		if ( 0 === (int) $_POST['id'] ) {
			wp_send_json_error();
		} else {
			$id = (int) $_POST['id'];
		}

		User_Digi_Class::g()->delete( $id );
		wp_send_json_success( array(
			'namespace' => 'digirisk',
			'module' => 'userDashboard',
			'callback_success' => 'deletedUserSuccess',
		) );
	}
}

User_Shortcode_Action::g();
