<?php
/**
 * Classe gérant les filtres du listing des risques.
 *
 * @author    Evarisk <dev@evarisk.com>
 * @copyright (c) 2006 2018 Evarisk <dev@evarisk.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   DigiRisk\Classes
 *
 * @since     6.5.0
 */

namespace digi;

defined( 'ABSPATH' ) || exit;

/**
 * Lsting Risk Filter class.
 */
class Listing_Risk_Corrective_Task_Filter extends Identifier_Filter {

	/**
	 * Constructor.
	 *
	 * @since 7.1.0
	 */
	public function __construct() {
		parent::__construct();
		add_filter( 'digi_tab', array( $this, 'callback_digi_tab' ), 7, 2 );
	}

	/**
	 * Ajoutes une entrée dans le tableau $list_tab pour la société.
	 *
	 * @param  array   $list_tab  La liste des filtres.
	 * @param  integer $id        L'ID de la société.
	 * @return array              La liste des filtres + le filtre ajouté par cette méthode.
	 *
	 * @since 7.1.0
	 */
	public function callback_digi_tab( $list_tab, $id ) {
		$list_tab['digi-society']['listing-risk-action-corrective'] = array(
			'type'  => 'text',
			'text'  => __( 'Listing des risques action corrective ', 'digirisk' ),
			'title' => __( 'Listing des risques action corrective', 'digirisk' ),
			'icon'  => '<i class="fas fa-exclamation"></i>',
		);

		$list_tab['digi-group']['listing-risk-action-corrective'] = array(
			'type'  => 'text',
			'text'  => __( 'Listing des risques action corrective ', 'digirisk' ),
			'title' => __( 'Listing des risques action corrective', 'digirisk' ),
			'icon'  => '<i class="fas fa-exclamation"></i>',
		);

		return $list_tab;
	}
}

new Listing_Risk_Corrective_Task_Filter();
