<?php
/**
 * Vue permettant d'exporter un modèle DigiRisk.
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.1.9
 * @version 6.5.0
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<form action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" id="digi-export-risksigns" >
	<h3><?php esc_html_e( 'Exporter les signalisations', 'digirisk' ); ?></h3>

	<div class="content">
		<input type="hidden" name="action" value="digi_export_risksigns_data" />
		<?php wp_nonce_field( 'digi_export_risksigns_data' ); ?>
		<input type="hidden" name="element_id" value="<?php echo esc_attr( $element_id ); ?>" />

		<span class="digi-export-explanation" ><?php esc_html_e( 'Réalisez une exportation de vos signalisations DigiRisk. Vous pouvez par la suite les importer dans DigiriskDolibarr.', 'digirisk' ); ?></span>

		<ul class="hidden">
			<li>
				<label><input type="checkbox" name="type_to_export[]" value="<?php echo esc_attr( Recommendation::g()->get_type() ); ?>" checked="true" /><?php esc_html_e( 'Signalisations', 'digirisk' ); ?></label>
			</li>
		</ul>
	</div>

	<button class="button blue" id="digi-export-button" ><?php esc_html_e( 'Exporter mes données', 'digirisk' ); ?></button>
</form>
