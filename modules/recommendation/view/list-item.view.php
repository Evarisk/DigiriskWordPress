<?php
/**
 * Affichage d'une recommendation
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.2.1
 * @version 6.4.4
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<tr class="recommendation-row" data-id="<?php echo esc_attr( $recommendation->id ); ?>">
	<td class="padding">
		<span><strong><?php echo esc_html( $recommendation->unique_identifier ); ?></span></strong>
	</td>
	<td>
		<?php do_shortcode( '[dropdown_recommendation id="' . $recommendation->id . '" type="recommendation" display="view"]' ); ?>
	</td>
	<td class="w50">
		<?php do_shortcode( '[wpeo_upload id="' . $recommendation->id . '" model_name="/digi/' . $recommendation->get_class() . '" field_name="image" title="' . $recommendation->unique_identifier . '" ]' ); ?>
	</td>
	<td class="padding">
		<?php do_shortcode( '[digi_comment id="' . $recommendation->id . '" namespace="digi" type="recommendation_comment" display="view"]' ); ?>
	</td>
	<td>
		<div class="action grid-layout w2">
			<!-- Editer une recommendation -->
			<div 	class="button w50 light edit action-attribute"
						data-id="<?php echo esc_attr( $recommendation->id ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'ajax_load_recommendation' ) ); ?>"
						data-loader="table"
						data-action="load_recommendation"><i class="icon fas fa-pencil"></i></div>

			<div 	class="button w50 light delete action-delete"
						data-id="<?php echo esc_attr( $recommendation->id ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'ajax_delete_recommendation' ) ); ?>"
						data-action="delete_recommendation"><i class="icon far fa-times"></i></div>
		</div>
	</td>
</tr>
