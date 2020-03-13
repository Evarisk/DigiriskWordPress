<?php
/**
 * Gestion des actions des plans de prevention
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     7.1.0
 * @version   7.1.0
 * @copyright 2018 Evarisk.
 * @package   DigiRisk
 */

namespace digi;

use eoxia\Custom_Menu_Handler as CMH;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des actions des plans de prevention pour la lecture.
 */
class Prevention_Page_Action {

	/**
	 * Le constructeur appelle une action personnalisée
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 41 );

		add_action( 'wp_ajax_start_prevention', array( $this, 'callback_start_prevention' ) );
		add_action( 'admin_post_start_prevention', array( $this, 'callback_start_prevention' ) );

		add_action( 'wp_ajax_next_step_prevention', array( $this, 'ajax_next_step_prevention' ) );
		add_action( 'admin_post_next_step_prevention', array( $this, 'ajax_next_step_prevention' ) );

		add_action( 'admin_post_change_step_prevention', array( $this, 'change_step_prevention' ) );
	}

	public function callback_admin_menu() {
		if ( user_can( get_current_user_id(), 'manage_prevention' ) ) {
			CMH::register_menu( 'digirisk', __( 'Plan de prévention', 'digirisk' ), __( 'Plan de prévention', 'digirisk' ), 'manage_prevention', 'digirisk-prevention', array( Prevention_Page_Class::g(), 'display' ), 'fa fa-info', 4 );
		}
	}

	/**
	 * Creation des plans de prévention
	 * @var [type]
	 */
	 public function callback_start_prevention() {
 		$id = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;

		$prevention = Prevention_Class::g()->get( array( 'id' => $id ), true );
		if( ! empty( $prevention ) && $prevention->data[ 'id' ] != 0 ){
			echo '<pre>'; print_r( 'DEFINE' ); echo '</pre>'; exit;
		}else{
			$data = array(
				'title' => 'Nouveau Plan de prévention',
				'step'  => 1
			);
			$prevention = Prevention_Class::g()->create( $data );
		}
		wp_redirect( admin_url( 'admin.php?page=digirisk-prevention&id=' . $prevention->data['id'] ) );
 	}

	public function ajax_next_step_prevention() {
		ini_set( 'display_errors', true );
		error_reporting( E_ALL );
		wp_verify_nonce( 'next_step_causerie' );

		$id = ! empty( $_REQUEST['id'] ) ? (int) $_REQUEST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$prevention = Prevention_Class::g()->get( array( 'id' => $id ), true );
		$prevention = Prevention_Class::g()->add_information_to_prevention( $prevention );

		if ( empty( $prevention ) ) {
			wp_send_json_error();
		}

		$society = Society_Class::g()->get( array(
			'posts_per_page' => 1,
		), true );

		$legal_display = '';
		if( ! empty( $society ) ){
			$legal_display = Legal_Display_Class::g()->get( array(
				'posts_per_page' => 1,
				'post_parent'    => $society->data[ 'id' ],
			), true );
		}

		$url_redirect = '';

		Prevention_Page_Class::g()->register_search( null, $prevention );

		switch ( $prevention->data['step'] ) {
			case \eoxia\Config_Util::$init['digirisk']->prevention_plan->steps->PREVENTION_FORMER:
				$prevention = Prevention_Class::g()->step_maitreoeuvre( $prevention );
				$prevention = Prevention_Class::g()->add_information_to_prevention( $prevention );

				ob_start();
				\eoxia\View_Util::exec( 'digirisk', 'prevention_plan', 'start/main', array(
					'prevention' => $prevention,
				) );
				break;
			case \eoxia\Config_Util::$init['digirisk']->prevention_plan->steps->PREVENTION_INFORMATION:
				$nextstep = \eoxia\Config_Util::$init['digirisk']->prevention_plan->steps->PREVENTION_ENTERPRISE;
				$data = array(
					'title'               => isset( $_POST[ 'prevention-title' ] ) ? sanitize_text_field( $_POST[ 'prevention-title' ] ) : '',
					'more_than_400_hours' => isset( $_POST[ 'more_than_400_hours' ] ) ? (int) $_POST[ 'more_than_400_hours' ] : '0',
					'imminent_danger'     => isset( $_POST[ 'imminent_danger' ] ) ? (int) $_POST[ 'imminent_danger' ]: '0',
					'date_start'          => isset( $_POST[ 'start_date' ] ) ? sanitize_text_field( $_POST[ 'start_date' ] ) : '',
					'date_end'            => isset( $_POST[ 'end_date' ] ) ? sanitize_text_field( $_POST[ 'end_date' ] ) : '',
					'date_end__is_define' => isset( $_POST[ 'date_end__is_define' ] ) ? sanitize_text_field( $_POST[ 'date_end__is_define' ] ) : 'defined'
				);

				$prevention = Prevention_Class::g()->update_information_prevention( $prevention, $data );
				$prevention = Prevention_Page_Class::g()->next_step( $prevention, $nextstep );
				$prevention = Prevention_Class::g()->add_information_to_prevention( $prevention );


				ob_start();
				\eoxia\View_Util::exec( 'digirisk', 'prevention_plan', 'start/main', array(
					'prevention'    => $prevention,
					'society'       => $society,
					'legal_display' => $legal_display
				) );
				break;
			case \eoxia\Config_Util::$init['digirisk']->prevention_plan->steps->PREVENTION_ENTERPRISE:
				$prevention = Prevention_Page_Class::g()->save_society_information( $prevention, $society, $legal_display );
				$prevention = Prevention_Class::g()->add_information_to_prevention( $prevention );
				ob_start();
				\eoxia\View_Util::exec( 'digirisk', 'prevention_plan', 'start/main', array(
					'prevention'    => $prevention,
					'society'       => $society,
					'legal_display' => $legal_display
				) );
				break;
			case \eoxia\Config_Util::$init['digirisk']->prevention_plan->steps->PREVENTION_PARTICIPANT:
				$prevention = Prevention_Class::g()->save_info_maitre_oeuvre();
				if( ! $prevention->data['is_end'] ){
					Prevention_Page_Class::g()->step_close_prevention( $prevention, $society, $legal_display );
				}
				$url_redirect = admin_url( 'admin.php?page=digirisk-prevention' );
				break;
			default:
				break;
		}

		wp_send_json_success( array(
			'namespace'        => 'digirisk',
			'module'           => 'preventionPlan',
			'callback_success' => 'nextStep',
			'current_step'     => $prevention->data['step'],
			'url'              => $url_redirect,
			'view'             => ob_get_clean(),
		) );
	}

	public function change_step_prevention(){
		$id   = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		$step = ! empty( $_GET['step'] ) ? (int) $_GET['step'] : 0;

		$prevention = Prevention_Class::g()->get( array( 'id' => $id ), true );

		if ( $prevention->data['maitre_oeuvre'][ 'user_id' ] && $prevention->data['maitre_oeuvre'][ 'signature_id'] ) {
			$prevention->data['step'] = $step;
		}else{
			$prevention->data['step'] = \eoxia\Config_Util::$init['digirisk']->prevention_plan->steps->PREVENTION_FORMER;
		}
		Prevention_Class::g()->update( $prevention->data );


		wp_redirect( admin_url( 'admin.php?page=digirisk-prevention&id=' . $id ) );
	}

}

new Prevention_Page_Action();
