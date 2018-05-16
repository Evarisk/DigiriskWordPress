<?php
/**
 * Les actions relatives à la sauvegarde des risques.
 *
 * @author Evarisk <dev@evarisk.com>
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
 * Les actions relatives à la sauvegarde des risques
 */
class Risk_Save_Action {

	/**
	 * Le constructeur appelle la méthode personnalisé: save_risk
	 *
	 * @since 6.0.0
	 * @version 6.5.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_edit_risk', array( $this, 'callback_edit_risk' ) );
	}

	/**
	 * Enregistres un risque.
	 *
	 * @since 6.0.0
	 * @version 6.5.0
	 */
	public function callback_edit_risk() {
		check_ajax_referer( 'edit_risk' );

		$id                   = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$parent_id            = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$risk_category_id     = ! empty( $_POST['risk_category_id'] ) ? (int) $_POST['risk_category_id'] : 0;
		$evaluation_method_id = ! empty( $_POST['evaluation_method_id'] ) ? (int) $_POST['evaluation_method_id'] : 0;
		$comments             = ! empty( $_POST['list_comment'] ) ? (array) $_POST['list_comment'] : array();

		if ( empty( $parent_id ) ) {
			wp_send_json_error();
		}

		$risk_data = array(
			'id'        => $id,
			'parent_id' => $parent_id,
			'title'     => 'Chocolat',
		);

		$risk = Risk_Class::g()->save( $risk_data, $risk_category_id, $evaluation_method_id );

		if ( ! empty( $risk->errors ) ) {
			wp_send_json_error( $risk->errors );
		}

		$evaluation_method_variables = ! empty( $_POST['evaluation_variables'] ) ? wp_unslash( (string) $_POST['evaluation_variables'] ) : '';
		$evaluation_method_variables = json_decode( $evaluation_method_variables, true );

		$risk_evaluation = Risk_Evaluation_Class::g()->save( $risk->data['id'], $evaluation_method_id, $evaluation_method_variables );
		Risk_Evaluation_Comment_Class::g()->save( $risk, $comments );

		$risk->data['current_equivalence'] = $risk_evaluation->data['equivalence'];

		Risk_Class::g()->update( $risk->data );

		wp_send_json_success( array() );
	}
}

new Risk_Save_Action();
