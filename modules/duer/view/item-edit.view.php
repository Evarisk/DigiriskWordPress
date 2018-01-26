<?php
/**
 * Gestion du formulaire pour générer un DUER
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.1.9
 * @version 6.5.0
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<tr>
	<input type="hidden" name="action" value="generate_duer" />
	<?php wp_nonce_field( 'callback_ajax_generate_duer' ); ?>
	<input type="hidden" name="element_id" value="<?php echo esc_attr( $element_id ); ?>" />

	<td class="padding"></td>
	<td class="padding">
		<div class="group-date form-element <?php echo esc_attr( ! empty( $element->document_meta['dateDebutAudit']['raw'] ) ? 'active' : '' ); ?>">
			<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none;" name="dateDebutAudit" value="<?php echo esc_attr( $element->document_meta['dateDebutAudit']['raw'] ); ?>" />
			<input type="text" class="date" placeholder="04/01/2017" value="<?php echo esc_html( $element->document_meta['dateDebutAudit']['rendered']['date'] ); ?>" />
		</div>
	</td>
	<td class="padding">
		<div class="group-date form-element <?php echo esc_attr( ! empty( $element->document_meta['dateFinAudit']['raw'] ) ? 'active' : '' ); ?>">
			<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none;" name="dateFinAudit" value="<?php echo esc_attr( $element->document_meta['dateFinAudit']['raw'] ); ?>" />
			<input type="text" class="date" placeholder="04/01/2017" value="<?php echo esc_html( $element->document_meta['dateFinAudit']['rendered']['date'] ); ?>" />
		</div>
	</td>

	<td class="padding">
		<textarea class="hidden textarea-content-destinataire-duer" name="destinataireDUER"><?php echo esc_html( $element->document_meta['destinataireDUER'] ); ?></textarea>
		<span data-parent="main-container"
					data-target="popup"
					data-cb-namespace="digirisk"
					data-cb-object="DUER"
					data-cb-func="fill_textarea_in_popup"
					data-title="Édition du destinataire"
					data-src="destinataire-duer"
					class="open-popup button grey radius w30 span-content-destinataire-duer"><i class="float-icon fa fa-pencil animated"></i><span class="dashicons dashicons-admin-users"></span></span>
	</td>

	<td class="padding">
		<textarea class="hidden textarea-content-methodology" name="methodologie"><?php echo esc_html( $element->document_meta['methodologie'] ); ?></textarea>
		<span data-parent="main-container"
					data-target="popup"
					data-cb-namespace="digirisk"
					data-cb-object="DUER"
					data-cb-func="fill_textarea_in_popup"
					data-title="Édition de la méthodologie"
					data-src="methodology"
					class="open-popup button grey w30 radius span-content-methodology"><i class="float-icon fa fa-pencil animated"></i><span class="dashicons dashicons-search"></span></span>
	</td>

	<td class="padding">
		<textarea class="hidden textarea-content-sources" name="sources"><?php echo esc_html( $element->document_meta['sources'] ); ?></textarea>
		<span data-parent="main-container"
					data-target="popup"
					data-cb-namespace="digirisk"
					data-cb-object="DUER"
					data-cb-func="fill_textarea_in_popup"
					data-title="Édition de la source"
					data-src="sources"
					class="open-popup button grey radius w30 span-content-sources"><i class="float-icon fa fa-pencil animated"></i><span class="dashicons dashicons-admin-links"></span></span>
	</td>

	<td class="padding">
		<textarea class="hidden textarea-content-dispo-des-plans" name="dispoDesPlans"><?php echo esc_html( $element->document_meta['dispoDesPlans'] ); ?></textarea>
		<span data-parent="main-container"
					data-target="popup"
					data-cb-namespace="digirisk"
					data-cb-object="DUER"
					data-cb-func="fill_textarea_in_popup"
					data-title="Édition de la localisation"
					data-src="dispo-des-plans"
					class="open-popup button grey radius w30 span-content-dispo-des-plans"><i class="float-icon fa fa-pencil animated"></i><span class="dashicons dashicons-location"></span></span>
	</td>

	<td class="padding">
		<textarea class="hidden textarea-content-notes-importantes" name="remarqueImportante"><?php echo esc_html( $element->document_meta['remarqueImportante'] ); ?></textarea>
		<span data-parent="main-container"
					data-target="popup"
					data-cb-namespace="digirisk"
					data-cb-object="DUER"
					data-cb-func="fill_textarea_in_popup"
					data-title="Édition de la note importante"
					data-src="notes-importantes"
					class="open-popup button grey radius w30 span-content-notes-importantes"><i class="float-icon fa fa-pencil animated"></i><span class="dashicons dashicons-clipboard"></span></span>
	</td>

	<td>
		<div class="action w50">
			<div class="open-popup add button blue w50"
					data-id="<?php echo esc_attr( $element_id ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'display_societies_duer' ) ); ?>"
					data-parent="main-container"
					data-target="popup"
					data-cb-namespace="digirisk"
					data-cb-object="DUER"
					data-cb-func="popup_for_generate_DUER"
					data-title="Génération du DUER">
					<i class="icon fa fa-print"></i>
				</div>
		</div>
	</td>

</tr>
