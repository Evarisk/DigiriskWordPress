<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<form method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" class="wp-digi-bloc-loader form-epi">
	<?php wp_nonce_field( 'edit_epi' ); ?>
	<input type="hidden" name="action" value="edit_epi" />
	<input type="hidden" name="parent_id" value="<?php echo $society_id; ?>" />

	<ul class="wp-digi-list wp-digi-epi wp-digi-table">
		<?php epi_class::g()->display_epi_list( $society_id ); ?>
		<?php require( EPI_VIEW_DIR . '/item-edit.php' ); ?>
	</ul>
</form>
