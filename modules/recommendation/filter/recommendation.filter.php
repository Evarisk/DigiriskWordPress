<?php
/**
 * Les filtres relatifs aux recommandations
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.2.10
 * @version 6.5.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres relatifs aux recommandations
 */
class Recommendation_Filter {

	/**
	 * Le constructeur ajoute le filtre digi_tab
	 *
	 * @since 6.2.10
	 * @version 6.2.4
	 */
	public function __construct() {
		add_filter( 'digi_tab', array( $this, 'callback_tab' ), 4, 2 );
	}

	/**
	 * Ajoutes l'onglet "Recommendations" dans les unités de travail.
	 *
	 * @param  array   $list_tab  La liste des onglets.
	 * @param  integer $id        L'ID de la société.
	 * @return array              La liste des onglets et ceux ajoutés par cette méthode.
	 *
	 * @since 6.2.10
	 * @version 6.4.4
	 */
	public function callback_tab( $list_tab, $id ) {
		$list_tab['digi-workunit']['recommendation'] = array(
			'type'  => 'text',
			'text'  => __( 'Signalisations', 'digirisk' ),
			'title' => __( 'Les signalisations', 'digirisk' ),
		);

		$list_tab['digi-group']['recommendation'] = array(
			'type'  => 'text',
			'text'  => __( 'Signalisations', 'digirisk' ),
			'title' => __( 'Les signalisations', 'digirisk' ),
		);

		return $list_tab;
	}
}

new Recommendation_Filter();
