<?php
/**
 * Affichage principale de la page "Causeries".
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


<div class="wrap wrap-prevention digirisk-wrap wpeo-wrap">
	<span style="display: flex;">
		<h2 style="font-size: 23px; font-weight: 400;"><?php esc_html_e( 'Prevention Plan', 'digirisk' ); ?></h2>
		<?php if ( user_can( get_current_user_id(), 'manage_preventionplan' ) ): ?>
			<a class="page-title-action wpeo-tooltip-event"
			 href="<?php echo esc_attr( admin_url( 'admin-post.php?action=start_prevention' ) ); ?>"
			 aria-label="<?php esc_html_e( 'Commencer un plan de prévention', 'digirisk' ); ?>"
			 style="margin-left: 5px; height: 100%; margin-top: 24px;">
				<?php esc_html_e( 'Nouveau', 'digirisk' ); ?>
			</a>
		<?php endif; ?>
	</span>

	<div class="wpeo-tab">
		<ul class="tab-list">
			<li class="tab-element tab-active"
			data-action="prevention_load_tab"
			data-nonce="<?php echo esc_attr( wp_create_nonce( "prevention_load_tab" ) ); ?>"
			data-tab="<?php echo esc_attr( 'dashboard' ); ?>">
				<?php esc_html_e( 'Dashboard', 'digirisk' ); ?>
			</li>

			<?php if ( user_can( get_current_user_id(), 'manage_preventionplan' ) ): ?>
				<li class="tab-element"
				data-action="prevention_load_tab"
				data-nonce="<?php echo esc_attr( wp_create_nonce( "prevention_load_tab" ) ); ?>"
				data-tab="<?php echo esc_attr( 'progress' ); ?>">
					<?php esc_html_e( 'En cours', 'digirisk' ); ?>
				</li>
			<?php endif; ?>
		</ul>

		<div class="tab-content main-content">
			<?php Prevention_Page_Class::g()->display_dashboard(); ?>
		</div>
	</div>
</div>