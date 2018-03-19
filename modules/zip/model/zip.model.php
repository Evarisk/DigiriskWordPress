<?php
/**
 * Définition des champs d'un zip.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.2.1
 * @version 6.2.1
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Le modèle du ZIP
 */
class ZIP_Model extends \eoxia\Post_Model {

	/**
	 * Le constructeur
	 *
	 * @since 6.2.1
	 * @version 6.2.1
	 *
	 * @param ZIP_Model $object L'objet zip.
	 */
	public function __construct( $object ) {
		$this->model['list_generation_results'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
		);

		parent::__construct( $object );
	}
}
