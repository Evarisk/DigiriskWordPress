<?php
/**
 * Template pour les informations de la société.
 *
 * @author    Evarisk <dev@evarisk.com>
 * @copyright (c) 2006-2018 Evarisk <dev@evarisk.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   DigiRisk\Templates
 *
 * @since     7.2.0
 */

namespace digi;

defined( 'ABSPATH' ) || exit; ?>

<h3><?php esc_html_e( 'Analyse des risques', 'digirisk' ); ?></h3>

<?php if ( ( $type != 'digi-group' ) && ( $type != 'digi-workunit' ) ) : ?>
	<h4><?php esc_html_e( 'Analyse par nombre de risques et cotations', 'digirisk' ); ?></h4>

	<div class="wpeo-table table-flex table-4">
		<div class="table-row table-header">
			<div class="table-cell"></div>
			<div class="table-cell" data-title="Nombre de risques"><?php esc_html_e( 'Nombre de risques', 'digirisk' ); ?></div>
			<div class="table-cell" data-title="Somme des cotations"><?php esc_html_e( 'Sommes des cotations', 'digirisk' ); ?></div>
			<div class="table-cell" data-title="Somme des cotations"><?php esc_html_e( 'Moyenne des cotations', 'digirisk' ); ?></div>
		</div>

		<div class="table-row">
			<div class="table-cell" data-title="Nombre de risques sur le DU précédent"><?php esc_html_e( 'Nombre de risque sur le DU précédent', 'digirisk' ); ?></div>
			<div class="table-cell"><?php echo $old_duer_info['total_risk']; ?></div>
			<div class="table-cell"><?php echo $old_duer_info['quotation_total']; ?></div>
			<div class="table-cell"><?php echo round( $old_duer_info['average'] ); ?></div>
		</div>

		<div class="table-row">
			<div class="table-cell" data-title="Nombre de risque sur le DU actuel"><?php esc_html_e( 'Nombre de risque sur le DU actuel', 'digirisk' ); ?></div>
			<div class="table-cell"><?php echo $current_duer_info['total_risk']; ?></div>
			<div class="table-cell"><?php echo $current_duer_info['quotation_total']; ?></div>
			<div class="table-cell"><?php echo round( $current_duer_info['average'] ); ?></div>
		</div>

		<div class="table-row">
			<div class="table-cell" data-title="Progression"><?php esc_html_e( 'Progression', 'digirisk' ); ?></div>
			<div class="table-cell"><?php echo $diff_info['total_risk']; ?></div>
			<div class="table-cell"><?php echo $diff_info['quotation_total']; ?></div>
			<div class="table-cell"><?php echo round( $diff_info['average'] ); ?></div>
		</div>
	</div>

	<h4><?php esc_html_e( 'Analyse des risques par cotation', 'digirisk' ); ?></h4>

	<div class="wpeo-table table-flex table-5">
		<div class="table-row table-header">
			<div class="table-cell"><?php esc_html_e( 'Cotation', 'digirisk' ); ?></div>
			<div class="table-cell" data-title="<?php esc_attr_e( 'Gris', 'digirisk' ); ?>"><?php esc_html_e( 'Gris', 'digirisk' ); ?></div>
			<div class="table-cell" data-title="<?php esc_attr_e( 'Orange', 'digirisk' ); ?>"><?php esc_html_e( 'Orange', 'digirisk' ); ?></div>
			<div class="table-cell" data-title="<?php esc_attr_e( 'Rouge', 'digirisk' ); ?>"><?php esc_html_e( 'Rouge', 'digirisk' ); ?></div>
			<div class="table-cell" data-title="<?php esc_attr_e( 'Noir', 'digirisk' ); ?>"><?php esc_html_e( 'Noir', 'digirisk' ); ?></div>
		</div>

		<div class="table-row">
			<div class="table-cell" data-title="<?php esc_attr_e( 'Nombre de risques sur le DU précédent', 'digirisk' ); ?>"><?php esc_html_e( 'Nombre DU précédent', 'digirisk' ); ?></div>
			<div class="table-cell"><?php echo $old_duer_info['number_risk'][1]; ?></div>
			<div class="table-cell"><?php echo $old_duer_info['number_risk'][2]; ?></div>
			<div class="table-cell"><?php echo $old_duer_info['number_risk'][3]; ?></div>
			<div class="table-cell"><?php echo $old_duer_info['number_risk'][4]; ?></div>
		</div>

		<div class="table-row">
			<div class="table-cell" data-title="<?php esc_attr_e( 'Nombre de risques sur le DU précédent', 'digirisk' ); ?>"><?php esc_html_e( 'Nombre DU actuel', 'digirisk' ); ?></div>
			<div class="table-cell"><?php echo $current_duer_info['number_risk'][1]; ?></div>
			<div class="table-cell"><?php echo $current_duer_info['number_risk'][2]; ?></div>
			<div class="table-cell"><?php echo $current_duer_info['number_risk'][3]; ?></div>
			<div class="table-cell"><?php echo $current_duer_info['number_risk'][4]; ?></div>
		</div>

		<div class="table-row">
			<div class="table-cell" data-title="<?php esc_attr_e( 'Nombre de risques sur le DU précédent', 'digirisk' ); ?>"><?php esc_html_e( 'Progression', 'digirisk' ); ?></div>
			<div class="table-cell"><?php echo $diff_info['number_risk'][1]; ?></div>
			<div class="table-cell"><?php echo $diff_info['number_risk'][2]; ?></div>
			<div class="table-cell"><?php echo $diff_info['number_risk'][3]; ?></div>
			<div class="table-cell"><?php echo $diff_info['number_risk'][4]; ?></div>
		</div>
	</div>
