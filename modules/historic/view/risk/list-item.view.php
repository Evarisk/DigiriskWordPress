<?php
/**
 * Affichage d'une cotation (Historique)
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.2.10
 * @version 6.5.0
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<tr class="risk-row">
	<td data-title="Ref." class="padding">
		<span><strong><?php echo esc_html( $evaluation->unique_identifier ); ?></strong></span>
	</td>
	<td data-title="Date" class="w50 padding">
		<?php echo esc_html( $evaluation->date['rendered']['date_time'] ); ?>
	</td>
	<td data-title="Cot." class="w50">
		<div class="cotation-container grid">
			<div class="action cotation default-cotation level<?php echo esc_attr( $evaluation->scale ); ?>">
				<span><?php echo esc_html( $evaluation->risk_level['equivalence'] ); ?></span>
			</div>
		</div>
	</td>
	<td data-title="Commentaire" class="padding">
		<ul class="comment-container">
			<?php
			if ( ! empty( $evaluation->comments ) ) :
				foreach ( $evaluation->comments as $comment ) :
					if ( 0 !== $comment->id ) :
						?>
							<?php	$userdata = get_userdata( $comment->author_id ); ?>

							<li class="comment">
								<span class="user"><?php echo ! empty( $userdata->display_name ) ? esc_html( $userdata->display_name ) : 'Indéfini'; ?>, </span>
								<span class="date"><?php echo esc_html( $comment->date['rendered']['date'] ); ?> : </span>
								<span class="content"><?php echo esc_html( $comment->content ); ?></span>
							</li>
						<?php
					else :
						?><li><i><?php echo esc_html( 'Aucun commentaire', 'digirisk' ); ?></i></li><?php
					endif;
				endforeach;
			endif;
			?>
		</ul>
	</td>
</tr>
