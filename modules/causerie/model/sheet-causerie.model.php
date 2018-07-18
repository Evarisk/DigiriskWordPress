<?php
/**
 * Le modèle définissant les données d'une fiche de causerie.
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     6.6.0
 * @version   6.6.0
 * @copyright 2018 Causerie.
 * @package   DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Le modèle définissant les données d'une fiche de causerie.
 */
class Sheet_Causerie_Model extends Document_Model {

	/**
	 * Construit le modèle
	 *
	 * @since 6.6.0
	 * @version 6.6.0
	 *
	 * @param  Sheet_Causerie_Modal $object La définition de l'objet dans l'instance actuelle.
	 *
	 * @return Sheet_Causerie_Modal
	 */
	public function __construct( $object ) {
		$this->model['document_meta'] = array(
			'type'      => 'array',
			'meta_type' => 'single',
			'field'     => 'document_meta',
			'child'     => array(),
		);

		$this->model['document_meta']['child']['titreCauserie'] = array(
			'type' => 'string',
		);

		$this->model['document_meta']['child']['categorieINRS'] = array(
			'type' => 'string',
		);

		$this->model['document_meta']['child']['nomUtilisateur'] = array(
			'type' => 'string',
		);

		$this->model['document_meta']['child']['prenomUtilisateur'] = array(
			'type' => 'string',
		);

		$this->model['document_meta']['child']['descriptionCauserie'] = array(
			'type' => 'string',
		);

		$this->model['document_meta']['child']['formateurCauserie'] = array(
			'type' => 'string',
		);

		$this->model['document_meta']['child']['dateDebutCauserie'] = array(
			'type' => 'wpeo_date',
		);

		$this->model['document_meta']['child']['dateClotureCauserie'] = array(
			'type' => 'wpeo_date',
		);

		$this->model['document_meta']['child']['nombreCauserie'] = array(
			'type' => 'integer',
		);

		$this->model['document_meta']['child']['dateCreation'] = array(
			'type' => 'wpeo_date',
		);

		$this->model['document_meta']['child']['nombreFormateur'] = array(
			'type' => 'integer',
		);

		$this->model['document_meta']['child']['nombreUtilisateur'] = array(
			'type' => 'integer',
		);

		$this->model['document_meta']['child']['nombreMedia'] = array(
			'type' => 'integer',
		);

		$this->model['document_meta']['child']['nomsMediaCauserie'] = array(
			'type' => 'integer',
		);

		$this->model['document_meta']['child']['mediaCauserie'] = array(
			'type' => 'array',
		);

		$this->model['document_meta']['child']['utilisateurs'] = array(
			'type' => 'array',
		);

		parent::__construct( $object );
	}

}
