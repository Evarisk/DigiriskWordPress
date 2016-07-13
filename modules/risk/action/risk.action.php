<?php
/**
* @TODO : A détailler
*
* @author Jimmy Latour <jimmy.latour@gmail.com>
* @version 0.1
* @copyright 2015-2016 Eoxia
* @package risk
* @subpackage action
*/

if ( !defined( 'ABSPATH' ) ) exit;

class risk_action {
	/**
	* Le constructeur appelle une action personnalisée:
	* callback_display_risk
	* Il appelle également les actions ajax suivantes:
	* wp_ajax_wpdigi-delete-risk
	* wp_ajax_wpdigi-load-risk
	* wp_ajax_wpdigi-edit-risk
	* wp_ajax_delete_comment
	*/
	public function __construct() {
		// Remplacé les - en _
		add_action( 'display_risk', array( $this, 'callback_display_risk' ) );
		add_action( 'wp_ajax_wpdigi-delete-risk', array( $this, 'ajax_delete_risk' ) );
		add_action( 'wp_ajax_wpdigi-load-risk', array( $this, 'ajax_load_risk' ) );
		add_action( 'wp_ajax_wpdigi-edit-risk', array( $this, 'ajax_edit_risk' ) );
		add_action( 'wp_ajax_delete_comment', array( $this, 'callback_delete_comment' ) );
	}

	/**
  * Enregistres un risque.
	* Ce callback est le dernier de l'action "save_risk"
  *
	* int $_POST['element_id'] L'ID de l'élement ou le risque sera affecté
	*
	* @param array $_POST Les données envoyées par le formulaire
  */
	public function callback_display_risk() {
		$element_id = !empty( $_POST['element_id'] ) ? (int) $_POST['element_id'] : 0;
		if ( $element_id === 0 ) {
			wp_send_json_error( array( 'file' => __FILE__, 'line' => __LINE__ ) );
		}

		ob_start();
		// risk_class::get()->display_risk_list( $element_id );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	/**
	* Supprimes un risque
	*
	* int $_POST['risk_id'] L'ID du risque
	*
	* @param array $_POST Les données envoyées par le formulaire
	*/
	public function ajax_delete_risk() {
		// todo : global
		if ( 0 === (int)$_POST['risk_id'] )
			wp_send_json_error( array( 'error' => __LINE__, ) );
		else
			$risk_id = (int)$_POST['risk_id'];

		$global = sanitize_text_field( $_POST['global'] );

		wpdigi_utils::check( 'ajax_delete_risk_' . $risk_id );

		global $wpdigi_risk_ctr;
		global ${$global};

		$risk = $wpdigi_risk_ctr->show( $risk_id );

		if ( empty( $risk ) )
			wp_send_json_error( array( 'error' => __LINE__ ) );

		$workunit = ${$global}->show( $risk->parent_id );

		if ( empty( $workunit ) )
			wp_send_json_error( array( 'error' => __LINE__ ) );

		if ( FALSE === $key = array_search( $risk_id, $workunit->option['associated_risk'] ) )
			wp_send_json_error( array( 'error' => __LINE__ ) );

		unset( $workunit->option['associated_risk'][$key] );

		$risk->status = 'trash';

		$wpdigi_risk_ctr->update( $risk );
		${$global}->update( $workunit );

		wp_send_json_success();
	}

	/**
	* Charges un risque
	*
	* int $_POST['risk_id'] L'ID du risque
	*
	* @param array $_POST Les données envoyées par le formulaire
	*/
	public function ajax_load_risk() {
		// todo : global
		if ( 0 === (int)$_POST['risk_id'] )
			wp_send_json_error( array( 'error' => __LINE__, ) );
		else
			$risk_id = (int)$_POST['risk_id'];

		$global = sanitize_text_field( $_POST['global'] );

		wpdigi_utils::check( 'ajax_load_risk_' .$risk_id );

		$risk_definition = risk_class::get()->get_risk( $risk_id );

		$element = society_class::get()->show_by_type( $risk_definition->parent_id );

		foreach ( $risk_definition->comment as &$comment ) {
			$comment->date = explode( ' ', $comment->date );
			$comment->date = $comment->date[0];

		}
		unset( $comment );

		ob_start();
		require( RISK_VIEW_DIR . 'list-item-edit.php' );
		$template = ob_get_clean();

		wp_send_json_success( array( 'template' => $template ) );
	}

	/**
	* Supprimes un commentaire sur un risque (met le status du commentaire à "trash")
	*
	* int $_POST['id'] L'ID du commentaire
	* int $_POST['risk_id'] L'ID du risque
	*
	* @param array $_POST Les données envoyées par le formulaire
	*/
	public function callback_delete_comment() {
		$id = !empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$risk_id = !empty( $_POST['risk_id'] ) ? (int) $_POST['risk_id'] : 0;

		check_ajax_referer( 'ajax_delete_risk_comment_' . $risk_id . '_' . $id );

		$risk_evaluation_comment = risk_evaluation_comment_class::get()->show( $id );
		$risk_evaluation_comment->status = 'trash';
		risk_evaluation_comment_class::get()->update( $risk_evaluation_comment );

		wp_send_json_success();
	}
}

new risk_action();
