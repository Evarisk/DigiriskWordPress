<?php
/**
 * Classe principale gérant tous les documents (ODT) de DigiRisk.
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 6.0.0
 * @version 6.6.0
 * @copyright 2018 Evarisk.
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe principale gérant tous les documents (ODT) de DigiRisk.
 */
class Document_Class extends \eoxia001\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\digi\document_model';

	/**
	 * Le post type
	 *
	 * @var string
	 * @todo:  Détruis la route de WordPress /wp-json/wp/v2/media (A changer très rapidement)
	 */
	protected $post_type = 'attachment';

	/**
	 * La taxonomie
	 *
	 * @var string
	 */
	public $attached_taxonomy_type = 'attachment_category';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = '_wpdigi_document';

	/**
	 * Le préfixe de l'objet dans DigiRisk
	 *
	 * @var string
	 */
	public $element_prefix = 'DOC';

	/**
	 * La fonction appelée automatiquement avant la mise à jour de l'objet dans la base de donnée
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
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'document';

	/**
	 * Le nombre de document par page
	 *
	 * @var integer
	 */
	protected $limit_document_per_page = 5;

	/**
	 * Les documents acceptés
	 *
	 * @var array Un tableau de "string".
	 */
	public $mime_type_link = array(
		'application/vnd.oasis.opendocument.text' => '.odt',
		'application/zip'                         => '.zip',
	);

	/**
	 * Le nom de l'ODT sans l'extension; exemple: document_unique
	 *
	 * @var string
	 */
	protected $odt_name = '';

	/**
	 * Récupères le chemin vers le dossier "digirisk" dans wp-content/uploads
	 *
	 * @since 6.0.0
	 * @version 6.4.0
	 *
	 * @param string $path_type (Optional) Le type de path 'basedir' ou 'baseurl'.
	 * @return string Le chemin vers le document
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_upload_dir/
	 */
	public function get_digirisk_dir_path( $path_type = 'basedir' ) {
		$upload_dir = wp_upload_dir();
		return str_replace( '\\', '/', $upload_dir[ $path_type ] ) . '/digirisk';
	}

	/**
	 * Récupération de la liste des modèles de fichiers disponible pour un type d'élément
	 *
	 * @since 6.1.0
	 * @version 6.4.4
	 *
	 * @param array $current_element_type La liste des types pour lesquels il faut récupérer les modèles de documents.
	 * @return array                      Un statut pour la réponse, un message si une erreur est survenue, le ou les identifiants des modèles si existants.
	 */
	public function get_model_for_element( $current_element_type ) {
		if ( in_array( 'zip', $current_element_type, true ) ) {
			return null;
		}

		$response = array(
			'status'     => true,
			'model_id'   => null,
			'model_path' => str_replace( '\\', '/', PLUGIN_DIGIRISK_PATH . 'core/assets/document_template/' . $current_element_type[0] . '.odt' ),
			'model_url'  => str_replace( '\\', '/', PLUGIN_DIGIRISK_URL . 'core/assets/document_template/' . $current_element_type[0] . '.odt' ),
			// translators: Pour exemple: Le modèle utilisé est: C:\wamp\www\wordpress\wp-content\plugins\digirisk-alpha\core\assets\document_template\document_unique.odt.
			'message'    => sprintf( __( 'Le modèle utilisé est: %1$score/assets/document_template/%2$s.odt', 'digirisk' ), PLUGIN_DIGIRISK_PATH, $current_element_type[0] ),
		);

		$tax_query = array(
			'relation' => 'AND',
		);

		if ( ! empty( $current_element_type ) ) {
			foreach ( $current_element_type as $element ) {
				$tax_query[] = array(
					'taxonomy' => self::g()->attached_taxonomy_type,
					'field'    => 'slug',
					'terms'    => $element,
				);
			}
		}

		$query = new \WP_Query( array(
			'fields'         => 'ids',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'tax_query'      => $tax_query,
			'post_type'      => 'attachment',
		) );

		if ( $query->have_posts() ) {
			$upload_dir = wp_upload_dir();

			$model_id               = $query->posts[0];
			$attachment_file_path   = str_replace( '\\', '/', get_attached_file( $model_id ) );
			$response['model_id']   = $model_id;
			$response['model_path'] = str_replace( '\\', '/', $attachment_file_path );
			$response['model_url']  = str_replace( str_replace( '\\', '/', $upload_dir['basedir'] ), str_replace( '\\', '/', $upload_dir['baseurl'] ), $attachment_file_path );

			// translators: Pour exemple: Le modèle utilisé est: C:\wamp\www\wordpress\wp-content\plugins\digirisk-alpha\core\assets\document_template\document_unique.odt.
			$response['message'] = sprintf( __( 'Le modèle utilisé est: %1$s', 'digirisk' ), $attachment_file_path );
		}

		return $response;
	}

	/**
	 * Création d'un fichier odt a partir d'un modèle de document donné et d'un modèle de donnée
	 *
	 * @since 6.0.0
	 * @version 6.4.4
	 *
	 * @param string $model_path       Le chemin vers le fichier modèle a utiliser pour la génération.
	 * @param array  $document_content Un tableau contenant le contenu du fichier à écrire selon l'élément en cours d'impression.
	 * @param string $document_name    Le nom du document.
	 *
	 * @return object                  L'élément courant sur lequel on souhaite générer un document
	 */
	public function generate_document( $model_path, $document_content, $document_name ) {
		$response = array(
			'status'  => false,
			'message' => '',
			'link'    => '',
		);

		require_once PLUGIN_DIGIRISK_PATH . '/core/external/odtPhpLibrary/odf.php';

		$digirisk_directory = $this->get_digirisk_dir_path();
		$document_path      = $digirisk_directory . '/' . $document_name;

		$config = array(
			'PATH_TO_TMP' => $digirisk_directory . '/tmp',
		);
		if ( ! is_dir( $config['PATH_TO_TMP'] ) ) {
			wp_mkdir_p( $config['PATH_TO_TMP'] );
		}

		// On créé l'instance pour la génération du document odt.
		@ini_set( 'memory_limit', '256M' );
		$odf_php_lib = new \DigiOdf( $model_path, $config );

		// Vérification de l'existence d'un contenu a écrire dans le document.
		if ( ! empty( $document_content ) ) {
			// Lecture du contenu à écrire dans le document.
			foreach ( $document_content as $data_key => $data_value ) {
				if ( is_array( $data_value ) && ! empty( $data_value['date_input'] ) ) {
					$data_value = $data_value['date_input']['fr_FR']['date'];
				}
				$odf_php_lib = $this->set_document_meta( $data_key, $data_value, $odf_php_lib );
			}
		}

		// Vérification de l'existence du dossier de destination.
		if ( ! is_dir( dirname( $document_path ) ) ) {
			wp_mkdir_p( dirname( $document_path ) );
		}

		// Enregistrement du document sur le disque.
		$odf_php_lib->saveToDisk( $document_path );

		// Dans le cas ou le fichier a bien été généré, on met a jour les informations dans la base de données.
		if ( is_file( $document_path ) ) {
			$response['status']  = true;
			$response['success'] = true;
			$response['link']    = $document_path;
		}

		return $response;
	}

	/**
	 * Ecris dans le document ODT
	 *
	 * @since 6.0.0
	 * @version 6.4.0
	 *
	 * @param string $data_key    La clé dans le ODT.
	 * @param string $data_value  La valeur de la clé.
	 * @param object $current_odf Le document courant.
	 *
	 * @return object             Le document courant
	 */
	public function set_document_meta( $data_key, $data_value, $current_odf ) {
		// Dans le cas où la donnée a écrire est une valeur "simple" (texte).
		if ( ! is_array( $data_value ) ) {
			$current_odf->setVars( $data_key, stripslashes( $data_value ), true, 'UTF-8' );
		} else if ( is_array( $data_value ) && isset( $data_value[ 'type' ] ) && !empty( $data_value[ 'type' ] ) ) {
			switch ( $data_value[ 'type' ] ) {

				case 'picture':
					$current_odf->setImage( $data_key, $data_value[ 'value' ], ( !empty( $data_value[ 'option' ] ) && !empty( $data_value[ 'option' ][ 'size' ] ) ? $data_value[ 'option' ][ 'size' ] : 0 ) );
					break;

				case 'segment':
					$segment = $current_odf->setdigiSegment( $data_key );

					if ( $segment && is_array( $data_value[ 'value' ] ) ) {
						foreach ( $data_value[ 'value' ] as $segment_detail ) {
							foreach ( $segment_detail as $segment_detail_key => $segment_detail_value ) {
								if ( is_array( $segment_detail_value ) && array_key_exists( 'type', $segment_detail_value ) && ( 'sub_segment' == $segment_detail_value[ 'type' ] ) ) {
									foreach ( $segment_detail_value[ 'value' ] as $sub_segment_data ) {
										foreach ( $sub_segment_data as $sub_segment_data_key => $sub_segment_data_value ) {
											$segment->$segment_detail_key = $this->set_document_meta( $sub_segment_data_key, $sub_segment_data_value, $segment );
										}
									}
								}
								else {
									$segment = $this->set_document_meta( $segment_detail_key, $segment_detail_value, $segment );
								}
							}

							$segment->merge();
						}

						$current_odf->mergedigiSegment( $segment );
					}
					unset( $segment );
					break;
			}
		}

		return $current_odf;
	}

	/**
	 * Renvoies le chemin HTTP vers l'ODT.
	 *
	 * @since 6.0.0
	 * @version 6.6.0
	 *
	 * @param  Object $element Le modèle (objet) ODT.
	 * @param  string $type    Le type de l'élément parent.
	 *
	 * @return string          Le chemin HTTP vers l'ODT.
	 */
	public function get_document_path( $element, $type ) {
		$url = '';

		if ( ! empty( $element ) && is_object( $element ) ) {
			$basedir = $this->get_digirisk_dir_path( 'basedir' );
			$baseurl = $this->get_digirisk_dir_path( 'baseurl' );
			$url     = $baseurl . '/';

			if ( ! empty( $element->parent_id ) && ! empty( $element->mime_type ) ) {
				$url .= '/' . $type . '/' . $element->parent_id . '/';
			}

			$url .= $element->title;

			if ( ! empty( $element->mime_type ) ) {
				$url .= $this->mime_type_link[ $element->mime_type ];
			}

			$path = str_replace( $baseurl, $basedir, $url );

			if ( empty( $element->mime_type ) ) {
				$url = '';
			}

			if ( ! file_exists( $path ) ) {
				$url = '';
			}
		}

		return $url;
	}

	/**
	 * Récupération de la prochaine version pour un type de document
	 *
	 * @param array $current_element_type Le type de document actuellement en cours de création.
	 * @param integer $element_id         L'ID de l'élément.
	 *
	 * @return int                        La version +1 du document actuellement en cours de création.
	 */
	public function get_document_type_next_revision( $current_element_type, $element_id ) {
		global $wpdb;

		// Récupération de la date courante.
		$today = getdate();

		// Définition des paramètres de la requête de récupération des documents du type donné pour la date actuelle.
		$get_model_args = array(
			'nopaging'=> true,
			'post_parent' => $element_id,
			'post_type' => $current_element_type[0],
			'post_status' => array( 'publish', 'inherit' ),
			'tax_query' => array(
				array(
					'taxonomy' => $this->attached_taxonomy_type,
					'field'    => 'slug',
					'terms'    => wp_parse_args( $current_element_type, array( 'printed' ) ),
					'operator' => 'AND',
				),
			),
			'date_query' => array(
				array(
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			),
		);

		$element_sheet_default_model = new \WP_Query( $get_model_args );

		return ( $element_sheet_default_model->post_count + 1 );
	}

	/**
	 * Création du document dans la base de données puis appel de la fonction de génération du fichier
	 *
	 * @since 6.0.0
	 * @version 6.4.0
	 *
	 * @param object $element      L'élément pour lequel il faut créer le document
	 * @param array $document_type Les catégories auxquelles associer le document généré
	 * @param array $document_meta Les données a écrire dans le modèle de document
	 *
	 * @return object              Le résultat de la création du document
	 */
	public function create_document( $element, $document_type, $document_meta ) {
		$types = $document_type;

		$response = array(
			'status' => true,
		);

		// Définition du modèle de document a utiliser pour l'impression.
		$model_to_use   = null;
		$model_response = $this->get_model_for_element( wp_parse_args( array( 'model', 'default_model' ), $document_type ) );
		$model_to_use   = $model_response['model_path'];

		// Définition de la révision du document.
		$document_revision = 0;
		$main_title_part   = '';

		$document_revision = $this->get_document_type_next_revision( $types, $element->id );
		$main_title_part   = $element->title;

		// Définition de la partie principale du nom de fichier.
		if ( ! empty( $document_type ) && is_array( $document_type ) ) {
			$main_title_part = $document_type[0] . '_' . $main_title_part;
		}

		// Enregistrement de la fiche dans la base de donnée.
		$response['filename']  = mysql2date( 'Ymd', current_time( 'mysql', 0 ) ) . '_';
		$response['filename'] .= apply_filters( 'digi_document_identifier', $element->unique_identifier . '_', $element );
		$response['filename'] .= sanitize_title( str_replace( ' ', '_', $main_title_part ) ) . '_V' . $document_revision . '.odt';

		$document_args = array(
			'post_status' => 'inherit',
			'post_title' => basename( $response['filename'], '.odt' ),
		);

		$filetype = 'unknown';

		// @todo: A faire
		if ( in_array( 'zip', $document_type ) ) {
			$filetype = "application/zip";
		}

		$path = '';

		if ( null !== $model_to_use ) {
			$path = $response['filename'];

			if ( ! empty( $element ) ) {
				$path = $element->type . '/' . $element->id . '/' . $response['filename'];
			}

			$document_creation = $this->generate_document( $model_to_use, $document_meta, $path );

			if ( !empty( $document_creation ) && !empty( $document_creation['status'] ) && !empty( $document_creation['link'] ) ) {
				$filetype = wp_check_filetype( $document_creation['link'], null );
				$response['link'] = $document_creation['link'];
				$response['message'] = __( 'The sheet have been generated successfully. Please find it below', 'digirisk' );
			} else {
				$response = wp_parse_args( $document_creation, $response );
			}
		} else {
			$response['status'] = false;
			$response['message'] = $model_response['message'];
		}

		$response['id'] = wp_insert_attachment( $document_args, $this->get_digirisk_dir_path() . '/' . $path, ! empty( $element ) ? $element->id : 0 );

		$attach_data = wp_generate_attachment_metadata( $response['id'], $this->get_digirisk_dir_path() . '/' . $path );
		wp_update_attachment_metadata( $response['id'], $attach_data );

		wp_set_object_terms( $response['id'], wp_parse_args( $types, array( 'printed', ) ), $this->attached_taxonomy_type );

		//	On met à jour les informations concernant le document dans la base de données.
		$document_args = array(
			'id' => $response['id'],
			'title' => basename( $response['filename'], '.odt' ),
			'parent_id' => ! empty( $element->id ) ? $element->id : 0,
			'mime_type' => ! empty( $filetype['type'] ) ? $filetype['type'] : 'application/vnd.oasis.opendocument.text',
			'type' => $this->post_type,
			'model_id' => $model_to_use,
			'document_meta' => $document_meta,
			'status' => 'inherit',
			'version' => $document_revision,
		);

		$dcument = $this->update( $document_args );

		return $response;
	}
}

Document_Class::g();
