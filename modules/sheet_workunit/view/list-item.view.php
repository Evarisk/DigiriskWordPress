<?php
/**
 * Gestion de l'affichage d'une fiche de poste
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.1.9
 * @version 6.6.1
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<tr>
	<td class="padding"><strong><?php echo esc_html( $element->unique_identifier ); ?></strong></td>
	<td class="padding"><?php echo esc_html( $element->title ); ?></td>
	<td>
		<div class="action">
			<?php if ( ! empty( Document_Class::g()->get_document_path( $element, 'digi-workunit' ) ) ) : ?>
			<a class="button purple pop h50" href="<?php echo esc_attr( Document_Class::g()->get_document_path( $element, 'digi-workunit' ) ); ?>">
				<i class="fa fa-download icon" aria-hidden="true"></i>
				<!-- <span><?php esc_html_e( 'Fiche de poste', 'digirisk' ); ?></span> -->
			</a>
		<?php else : ?>
			<span class="button grey h50 tooltip hover" aria-label="<?php echo esc_attr_e( 'Corrompu', 'digirisk' ); ?>">
				<i class="fa fa-times icon" aria-hidden="true"></i>
			</span>
		<?php endif; ?>
		</div>
	</td>
</tr>
