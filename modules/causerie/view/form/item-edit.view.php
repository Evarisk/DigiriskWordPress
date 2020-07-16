<?php
/**
 * Edition d'une causerie
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     6.6.0
 * @version   6.6.1
 * @copyright 2018 Evarisk.
 * @package   DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="table-row edit" data-id="<?php echo esc_attr( $causerie->data['id'] ); ?>">

	<!-- Les champs obligatoires pour le formulaire -->
	<input type="hidden" name="action" value="edit_causerie" />
	<?php wp_nonce_field( 'edit_causerie' ); ?>
	<input type="hidden" name="id" value="<?php echo esc_attr( $causerie->data['id'] ); ?>" />

	<div data-title="Ref." class="table-cell table-50">
		<strong><?php echo esc_html( $causerie->data['unique_identifier'] ); ?></strong>
	</div>
	<div class="table-cell table-50">
		<?php echo do_shortcode( '[wpeo_upload id="' . $causerie->data['id'] . '" model_name="/digi/Causerie_Class" single="false" field_name="image" ]' ); ?>
	</div>
	<div class="table-cell table-50">
		<?php do_shortcode( '[digi_dropdown_categories_risk id="' . $causerie->data['id'] . '" type="causerie" display="' . ( ( 0 !== $causerie->data['id'] ) ? 'view' : 'edit' ) . '" category_risk_id="' . ( isset( $causerie->data['risk_category'] ) ? $causerie->data['risk_category']->data['id'] : 0 ) . '" preset="0"]' ); ?>
	</div>
	<div class="table-cell causerie-description">
		<input type="text" name="title" placeholder="<?php esc_html_e( 'Titre de la causerie', 'digirisk' ); ?>" value="<?php echo esc_attr( $causerie->data['title'] ); ?>" />
		<textarea rows="2" name="description" placeholder="<?php esc_html_e( 'Description de la causerie', 'digirisk' ); ?>"><?php echo esc_html( $causerie->data['content'] ); ?></textarea>
	</div>
	<div class="table-cell table-150 table-end" data-title="action">
		<?php if ( 0 !== $causerie->data['id'] ) : ?>
			<div class="action">
				<div data-parent="table-row" data-loader="wpeo-table" class="wpeo-button button-square-50 button-green save action-input"><i class="icon fas fa-save"></i></div>
			</div>
		<?php else : ?>
			<div class="action">
				<div class="wpeo-button button-event button-square-50 add action-input"
				data-loader="wpeo-table"
				data-parent="table-row"><i class="icon fa fa-plus"></i></div>
			</div>
		<?php endif; ?>
	</div>
</div>
