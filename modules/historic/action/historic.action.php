<?php
/**
 * Les actions relatives aux historiques
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.2.10
 * @version 6.5.0
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux historiques
 */
class Historic_Action {

	/**
	 * Le constructeur
	 *
	 * @since 6.2.10
	 * @version 6.2.10
	 */
	public function __construct() {
		add_action( 'digi_add_historic', array( $this, 'callback_add_historic' ), 10, 1 );
		add_action( 'wp_ajax_historic_risk', array( $this, 'callback_historic_risk' ) );
	}

	/**
	 * Enregistres dans la base de donnée les données de $data qui doivent réspecter obligatoirement le schéma suivant:
	 * - parent ID
	 * - ID
	 * - Contenu
	 *
	 * @param array $data Un tableau respectant le format ci-dessus.
	 *
	 * @return void
	 *
	 * @since 6.2.10
	 * @version 6.2.10
	 */
	public function callback_add_historic( $data ) {
		if ( ! empty( $data['parent_id'] ) && ! empty( $data['id'] ) && ! empty( $data['content'] ) ) {
			$data['date'] = current_time( 'mysql' );
			update_post_meta( $data['parent_id'], \eoxia\Config_Util::$init['digirisk']->historic->key_historic, $data );
		}
	}

	/**
	 * Récupères toutes les cotations faites pour le risque et les affiches.
	 *
	 * @return void
	 *
	 * @since 6.2.10
	 * @version 6.5.0
	 */
	public function callback_historic_risk() {
		check_ajax_referer( 'historic_risk' );

		$risk_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $risk_id ) ) {
			wp_send_json_error();
		}

		$evaluations = Risk_Evaluation_Class::g()->get( array(
			'post_id' => $risk_id,
			'orderby' => 'comment_ID',
		) );

		if ( ! empty( $evaluations ) ) {
			foreach ( $evaluations as &$evaluation ) {
				$evaluation->comments = Risk_Evaluation_Comment_Class::g()->get( array(
					'post_id' => $risk_id,
					'parent'  => $evaluation->id,
				) );
			}
		}

		ob_start();
		\eoxia\View_Util::exec( 'digirisk', 'historic', 'risk/main', array(
			'evaluations' => $evaluations,
		) );
		$view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'digirisk',
			'module'           => 'historic',
			'callback_success' => 'openedHistoricRiskPopup',
			'view'             => $view,
		) );
	}
}

new Historic_Action();
