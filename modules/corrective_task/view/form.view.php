<?php
/**
 * Le formulaire pour ajouter une tâche corrective.
 *
 * @author Evarisk <jimmy@evarisk.com>
 * @since 6.0.0
 * @version 6.4.4
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php echo do_shortcode( '[task id="' . $task->data['id'] . '"]' ); ?>

<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $task->data['id'] ) ); ?>"><?php esc_html_e( 'Aller vers la tâche', 'digirisk' ); ?></a>

<p class="message hidden"><?php esc_html_e( 'Cliquez sur le + blue pour créer votre action corrective.', 'digirisk' ); ?></p>
