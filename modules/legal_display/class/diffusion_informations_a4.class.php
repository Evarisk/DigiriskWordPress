<?php
/**
 * Permet de définir l'objet Diffusion Informations A4
 *
 * @package Evarisk\Plugin
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Permet de définir l'objet Affichage_Legal_A4
 */
class Diffusion_Informations_A4_Class extends \eoxia\Post_Class {
	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name   				= '\digi\diffusion_informations_a4_model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type    				= 'diffusion_info_A4';

	/**
	 * Le type du document
	 *
	 * @var string
	 */
	public $attached_taxonomy_type  = 'attachment_category';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key    					= '_wpdigi_document';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base 								= 'diffusion_informations_a4';

	/**
	 * La version de l'objet
	 *
	 * @var string
	 */
	protected $version 							= '0.1';

	/**
	 * Le préfixe de l'objet dans DigiRisk
	 *
	 * @var string
	 */
	public $element_prefix 					= 'DI-A4-';

	/**
	 * La fonction appelée automatiquement avant la création de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $before_put_function = array( '\digi\construct_identifier' );

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet dans la base de donnée
	 *
	 * @var array
	 */
	protected $after_get_function = array( '\digi\get_identifier' );

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'Diffusion Informations A4';

	/**
	 * Le constructeur
	 *
	 * @return void
	 */
	protected function construct() {
		parent::construct();
	}
}

Diffusion_Informations_A4_Class::g();