<?php
/**
 * Ajoutes l'onglet Configuration aux unités de travail
 *
 * @since 6.2.2.0
 * @version 6.2.4.0
 *
 * @package Evarisk\Plugin
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Ajoutes l'onglet Configuration aux unités de travail
 */
class Workunit_Filter {

	/**
	 * Le constructeur
	 *
	 * @since 6.2.2.0
	 * @version 6.2.2.0
	 */
	public function __construct() {
		add_filter( 'digi_tab', array( $this, 'callback_digi_tab' ), 5, 2 );
	}

	/**
	 * Ajoutes les onglets "Configuration" et "Supprimer" aux unités de travail
	 *
	 * @since 6.2.2.0
	 * @version 6.2.4.0
	 *
	 * @param  array   $tab_list La liste des filtres.
	 * @param  integer $id L'ID de la société.
	 *
	 * @return array
	 */
	function callback_digi_tab( $tab_list, $id ) {
		$tab_list['digi-workunit']['more'] = array(
			'type' => 'toggle',
			'text' => '<i class="action fa fa-ellipsis-v toggle"></i>',
			'items' => array(
				'configuration' => array(
					'type' => 'text',
					'text' => __( 'Configuration', 'digirisk' ),
				),
				'delete' => array(
					'type' => 'text',
					'text' => __( 'Supprimer', 'digirisk' ),
					'class' => 'action-delete',
					'attributes' => 'data-action=delete_society data-id=' . $id . '',
					'nonce' => wp_create_nonce( 'delete_society' ),
				),
			),
		);

		return $tab_list;
	}
}

new Workunit_Filter();
