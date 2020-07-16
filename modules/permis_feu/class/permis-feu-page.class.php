<?php
/**
 * Gestion du déroulement du permis de feu
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     7.4.0
 * @version   7.4.0
 * @copyright 2019 Evarisk.
 * @package   DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion du déroulement de l'intervention du quart d'heure sécurité.
 */
class Permis_Feu_Page_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util.
	 *
	 * @since   6.6.0
	 */
	protected function construct() {}


	/**
	 * Affiches la vue principale de la page "Causerie".
	 *
	 * Si $_GET['id'] existe, affiches la vue d'intervention.
	 *
	 * @since   6.6.0
	 * @version 6.6.0
	 *
	 * @return void
	 */
	public function display() {
		Permis_feu_Class::g()->generate_worktype_if_not_exist();
		$id = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0; // WPCS: CSRF ok.

		if ( ! empty( $id ) ) {
			$this->display_single( $id );
		} else {
			\eoxia\View_Util::exec( 'digirisk', 'permis_feu', 'main' );
		}
	}

	public function display_single( $id ){

		$permis_feu = Permis_Feu_Class::g()->get( array( 'id' => $id ), true );
		if ( ! empty( $permis_feu ) ) {
			\eoxia\View_Util::exec( 'digirisk', 'permis_feu', 'start/main', array(
				'permis_feu' => Permis_Feu_Class::g()->add_information_to_permis_feu( $permis_feu )
			) );
		} else {
			\eoxia\View_Util::exec( 'digirisk', 'permis_feu', 'main' );
		}
	}

	public function display_dashboard(){
		$list_permis_feu = Permis_Feu_Class::g()->get( array(
			'meta_key'   => '_wpdigi_permis_feu_is_end',
            'meta_value' => \eoxia\Config_Util::$init['digirisk']->permis_feu->status->PERMIS_FEU_IS_ENDED,
		) );

		\eoxia\View_Util::exec( 'digirisk', 'permis_feu', 'dashboard/main', array(
			'list_permis_feu' => $list_permis_feu
		) );
	}

	public function display_progress() {
		$args = array(
		    'meta_query' => array(
				'relation' => 'OR',
        		array(
		            'key'     => '_wpdigi_permis_feu_is_end',
		            'compare' => 'NOT EXISTS',
		        ),
		        array(
		            'key'	  => '_wpdigi_permis_feu_is_end',
		            'value'   => \eoxia\Config_Util::$init['digirisk']->permis_feu->status->PERMIS_FEU_IN_PROGRESS,
		        ),
		    ),
		);

		$permis_feu = Permis_Feu_Class::g()->get( $args );

		\eoxia\View_Util::exec( 'digirisk', 'permis_feu', 'progress/main', array(
			'permis_feu' => $permis_feu,
			'nbr' => count( $permis_feu )
		) );
	}

	public function register_search( $permis_feu, $post_values = array() ) {
		global $eo_search;

		$args_permis_feu_participants = array(
			'type'  => 'user',
			'name'  => 'participant_id',
		);

		$eo_search->register_search( 'permis_feu_participants', $args_permis_feu_participants );

		$args_accident_post = array(
			'type'  => 'post',
			'name'  => 'unitedetravail',
			'args' => array(
				'model_name' => array(
					'\digi\Group_Class',
					'\digi\Workunit_Class',
				),
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => '_wpdigi_unique_identifier',
						'compare' => 'LIKE',
					),
					array(
						'key' => '_wpdigi_unique_key',
						'compare' => 'LIKE',
					),
				)
			),
			'icon' => 'fa-search',
		);

		if ( ! empty( $post_values['intervention'] ) ) {
			$args_accident_post['hidden_value'] = $post_values['intervention']->data['unite_travail'];
			$args_accident_post['value']        = Prevention_Intervention_Class::g()->return_name_workunit( $post_values['intervention']->data[ 'unite_travail' ] );
		}

		$args_accident_post = wp_parse_args( $args_accident_post, $post_values );


		$args_permis_feu_maitreoeuvre = array(
			'type'         => 'user',
			'name'         => 'user_id',
			'label'        => __( 'Nom', 'digirisk' ),
			'value'        => ! empty( $permis_feu->data['maitre_oeuvre']['user_id'] ) ? $permis_feu->data['maitre_oeuvre']['data']->last_name : '',
			'hidden_value' => $permis_feu->data['maitre_oeuvre']['user_id'],
			'class'        => ! empty( $permis_feu->data['maitre_oeuvre']['user_id'] ) ? 'form-element-disable' : '',
			'icon'         => 'fa-search',
		);

		$eo_search->register_search( 'maitre_oeuvre', $args_permis_feu_maitreoeuvre );

		$eo_search->register_search( 'accident_post', $args_accident_post );

		$args_prevention = array(
			'label' => 'Prevention',
			'icon'  => 'fa-search',
			'type'  => 'post',
			'name'  => 'prevention_id',
			'args' => array(
				'model_name' => array(
					'\digi\Prevention_Class'
				),
				'meta_query' => array(
					array(
						'key' => '_wpdigi_prevention_prevention_is_end',
						'value' => \eoxia\Config_Util::$init['digirisk']->prevention_plan->status->PREVENTION_IS_ENDED,
					)
				)
			),
			'icon' => 'fa-search',
			'next_action' => 'digi_permis_feu_search_prevention',
		);

		$eo_search->register_search( 'prevention_list', $args_prevention );

		$args_permis_feu_intervenant = array(
			'type'         => 'user',
			'name'         => 'user_id',
			'label'        => __( 'Nom', 'digirisk' ),
			'value'        => ! empty( $permis_feu->data['intervenant_exterieur']['user_id'] ) ? $permis_feu->data['intervenant_exterieur']['data']->last_name : '',
			'hidden_value' => $permis_feu->data['intervenant_exterieur']['user_id'],
			'class'        => ! empty( $permis_feu->data['intervenant_exterieur']['user_id'] ) ? 'form-element-disable' : '',
			'icon'         => 'fa-search',
		);

		$eo_search->register_search( 'intervenant_exterieur', $args_permis_feu_intervenant );
	}


	public function display_step_nbr( $permis_feu ){
		$user = null;

		// if ( ! empty( $permis_feu->data['former'] ) && ! empty( $permis_feu->data['former']['user_id'] ) ) {
		// 	$user = get_userdata( $permis_feu->data['former']['user_id'] );
		// }

		$society = Society_Class::g()->get( array(
			'posts_per_page' => 1,
		), true );

		if( ! empty( $society ) ){
			$legal_display = Legal_Display_Class::g()->get( array(
				'posts_per_page' => 1,
				'post_parent'    => $society->data[ 'id' ],
			), true );
		}

		$this->register_search( $permis_feu );

		if( $permis_feu->data[ 'step' ] >= \eoxia\Config_Util::$init['digirisk']->permis_feu->steps->PERMIS_FEU_CLOSED ){
			echo '<pre>'; print_r( 'FINIE' ); echo '</pre>'; exit;
		} else {
			$file = "step-" . $permis_feu->data['step'];

			\eoxia\View_Util::exec( 'digirisk', 'permis_feu', 'start/' . $file, array(
				'permis_feu'    => Permis_Feu_Class::g()->add_information_to_permis_feu( $permis_feu ),
				'society'       => $society,
				'legal_display' => $legal_display
			) );
		}
	}

	public function next_step( $permis_feu, $nextstep ) {
		// Passes à l'étape suivante.
		$permis_feu->data['step'] = $nextstep;
		return permis_feu_Class::g()->update( $permis_feu->data );
	}


	public function save_society_information( $permis_feu, $society, $legal_display ) {
		$data = array(
			'name' => isset( $_POST[ 'outside_name' ] ) ? sanitize_text_field( $_POST[ 'outside_name' ] ) : '',
			'siret' => isset( $_POST[ 'outside_siret' ] ) ? sanitize_text_field( $_POST[ 'outside_siret' ] ) : '',
			'address' => isset( $_POST[ 'outside_address' ] ) ? sanitize_text_field( $_POST[ 'outside_address' ] ) : '',
			'postal' => isset( $_POST[ 'outside_postalcode' ] ) ? sanitize_text_field( $_POST[ 'outside_postalcode' ] ) : '',
			'town' => isset( $_POST[ 'outside_town' ] ) ? sanitize_text_field( $_POST[ 'outside_town' ] ) : '',
		);

		$permis_feu->data[ 'society_outside' ] = $data;

		$permis_feu->data['step'] = \eoxia\Config_Util::$init['digirisk']->permis_feu->steps->PERMIS_FEU_PARTICIPANT;

		return Permis_Feu_Class::g()->update( $permis_feu->data );
	}

	public function step_close_permis_feu( $permis_feu, $society, $legal_display ){
		$permis_feu->data['step'] = \eoxia\Config_Util::$init['digirisk']->permis_feu->steps->PERMIS_FEU_CLOSED;
		$permis_feu->data['is_end'] = \eoxia\Config_Util::$init['digirisk']->permis_feu->status->PERMIS_FEU_IS_ENDED;
		$permis_feu->data['unique_identifier_int'] = Permis_feu_Class::g()->get_identifier_permis_feu();
		return Permis_Feu_Class::g()->update( $permis_feu->data );
	}
}

Permis_Feu_Page_Class::g();
