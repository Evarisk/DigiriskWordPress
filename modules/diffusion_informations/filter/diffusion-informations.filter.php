<?php
/**
 * Gestion des filtres relatifs aux diffusions d'informations
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.4.0
 * @version 6.4.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des filtres relatifs aux diffusions d'informations
 */
class Diffusion_Informations_Filter {

	/**
	 * Utilises le filtre digi_tab
	 */
	public function __construct() {
		add_filter( 'digi_tab', array( $this, 'callback_tab' ), 3, 2 );

		add_filter( 'digi_diffusion_informations_A4_document_data', array( $this, 'callback_digi_document_data' ), 10, 2 );
		add_filter( 'digi_diffusion_informations_A3_document_data', array( $this, 'callback_digi_document_data' ), 10, 2 );
	}

	/**
	 * Ajoutes l'onglet diffusion informations dans la société.
	 *
	 * @since 6.4.0
	 * @version 6.4.0
	 *
	 * @param  array   $list_tab Les onglets déjà présents.
	 * @param  integer $id       L'ID de la société.
	 * @return array             Les onglets déjà présents et ceux ajoutés par cette méthode.
	 */
	public function callback_tab( $list_tab, $id ) {
		$list_tab['digi-society']['diffusion-informations'] = array(
			'type'  => 'text',
			'text'  => __( 'Diffusion informations', 'digirisk' ),
			'title' => __( 'Les diffusions informations', 'digirisk' ),
		);
		return $list_tab;
	}

	/**
	 * Ajoutes toutes les données nécessaire pour le registre des AT bénins.
	 *
	 * @since 7.0.0
	 *
	 * @param  array         $data    Les données pour le registre des AT bénins.
	 * @param  Society_Model $society Les données de la société.
	 *
	 * @return array                  Les données pour le registre des AT bénins modifié.
	 */
	public function callback_digi_document_data( $data, $society ) {

	}
}

new Diffusion_Informations_Filter();
