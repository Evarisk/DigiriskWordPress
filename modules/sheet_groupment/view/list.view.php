<?php
/**
 * La liste des fiches de groupement
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.1.9
 * @version 6.2.4
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<thead>
	<tr>
		<th class="padding"><?php esc_html_e( 'Ref', 'digirisk' ); ?>.</th>
		<th class="padding"><?php esc_html_e( 'Nom', 'digirisk' ); ?></th>
		<th class="w50"></th>
	</tr>
</thead>

<tbody>
	<?php if ( ! empty( $list_document ) ) : ?>
		<?php foreach ( $list_document as $element ) : ?>
			<?php \eoxia\View_Util::exec( 'digirisk', 'sheet_groupment', 'list-item', array( 'element' => $element ) ); ?>
		<?php endforeach; ?>
	<?php endif; ?>
</tbody>
