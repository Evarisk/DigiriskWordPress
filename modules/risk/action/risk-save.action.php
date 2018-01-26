<?php
/**
 * Les actions relatives à la sauvegarde des risques.
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
 * Les actions relatives à la sauvegarde des risques
 */
class Risk_Save_Action {

	/**
	 * Le constructeur appelle la méthode personnalisé: save_risk
	 *
	 * @since 0.1
	 * @version 6.2.4.0
	 */
	public function __construct() {
		add_action( 'save_risk', array( $this, 'callback_save_risk' ), 10, 1 );
	}

	/**
	 * Enregistres un risque.
	 * Ce callback est appelé après le callback callback_save_risk de risk_evaluation_action
	 *
	 * @param Risk_Model $risk Les données du risque.
	 *
	 * @since 6.0.0
	 * @version 6.5.0
	 */
	public function callback_save_risk( $risk ) {
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( isset( $risk['id'] ) ) {
			$danger = Risk_Category_Class::g()->get( array( 'id' => $risk['danger_id'] ), true );

			$image_id = 0;

			if ( ! empty( $risk['image_id'] ) ) {
				$image_id = (int) $risk['image_id'];
			}

			$risk['id']                               = (int) $risk['id'];
			$risk['title']                            = $danger->name;
			$risk['parent_id']                        = $parent_id;
			$risk['taxonomy']['digi-category-risk'][] = (int) $danger->id;
			$risk['taxonomy']['digi-method'][0]       = (int) $risk['taxonomy']['digi-method'][0];
			$risk['preset']                           = (bool) ( $risk['preset'] === 'true' ) ? true : false;
			$risk['status']                           = 'inherit';
			$risk_obj                                 = Risk_Class::g()->update( $risk );

			if ( ! $risk_obj ) {
				wp_send_json_error();
			}

			$risk_evaluation = Risk_Evaluation_Class::g()->update( array(
				'id'      => $risk_obj->current_evaluation_id,
				'post_id' => $risk_obj->id,
			) );

			if ( ! $risk_evaluation ) {
				wp_send_json_error();
			}

			$risk_obj->current_equivalence = $risk_evaluation->equivalence;
			Risk_Class::g()->update( $risk_obj );

			if ( ! empty( $image_id ) ) {
				$args_media = array(
					'id'         => $risk_obj->id,
					'file_id'    => $image_id,
					'model_name' => '\digi\Risk_Class',
				);

				\eoxia\WPEO_Upload_Class::g()->set_thumbnail( $args_media );
				$args_media['field_name'] = 'image';
				\eoxia\WPEO_Upload_Class::g()->associate_file( $args_media );
			}
		} // End if().

		do_action( 'digi_add_historic', array(
			'parent_id' => $parent_id,
			'id'        => $risk_obj->id,
			'content'   => __( 'Mise à jour du risque', 'digirisk' ) . ' ' . $risk_obj->unique_identifier,
		) );

		do_action( 'save_risk_evaluation_comment', $risk_obj, $risk );
	}
}

new Risk_Save_Action();