<?php endif; ?>

<h4><?php esc_html_e( 'Analyse des risques par famille de risque', 'digirisk' ); ?></h4>

<div class="wpeo-table table-flex table-5">
	<div class="table-row table-header">
		<div class="table-cell table-75"><?php esc_html_e( 'Famille', 'digirisk' ); ?></div>
		<div class="table-cell" data-title="<?php esc_attr_e( 'Gris', 'digirisk' ); ?>"><?php esc_html_e( 'Gris', 'digirisk' ); ?></div>
		<div class="table-cell" data-title="<?php esc_attr_e( 'Orange', 'digirisk' ); ?>"><?php esc_html_e( 'Orange', 'digirisk' ); ?></div>
		<div class="table-cell" data-title="<?php esc_attr_e( 'Rouge', 'digirisk' ); ?>"><?php esc_html_e( 'Rouge', 'digirisk' ); ?></div>
		<div class="table-cell" data-title="<?php esc_attr_e( 'Noir', 'digirisk' ); ?>"><?php esc_html_e( 'Noir', 'digirisk' ); ?></div>
	</div>

	<?php
	if ( ! empty( $risks_categories ) ) :
		foreach ( $risks_categories as $risk_category ) :
			?>
			<div class="table-row">
				<div class="table-cell table-75">
					<div class="wpeo-tooltip-event hover" aria-label="<?php echo esc_attr( $risk_category->data['name'] ); ?>">
						<?php echo wp_get_attachment_image( $risk_category->data['thumbnail_id'], 'thumbnail', false ); ?>
					</div>
				</div>

				<div class="table-cell"><?php echo esc_html( $risk_category->data['level1'] ); ?></div>
				<div class="table-cell"><?php echo esc_html( $risk_category->data['level2'] ); ?></div>
				<div class="table-cell"><?php echo esc_html( $risk_category->data['level3'] ); ?></div>
				<div class="table-cell"><?php echo esc_html( $risk_category->data['level4'] ); ?></div>
			</div>
		<?php
		endforeach;
	endif;
	?>
</div>
