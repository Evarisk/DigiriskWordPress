<?php
/**
 * Information du maitre d'oeuvre (utilisateur wordpress) pour compléter les informations du plan de prévention
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     6.6.0
 * @version   6.6.0
 * @copyright 2018 Evarisk.
 * @package   DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $eo_search; ?>

<section class="wpeo-gridlayout padding grid-4" style="margin-bottom: 10px;">
	<input type='hidden' name="prevention_id" value="<?php echo esc_attr( $prevention->data['id'] ); ?>">
	<div class="wpeo-form">
		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'Nom', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<span class="form-field-icon-prev"><i class="fas fa-user"></i></span>
				<input type="text" name="intervenant-name" class="form-field" value="">
			</label>
		</div>
	</div>

	<div class="wpeo-form">
		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'Prénom', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<span class="form-field-icon-prev"><i class="fas fa-user"></i></span>
				<input type="text" name="intervenant-lastname" class="form-field" value="">
			</label>
		</div>
	</div>

	<div class="wpeo-form">
		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'Portable', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<span class="form-field-icon-prev"><i class="fas fa-mobile-alt"></i></span>
				<input type="text" name="intervenant-phone" class="form-field" value="">
			</label>
		</div>
	</div>

	<div class="wpeo-form">
		<div class="form-element signature-info-element">
			<span class="form-label"><?php esc_html_e( 'Signature', 'task-manager' ); ?></span>
			<?php if ( empty( $prevention->data['intervenant_exterieur']['signature_id'] ) ) : ?>
				<input type="hidden" name="intervenant-exterieur-signature">
				<div class="signature w50 padding">
					<div class="wpeo-button button-blue wpeo-modal-event"
						data-parent="form-element"
						data-target="modal-signature">
						<span><?php esc_html_e( 'Signer', 'digirisk' ); ?></span>
					</div>
					<?php
					\eoxia\View_Util::exec( 'digirisk', 'prevention_plan', 'start/step-1-signature-modal', array(
						'action' => 'prevention_save_signature_maitre_oeuvre',
						'parent_element' => 'information-intervenant-exterieur',
					) );
					?>
				</div>
			<?php else : ?>
				<input type="hidden" name="intervenant-exterieur-signature" value="ok">
				<div>
					<input type="hidden" name="intervenant-signature">
					<img class="signature" src="<?php echo esc_attr( wp_get_attachment_url( $prevention->data['maitre_oeuvre']['signature_id'] ) ); ?>">
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>