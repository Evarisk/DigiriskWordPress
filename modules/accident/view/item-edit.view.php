<?php
/**
 * Edition d'un accident
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.3.0
 * @version 6.5.0
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $eo_search; ?>
<div class="col advanced" data-id="<?php echo esc_attr( $accident->data['id'] ); ?>">
	<input type="hidden" name="action" value="edit_accident" />
	<?php wp_nonce_field( 'edit_accident' ); ?>
	<input type="hidden" name="accident[id]" value="<?php echo esc_attr( $accident->data['id'] ); ?>" />
	<input type="hidden" name="accident[parent_id]" value="<?php echo esc_attr( $accident->data['parent_id'] ); ?>" />
	<div class="col">
		<div data-title="Ref." class="cell padding w150">
			<ul>
				<li><strong><?php echo esc_attr( $accident->data['unique_identifier'] ); ?></strong></li>
				<li><?php echo esc_attr( $accident->data['registration_date_in_register']['rendered']['date'] ); ?></li>
			</ul>
		</div>
		<div data-title="<?php esc_attr_e( 'Nom., Prénom.. victime', 'digirisk' ); ?>" class="cell padding w200"><?php echo ! empty( $accident->data['victim_identity']->data['id'] ) ? User_Digi_Class::g()->element_prefix . $accident->data['victim_identity']->data['id'] . ' ' . $accident->data['victim_identity']->data['login'] : ''; ?></div>
		<div data-title="<?php esc_attr_e( 'Date et heure', 'digirisk' ); ?>" class="cell padding w150"><?php echo esc_html( $accident->data['accident_date']['rendered']['date_time'] ); ?></div>
		<div data-title="<?php esc_attr_e( 'Lieu', 'digirisk' ); ?>" class="cell padding w200"><?php echo esc_attr( $accident->data['place']->data['unique_identifier'] . ' ' . $accident->data['place']->data['title'] ); ?></div>
		<div data-title="<?php esc_attr_e( 'Circonstances', 'digirisk' ); ?>" class="cell padding"><?php do_shortcode( '[digi_comment id="' . $accident->data['id'] . '" namespace="eoxia" type="comment" display="view" display_date="false" display_user="false"]' ); ?></div>
		<div data-title="<?php esc_attr_e( 'Indicateurs', 'digirisk' ); ?>" class="cell padding w70"><span class="number-field"><?php echo esc_attr( $accident->data['number_field_completed'] ); ?></span>/13</div>
		<div data-title="<?php esc_attr_e( 'Actions', 'digirisk' ); ?>" class="cell w150">
			<div class="action">
				<div data-parent="advanced[data-id='<?php echo esc_attr( $accident->data['id'] ); ?>']" data-loader="flex-table" data-namespace="digirisk" data-module="accident" data-before-method="checkAllData" class="wpeo-button button-square-50 button-green save action-input"><i class="button-icon fas fa-save"></i></div>
			</div>
		</div>
	</div>

	<div class="advanced">
		<div class="wpeo-form">
			<div class="wpeo-gridlayout padding grid-3">
				<div class="form-element">
					<span class="form-label"><?php esc_html_e( 'Nom., Prénom.. victime', 'digirisk' ); ?></span>
					<div class="form-field-container">
						<?php $eo_search->display( 'accident_user' ); ?>
					</div>
				</div>

				<div class="form-element group-date">
					<span class="form-label"><?php esc_html_e( 'Date et heure', 'digirisk' ); ?></span>
					<label class="form-field-container">
						<span class="form-field-icon-prev"><i class="fal fa-calendar-alt"></i></span>
						<input type="hidden" class="mysql-date" name="accident[accident_date]" value="<?php echo esc_attr( $accident->data['accident_date']['raw'] ); ?>" />
						<input type="text" class="form-field date" value="<?php echo esc_html( $accident->data['accident_date']['rendered']['date_time'] ); ?>" />
					</label>
				</div>

				<div class="form-element">
					<span class="form-label"><?php esc_html_e( 'Lieu', 'digirisk' ); ?></span>
					<div class="form-field-container">
						<?php $eo_search->display( 'accident_post' ); ?>
					</div>
				</div>
			</div>

			<div class="form-element">
				<span class="form-label"><?php esc_html_e( 'Circonstances détaillées', 'digirisk' ); ?></span>
				<label class="form-field-container">
					<?php do_shortcode( '[digi_comment id="' . $accident->data['id'] . '" namespace="eoxia" type="comment" display="edit" display_date="false" display_user="false"]' ); ?>
				</label>
			</div>

			<div class="wpeo-gridlayout padding grid-2">
				<?php
				\eoxia\View_Util::exec( 'digirisk', 'accident', 'list-stopping-day', array(
					'accident' => $accident,
				) );
				?>

				<div class="investigation-group">
					<div class="form-element">
						<span class="form-label"><?php esc_html_e( 'Enquête accident', 'digirisk' ); ?></span>
						<label class="form-field-container">
							<div class="form-field-inline">
								<input id="have_investigation" class="form-field" type="checkbox" <?php echo $accident->data['have_investigation'] ? 'checked' : ''; ?> name="accident[have_investigation]" />
								<label for="have_investigation"><?php esc_html_e( 'Réaliser une enquête accident', 'digirisk' ); ?></label>
							</div>
						</label>
					</div>

					<div class="investigation-media <?php echo ( ! $accident->data['have_investigation'] ) ? 'hidden' : ''; ?>">
						<?php echo do_shortcode( '[wpeo_upload id="' . $accident->data['id'] . '" model_name="/digi/Accident_Class" single="true" mime_type="application" field_name="accident_investigation_id" custom_class="investigation" title="' . $accident->data['unique_identifier'] . ' : ' . __( 'enquête accident', 'digirisk' ) . '"]' ); ?>
					</div>
				</div>
			</div>

			<div class="wpeo-gridlayout padding grid-2">
				<div class="form-element">
					<span class="form-label"><?php esc_html_e( 'Siège des lésions (préciser droite ou gauche)', 'digirisk' ); ?></span>
					<label class="form-field-container">
						<input type="text" class="form-field" id="location_of_lesions" name="accident[location_of_lesions]" value="<?php echo esc_attr( $accident->data['location_of_lesions'] ); ?>">
					</label>
				</div>

				<div class="form-element">
					<span class="form-label"><?php esc_html_e( 'Nature des lésions', 'digirisk' ); ?></span>
					<label class="form-field-container">
						<input class="form-field" type="text" id="nature_of_lesions" name="accident[nature_of_lesions]" value="<?php echo esc_attr( $accident->data['nature_of_lesions'] ); ?>">
					</label>
				</div>
			</div>

			<div class="wpeo-gridlayout padding grid-2">
				<div class="form-element">
					<span class="form-label"><?php esc_html_e( 'Nom et adresse des témoins', 'digirisk' ); ?></span>
					<label class="form-field-container">
						<textarea class="form-field" id="name_witnesses" name="accident[name_and_address_of_witnesses]"><?php echo $accident->data['name_and_address_of_witnesses']; ?></textarea>
					</label>
				</div>

				<div class="form-element">
					<span class="form-label"><?php esc_html_e( 'Nom et adresse des tiers impliqués', 'digirisk' ); ?></span>
					<label class="form-field-container">
						<textarea class="form-field" id="name_and_address_of_third_parties_involved" name="accident[name_and_address_of_third_parties_involved]"><?php echo $accident->data['name_and_address_of_third_parties_involved']; ?></textarea>
					</label>
				</div>
			</div>

			<div class="wpeo-gridlayout padding grid-2">
				<div class="form-element">
					<span class="form-label">
						<?php esc_html_e( 'Signature du donneur de soin', 'digirisk' ); ?>
						<span class="canvas-eraser fa-layers fa-fw">
							<i class="fas fa-circle" data-fa-transform="grow-8"></i>
							<i class="fa-inverse fas fa-eraser" data-fa-transform="shrink-2"></i>
						</span>
					</span>
					<label class="form-field-container">
						<input type="hidden" name="signature_of_the_caregiver" />
						<input type="hidden" class="url" value="<?php echo ! empty( $accident->data['associated_document_id']['signature_of_the_caregiver_id'][0] ) ? esc_attr( wp_get_attachment_url( $accident->data['associated_document_id']['signature_of_the_caregiver_id'][0] ) ) : ''; ?>" />
						<canvas></canvas>
					</label>
				</div>
				<div class="form-element">
					<span class="form-label">
						<?php esc_html_e( 'Signature de la victime', 'digirisk' ); ?>
						<span class="canvas-eraser fa-layers fa-fw">
							<i class="fas fa-circle" data-fa-transform="grow-8"></i>
							<i class="fa-inverse fas fa-eraser" data-fa-transform="shrink-2"></i>
						</span>
					</span>
					<label class="form-field-container">
						<input type="hidden" name="signature_of_the_victim" />
						<input type="hidden" class="url" value="<?php echo ! empty( $accident->data['associated_document_id']['signature_of_the_victim_id'][0] ) ? esc_attr( wp_get_attachment_url( $accident->data['associated_document_id']['signature_of_the_victim_id'][0] ) ) : ''; ?>" />
						<canvas></canvas>
					</label>
				</div>
			</div>

			<div class="form-element">
				<span class="form-label"><?php esc_html_e( 'Observations', 'digirisk' ); ?></span>
				<label class="form-field-container">
					<textarea class="form-field" id="observation" name="accident[observation]"><?php echo $accident->data['observation']; ?></textarea>
				</label>
			</div>



			<div data-parent="advanced[data-id='<?php echo esc_attr( $accident->data['id'] ); ?>']" data-loader="flex-table" data-namespace="digirisk" data-module="accident" data-before-method="checkAllData" class="wpeo-button button-square-50 button-green save action-input alignright"><i class="button-icon fas fa-save"></i></div>
		</div>
	</div>
</div>
