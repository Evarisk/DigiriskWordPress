<?php
/**
 * Gestion de la popup et de la notification pour les notes de versions.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.3.0
 * @version 6.3.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$result = Digirisk_Class::g()->get_patch_note(); ?>

<div class="notification active">
	<span class="thumbnail"><img src="<?php echo esc_attr( PLUGIN_DIGIRISK_URL . 'core/assets/images/favicon_hd.png' ); ?>" /></span>
	<span class="title">Note de mise à jour de la <a href="#">version <?php echo esc_attr( \eoxia\Config_Util::$init['digirisk']->version ); ?></a></span>
	<span class="close"><i class="icon fa fa-times-circle"></i></span>
</div>

<div class="popup patch-note">
	<div class="container">
		<div class="header">
			<h2 class="title"><?php echo esc_html( 'Note de version: ' . $result->acf->numero_de_version ); ?></h2>
			<i class="close fa fa-times"></i>
		</div>
		<div class="content">
			<?php
			if ( ! empty( $result->acf->note_de_version ) ) :
				foreach ( $result->acf->note_de_version as $element ) :
					echo esc_html( $element->numero_de_suivi );
					echo $element->description;

					if ( ! empty( $element->illustration ) ) :
						?>
						<img src="<?php echo esc_attr( $element->illustration ); ?>" alt="<?php echo esc_attr( $element->numero_de_suivi ); ?>" />
						<?php
					endif;
				endforeach;
			endif;
			?>
		</div>
	</div>
</div>