<?php
/**
 * Les actions relatives aux groupements
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.1.5
 * @version 6.2.9
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux groupements
 */
class Group_Action {

	/**
	 * Le constructeur appelle les actions ajax suivantes:
	 * wp_ajax_wpdigi-create-group
	 * wp_ajax_wpdigi-load-group
	 * wp_ajax_wpdigi_ajax_group_update
	 * wp_ajax_display_ajax_sheet_display
	 * wp_ajax_wpdigi_generate_duer_digi-group
	 *
	 * @since 6.1.5
	 * @version 6.2.4
	 */
	public function __construct() {
		add_action( 'wp_ajax_create_group', array( $this, 'ajax_create_group' ) );
	}

	/**
	 * Créer un groupement
	 *
	 * @since 6.1.5
	 * @version 6.2.9
	 */
	public function ajax_create_group() {
		check_ajax_referer( 'create_group' );

		if ( 0 === (int) $_POST['parent_id'] ) {
			wp_send_json_error();
		} else {
			$parent_id = (int) $_POST['parent_id'];
		}

		$group = Group_Class::g()->create( array(
			'parent_id' => $parent_id,
			'title'     => __( 'Undefined', 'digirisk' ),
		) );

		ob_start();
		Digirisk_Class::g()->display();
		wp_send_json_success( array(
			'namespace' => 'digirisk',
			'module' => 'group',
			'callback_success' => 'createdGroupSuccess',
			'groupment_id' => $group->id,
			'template' => ob_get_clean(),
		) );
	}
}

new Group_Action();
