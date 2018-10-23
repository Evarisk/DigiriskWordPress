<?php
/**
 * Ajoutes un shortcode qui permet d'afficher le listing des risques.
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.5.0
 * @version 6.5.0
 * @copyright 2015-2018 Evarisk
 * @package risk
 * @subpackage shortcode
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajoutes un shortcode qui permet d'afficher la liste de tous les risques d'une société.
 */
class Listing_Risk_Shortcode {

	/**
	 * Ajoutes le shortcode digi_risk qui permet l'affichage de la liste des risques.
	 * Ajoutes le shortcode digi_dropdown_risk qui permet l'affichage de la toggle pour sélectionné la catégorie de risque.
	 */
	public function __construct() {
		add_shortcode( 'digi-listing-risk', array( $this, 'callback_digi_listing_risk' ) );
		add_shortcode( 'digi_dropdown_risk', array( $this, 'callback_dropdown_risk' ) );
	}

	/**
	 * Appelle la méthode display de l'objet Risk_Class pour gérer le rendu du listing des risques.
	 *
	 * @param  array $param  Les arguments du shortcode.
	 * @return void
	 *
	 * @since 6.5.0
	 * @version 6.5.0
	 */
	public function callback_digi_listing_risk( $param ) {
		$element_id = ! empty( $param['post_id'] ) ? (int) $param['post_id'] : 0;
		Listing_Risk_Class::g()->display( $element_id );
	}

	/**
	 * Appelle la vue pour afficher les catégories de risque.
	 *
	 * @param  array $param  Les arguments du shortcode.
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 6.3.0
	 */
	public function callback_dropdown_risk( $param ) {
		$society_id = ! empty( $param['society_id'] ) ? (int) $param['society_id'] : 0;
		$element_id = ! empty( $param['element_id'] ) ? (int) $param['element_id'] : 0;
		$risk_id = ! empty( $param['risk_id'] ) ? (int) $param['risk_id'] : 0;

		$society = Society_Class::g()->show_by_type( $society_id );

		$risks = Risk_Class::g()->get( array(
			'post_parent' => $society_id,
		) );

		if ( count( $risks ) > 1 ) {
			usort( $risks, function( $a, $b ) {
				if ( $a->evaluation->risk_level['equivalence'] === $b->evaluation->risk_level['equivalence'] ) {
					return 0;
				}
				return ( $a->evaluation->risk_level['equivalence'] > $b->evaluation->risk_level['equivalence'] ) ? -1 : 1;
			} );
		}

		\eoxia001\View_Util::exec( 'digirisk', 'risk', 'dropdown/list', array(
			'risks' => $risks,
			'risk_id' => $risk_id,
		) );
	}
}

new Listing_Risk_Shortcode();
