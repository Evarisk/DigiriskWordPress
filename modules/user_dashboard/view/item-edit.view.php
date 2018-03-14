<?php
/**
 * Affichage d'un utilisateur en mode édition.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.1.9
 * @version 6.4.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<tr class="user-row">
	<input type="hidden" name="action" value="save_user" />
	<?php wp_nonce_field( 'ajax_save_user' ); ?>
	<input type="hidden" name="id" value="<?php echo esc_attr( $user->id ); ?>" />
	<td><div class="avatar" style="background-color: #<?php echo esc_attr( $user->avatar_color ); ?>;"><span><?php echo esc_html( $user->initial ); ?></span></div></td>
	<td class="padding"><span><strong><?php echo esc_html( \eoxia\User_Class::g()->element_prefix . $user->id ); ?><strong></span></td>
	<td class="padding tooltip red"
		aria-label="<?php esc_attr_e( 'Ce champ est obligatoire', 'digirisk' ); ?>">
		<input type="text" class="lastname" placeholder="Name" name="lastname" value="<?php echo esc_attr( $user->lastname ); ?>" />
	</td>
	<td class="padding tooltip red"
		aria-label="<?php esc_attr_e( 'Ce champ est obligatoire', 'digirisk' ); ?>">
		<input type="text" class="firstname" placeholder="Firstname" name="firstname" value="<?php echo esc_attr( $user->firstname ); ?>" />
	</td>
	<td class="padding tooltip email red"
		aria-label="<?php echo esc_attr_e( 'Cette adresse email est déjà utilisée.', 'digirisk' ); ?>">
		<input type="text" class="email" placeholder="demo@<?php echo esc_attr( get_option( 'digirisk_domain_mail', 'demo.com' ) ); ?>" name="email" value="<?php echo esc_attr( $user->email ); ?>" />
	</td>
	<td>
		<div class="action">
			<?php if ( empty( $user->id ) ) : ?>
				<div class="button w50 disable add action-input"
					data-namespace="digirisk"
					data-module="userDashboard"
					data-before-method="checkData"
					data-parent="user-row"
					data-loader="table">
					<i class="icon far fa-plus"></i>
				</div>
			<?php	else : ?>
				<div class="button w50 green add action-input"
					data-namespace="digirisk"
					data-module="userDashboard"
					data-before-method="checkData"
					data-parent="user-row"
					data-loader="table">
					<i class="icon fas fa-save"></i>
				</div>
			<?php	endif; ?>
		</div>
	</td>
</tr>
