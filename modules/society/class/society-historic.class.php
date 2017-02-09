<?php
/**
 * Classe gérant l'historique des sociétés.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.2.6.0
 * @version 6.2.6.0
 * @copyright 2015-2017 Evarisk
 * @package society
 * @subpackage class
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant l'historique des sociétés.
 */
class Society_Historic_Class extends Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 6.2.6.0
	 * @version 6.2.6.0
	 */
	protected function construct() {}

	/**
	 * Charges tous les groupements de l'application, et enlèves le groupement courant.
	 * Charges la vue affichant le select permettant de déplacer une société vers une autre.
	 *
	 * @param  Society_Model $selected_society L'objet société.
	 * @return void
	 *
	 * @since 6.2.6.0
	 * @version 6.2.6.0
	 */
	public function display( $selected_society ) {
		$groupments = Group_Class::g()->get( array(
			'status' => 'publish',
		) );

		View_Util::exec( 'society', 'historic/main', array(
			'selected_society' => $selected_society,
			'groupments' => $groupments,
		) );
	}

	/**
	 * Récupères tous les risques ajoutées entre une période.
	 *
	 * @return array Tous les risques récupérés.
	 *
	 * @since 6.2.6.0
	 * @version 6.2.6.0
	 */
	public function get_added_risks_in_period() {

	}

	/**
	 * Récupères tous les risques supprimées entre une période.
	 *
	 * @return array Tous les risques récupérés.
	 *
	 * @since 6.2.6.0
	 * @version 6.2.6.0
	 */
	public function get_deleted_risk_in_period() {

	}

	/**
	 * Récupères tous les risques selon leurs cotations dans une période.
	 *
	 * @return array Tous les risques récupérés.
	 *
	 * @since 6.2.6.0
	 * @version 6.2.6.0
	 */
	public function get_added_risks_in_period_and_in_cotation() {

	}
}

Society_Historic_Class::g();
