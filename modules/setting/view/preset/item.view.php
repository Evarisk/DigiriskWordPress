<?php
/**
 * Affichage d'un risque à preset.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.2.9
 * @version 6.4.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<tr class="risk-row edit">

	<!-- Les champs obligatoires pour le formulaire -->
	<input type="hidden" name="action" value="edit_risk" />
	<input type="hidden" name="parent_id" value="0" />
	<input type="hidden" name="page" value="setting_risk" />
	<input type="hidden" name="risk[id]" value="<?php echo esc_attr( $danger->data['id'] ); ?>" />
	<input type="hidden" name="risk[preset]" value="true" />
	<input type="hidden" name="can_update" value="true" />

	<td class="wm130 w150">
		<?php do_shortcode( '[digi_evaluation_method_evarisk risk_id=' . $danger->data['id'] . ' type="risk"]' ); ?>
		<?php do_shortcode( '[digi_dropdown_categories_risk id="' . $danger->data['id'] . '" danger_id="' . $danger->data['id'] . '" preset="1" type="risk" display="' . ( ( $danger->data['id'] !== 0 ) ? "view" : "edit" ) . '"]' ); ?>
	</td>
	<td class="w50">
		<?php do_shortcode( '[digi_evaluation_method risk_id=' . $danger->data['id'] . ']' ); ?>
	</td>
	<td class="padding">
		<?php do_shortcode( '[digi_comment id="' . $danger->data['id'] . '" namespace="digi" type="risk_evaluation_comment" display="edit"]' ); ?>
	</td>
	<td>
		<div class="hidden">
			<div data-namespace="digirisk" data-module="risk" data-before-method="beforeSaveRisk" data-parent="risk-row" data-loader="table" class="button w50 green save action-input"><i class="icon fas fa-save"></i></div>
		</div>
	</td>
</tr>
