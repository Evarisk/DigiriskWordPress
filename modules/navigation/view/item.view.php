<?php
/**
 * Affiches une unité de travail dans la navigation
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.0.0
 * @version 6.5.0
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="unit <?php echo ( $establishment->id === $selected_establishment_id ) ? 'active' : ''; echo ( \eoxia\Post_Util::is_parent( $establishment->id, $selected_establishment_id ) ) ? 'toggled' : ''; ?>"
	data-id="<?php echo esc_attr( $establishment->id ); ?>">
	<div class="unit-container">

		<?php if ( Workunit_Class::g()->get_type() !== $establishment->type && \eoxia\Post_Util::have_child( $establishment->id, array( 'digi-group', 'digi-workunit' ) ) ) : ?>
			<div class="toggle-unit"><span class="icon"></span></div>
		<?php else : ?>
			<div class="spacer"><span class="icon"></span></div>
		<?php endif; ?>
		<?php do_shortcode( '[wpeo_upload id="' . $establishment->id . '" model_name="/digi/' . $establishment->get_class() . '" single="false" field_name="image" title="' . $establishment->modified_unique_identifier . ' - ' . $establishment->title . '" ]' ); ?>
		<div class="title action-attribute"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_right_container' ) ); ?>"
				data-action="load_society"
				data-establishment-id="<?php echo esc_attr( $establishment->id ); ?>"
				data-loader="digirisk-wrap"
				data-namespace="digirisk"
				data-module="navigation"
				data-before-method="setUnitActive">
			<span class="title-container">
				<span class="ref"><?php echo esc_html( $establishment->modified_unique_identifier ); ?></span>
				<span class="name"><?php echo esc_html( $establishment->title ); ?></span>
			</span>
		</div>
		<?php
		if ( 'digi-group' === $establishment->type ) :
		?>
			<div class="add-container">
				<div class="button w50 blue tooltip hover" aria-label="<?php echo esc_attr( 'Ajouter groupement', 'digirisk' ); ?>" data-type="Group_Class"><span class="icon dashicons dashicons-admin-multisite"></span><span class="button-add animated fa fa-plus-circle"></span></div>
				<div class="button w50 blue tooltip hover" aria-label="<?php echo esc_attr( 'Ajouter unité', 'digirisk' ); ?>" data-type="Workunit_Class"><span class="icon dashicons dashicons-admin-home"></span><span class="button-add animated fa fa-plus-circle"></span></div>
			</div>
			<div class="mobile-add-container toggle option" data-parent="toggle" data-target="content">
				<i class="action fa fa-ellipsis-v"></i>
				<ul class="content">
					<li class="item" data-type="Group_Class"><i class="icon dashicons dashicons-admin-multisite"></i><?php echo esc_attr( 'Ajouter groupement', 'digirisk' ); ?></li>
					<li class="item" data-type="Workunit_Class"><i class="icon dashicons dashicons-admin-home"></i><?php echo esc_attr( 'Ajouter unité', 'digirisk' ); ?></li>
				</ul>
			</div>
		<?php
		endif;
		?>
	</div>
	<?php Navigation_Class::g()->display_list( $establishment->id, $selected_establishment_id ); ?>
</li>
