<?php
/**
 * La liste des jour d'arrêt.
 *
 * @author Evarisk <jimmy@evarisk.com>
 * @since 6.4.0
 * @version 6.4.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="form-element">
	<span class="form-label"><?php esc_html_e( 'Nombre jours d\'arrêts', 'digirisk' ); ?></span>
	<div><?php esc_html_e( 'Total des jours d\'arrêt:', 'digirisk' ); ?>&nbsp;<strong><?php echo esc_html( $accident->data['compiled_stopping_days'] ); ?></strong></div>
	<label class="form-field-container">
		<ul class="comment-container">
			<?php
			$i = 0;
			if ( ! empty( $accident->data['stopping_days'] ) ) :
				foreach ( $accident->data['stopping_days'] as $i => $stopping_day ) :
					?>
					<li class="comment tooltip red" aria-label="<?php esc_attr_e( 'Le champ doit être au format numérique', 'digirisk-epi' ); ?>">
						<input type="hidden" name="accident_stopping_day[<?php echo esc_attr( $i ); ?>][id]" value="<?php echo esc_attr( $stopping_day->data['id'] ); ?>" />
						<input type="hidden" name="accident_stopping_day[<?php echo esc_attr( $i ); ?>][parent_id]" value="<?php echo esc_attr( $accident->data['id'] ); ?>" />
						<input type="hidden" name="accident_stopping_day[<?php echo esc_attr( $i ); ?>][date]" value="<?php echo esc_attr( $stopping_day->data['date']['raw'] ); ?>" />
						<input class="is-number" type="text" name="accident_stopping_day[<?php echo esc_attr( $i ); ?>][content]" value="<?php echo esc_attr( $stopping_day->data['content'] ); ?>" />
						<?php echo do_shortcode( '[wpeo_upload id="' . $stopping_day->data['id'] . '" field_name="document" single="true" title="' . $accident->data['unique_identifier'] . ' : ' . __( "jour d'arrêt", 'digirisk' ) . '" model_name="/digi/Accident_Travail_Stopping_Day_Class" mime_type="application"]' ); ?>
						<span class="button delete action-delete"
									data-id="<?php echo esc_attr( $stopping_day->data['id'] ); ?>"
									data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_stopping_day' ) ); ?>"
									data-action="delete_stopping_day"
									data-message-delete="<?php echo esc_attr_e( 'Êtes-vous sur(e) de vouloir supprimer cet arrêt de travail ?', 'digirisk' ); ?>"><i class="icon far fa-times"></i></span>
					</li>
					<?php
				endforeach;
			endif;
			$i++;
			?>
			<li class="new comment tooltip red" aria-label="<?php esc_attr_e( 'Le champ doit être au format numérique', 'digirisk-epi' ); ?>">
				<input type="hidden" name="accident_stopping_day[<?php echo esc_attr( $i ); ?>][parent_id]" value="<?php echo esc_attr( $accident->data['id'] ); ?>" />
				<input type="hidden" name="accident_stopping_day[<?php echo esc_attr( $i ); ?>][date]" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" />
				<input class="is-number" type="text" name="accident_stopping_day[<?php echo esc_attr( $i ); ?>][content]" />
				<?php echo do_shortcode( '[wpeo_upload id="0" model_name="/digi/Accident_Travail_Stopping_Day_Class" field_name="document" single="true" mime_type="application"]' ); ?>
				<span data-namespace="digirisk"
							data-module="accident"
							data-before-method="checkStoppingDayData"
							data-parent="comment"
							data-accident-id="<?php echo esc_attr( $accident->data['id'] ); ?>"
							data-action="save_stopping_day"
							class="button add action-input"><i class="icon far fa-plus"></i></span>
			</li>
		</ul>
	</label>
</div>
