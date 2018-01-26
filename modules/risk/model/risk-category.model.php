<?php
/**
 * Définition du schéma des catégories de risque.
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.4.0
 * @version 6.5.0
 * @copyright 2015-2018 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Définition du schéma des catégories de risque.
 */
class Risk_Category_Model extends \eoxia\Term_Model {

	/**
	 * Définition du schéma des catégories de risque.
	 *
	 * @since 6.4.0
	 * @version 6.5.0
	 *
	 * @param array $data       Data.
	 * @param mixed $req_method Peut être "GET", "POST", "PUT" ou null.
	 */
	public function __construct( $data = null, $req_method = null ) {
		$this->schema['status'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => '_wpdigi_status',
			'default'   => '',
		);

		$this->schema['unique_key'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => '_wpdigi_unique_key',
			'default'   => '',
		);

		$this->schema['unique_identifier'] = array(
			'type'      => 'string',
			'meta_type' => 'multiple',
			'default'   => '',
		);

		$this->schema['thumbnail_id'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_thumbnail_id',
			'default'   => 0,
		);

		$this->schema['position'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_position',
			'default'   => 1,
		);

		parent::__construct( $data, $req_method );
	}

}
