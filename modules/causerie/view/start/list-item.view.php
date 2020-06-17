<?php
/**
 * Affichage d'une causerie
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
} ?>

<div class="table-row causerie-row" data-id="<?php echo esc_attr( $causerie->data['id'] ); ?>">
	<div data-title="Ref." class="table-cell table-50">
		<span>
			<strong><?php echo esc_html( $causerie->data['unique_identifier'] ); ?></strong>
			<?php
			if ( ! empty( $causerie->data['second_identifier'] ) ) :
				?>
				<strong><?php echo esc_html( $causerie->data['second_identifier'] ); ?></strong>
				<?php
			endif;
			?>
		</span>
	</div>
	<div data-title="Photo" class="table-cell table-50">
		<?php echo do_shortcode( '[wpeo_upload id="' . $causerie->data['id'] . '" model_name="' . $causerie->get_class() . '" mode="view" single="false" field_name="image" ]' ); ?>
	</div>
	<div data-title="Catégorie" class="table-cell table-50">
		<?php
		if ( isset( $causerie->data['risk_category'] ) ) :
			do_shortcode( '[digi_dropdown_categories_risk id="' . $causerie->data['id'] . '" type="causerie" display="view" category_risk_id="' . $causerie->data['risk_category']->data['id'] . '"]' );
		else :
			?>C<?php
		endif;
		?>
	</div>
	<div data-title="Titre et description" class="table-cell causerie-description">
		<span class="row-title"><?php echo esc_html( $causerie->data['title'] ); ?></span>
		<span class="row-subtitle"><?php echo nl2br( $causerie->data['content'] ); ?></span>
	</div>
	<div class="table-cell table-100 table-end">
		<div class="action <?php echo $started ? 'wpeo-gridlayout' : ''; ?>  grid-2 grid-gap-0">
			<?php if ( Causerie_Class::g()->get_type() === $causerie->data['type'] ) : ?>
				<a class="wpeo-button light button-square-50 edit" href="<?php echo esc_attr( admin_url( 'admin-post.php?action=start_causerie&id=' . $causerie->data['id'] ) ); ?>"><i class="icon fa fa-play"></i></a>
			<?php else : ?>
				<a class="wpeo-button light button-square-50 edit wpeo-tooltip-event hover" aria-label="<?php echo esc_attr_e( 'Reprendre la causerie', 'digirisk' ); ?>" href="<?php echo esc_attr( admin_url( 'admin.php?page=digirisk-causerie&id=' . $causerie->data['id'] ) ); ?>"><i class="icon fa fa-play"></i></a>
			<?php endif; ?>

			<?php
			if ( $started ) :
				?>
				<!-- Supprimer un risque -->
				<div 	class="wpeo-button button-square-50 button-transparent delete action-delete"
							data-id="<?php echo esc_attr( $causerie->data['id'] ); ?>"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_started_causerie' ) ); ?>"
							data-message-delete="<?php esc_attr_e( 'Cette causerie est en cours, êtes-vous sûr(e) de vouloir la supprimer ?', 'digirisk' ); ?>"
							data-action="delete_started_causerie"><i class="button-icon fas fa-trash"></i></div>
				<?php
			endif;
			?>

		</div>
	</div>
</div>
