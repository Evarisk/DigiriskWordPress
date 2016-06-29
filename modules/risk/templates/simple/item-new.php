<?php if ( !defined( 'ABSPATH' ) ) exit; ?>
<div class="wp-digi-risk-item wp-digi-risk-item-new wp-digi-list-item <?php $i++; echo ( $i%2 ? 'odd' : 'even' ); ?> wp-digi-bloc-loader" data-risk-id="new" >
	<form method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
		<input type="hidden" name="action" value="save_risk" />
		<input type="hidden" name="method_evaluation_id" />
		<input type="hidden" class="digi-method-simple" value="<?php echo $term_evarisk_simple->term_id; ?>" />
		<input type="hidden" name="global" value="<?php echo str_replace( 'mdl_01', 'ctr',get_class( $element ) ); ?>" />
		<?php wp_nonce_field( 'save_risk' ); ?>
		<input type="hidden" name="element_id" value="<?php echo $element->id; ?>" />

		<ul class="wp-digi-table">
			<li>
				<span title="<?php _e( 'Upload media', 'digirisk' ); ?>" class="wp-digi-risk-thumbnail wpeo-upload-media" data-nonce="<?php echo wp_create_nonce( 'ajax_file_association_risk' ); ?>" data-id="0" data-type="digi-risk"  >
					<div class="mask">
						<span class="dashicons dashicons-plus"></span>
					</div>
					<i title="<?php _e( 'Upload image', 'digirisk' ); ?>" class="wp-digi-element-thumbnail dashicons dashicons-format-image" ></i>
					<input type="hidden" name="thumbnail_id" value="" />
					<img data-nonce="<?php echo wp_create_nonce( 'ajax_file_association_risk' ); ?>" data-id="0" data-type="digi-risk" width="50" height="50" class="hidden wpeo-upload-media attachment-digirisk-element-miniature size-digirisk-element-miniature wp-post-image" alt="" sizes="(max-width: 50px) 100vw, 50px">
				</span>

				<input type="hidden" class="risk-level" name="risk_evaluation_level" value="0" />
				<span data-target="wp-digi-risk-cotation-chooser" class="digi-toggle wp-digi-risk-list-column-cotation" >
					<div class="wp-digi-risk-level-1 wp-digi-risk-level-new"><?php _e( 'Cot', 'digirisk' ); ?></div>
					<ul class="wp-digi-risk-cotation-chooser digi-popup" style="display: none;" >
						<li data-level="1" class="wp-digi-risk-level-1" >&nbsp;</li>
						<li data-level="2" class="wp-digi-risk-level-2" >&nbsp;</li>
						<li data-level="3" class="wp-digi-risk-level-3" >&nbsp;</li>
						<li data-level="4" class="wp-digi-risk-level-4" >&nbsp;</li>
						<li class="open-method-evaluation-render"><span class="dashicons dashicons-admin-generic"></span></li>
					</ul>
				</span>

				<span class="wp-digi-risk-list-column-reference"></span>

				<span class="wp-digi-risk-select"><?php global $wpdigi_danger_category_ctr; $wpdigi_danger_category_ctr->display_category_danger( array( 'with_danger' => true, ) ); ?></span>

				<span class="wp-digi-risk-date"><input type="text" class="wpdigi_date" name="comment_date[]" value="<?php echo current_time( 'd/m/Y', 0 ); ?>" /></span>
				<span class="wp-digi-risk-comment" ><textarea name="comment_content[]" rows="1" placeholder="<?php _e( 'Add a comment for the risk', 'digirisk' ); ?>" ></textarea></span>

				<span class="wp-digi-risk-action wp-digi-action wp-digi-action-new" >
					<button class="progress-button" data-style="fill" data-horizontal><i class="dashicons dashicons-plus"></i></button>
				</span>
			</li>
		</ul>

		<div class="wpdigi-method-evaluation-render"><?php require( wpdigi_utils::get_template_part( WPDIGI_RISKS_DIR, WPDIGI_RISKS_TEMPLATES_MAIN_DIR, 'simple', 'method', 'evaluation-evarisk' ) ); ?></div>
	</form>
</div>
