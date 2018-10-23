<?php
/**
 * Classe gérant la recherche
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.2.3.0
 * @version 6.2.4.0
 * @copyright 2015-2017 Evarisk
 * @package search
 * @subpackage action
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant la recherche
 */
class Search_Class extends \eoxia001\Singleton_Util {

	/**
	 * Le constructeur
	 */
	protected function construct() {}

	/**
	 * Récupères les éléments selon le term et le type de la recherche.
	 *
	 * @param  array $data Les données pour la recherche.
	 * @return array       Les élements trouvés par la recherche.
	 *
	 * @since 1.0
	 * @version 6.2.4.0
	 */
	public function search( $data ) {
		$list = array();

		if ( 'user' === $data['type'] ) {
			if ( ! empty( $data['term'] ) ) {
				if ( ! empty( $data['exclude'] ) ) {
					$data['exclude'] = explode( ',', $data['exclude'] );
				}

				$args = array(
					'fields' => 'ID',
					'search' => '*' . $data['term'] . '*',
				);

				if ( ! empty( $data['exclude'] ) ) {
					$args['exclude'] = $data['exclude'];
				}

				$list = get_users( $args  );

				$args = array(
					'fields' => 'ID',
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => 'first_name',
							'value' => $data['term'],
							'compare' => 'LIKE',
						),
						array(
							'key' => 'last_name',
							'value' => $data['term'],
							'compare' => 'LIKE',
						),
					),
				);

				if ( ! empty( $data['exclude'] ) ) {
					$args['exclude'] = $data['exclude'];
				}

				$list = wp_parse_args( $list, get_users( $args ) );

				$list = array_unique( $list );
			} else {
				$data = array(
					'fields' => 'ID',
					'exclude' => array( 1 ),
				);

				if ( ! empty( $data['exclude'] ) ) {
					$data['exclude'] = explode( ',', $data['exclude'] );
				}
				$list = get_users( );
			}

			// Force le tableau de integer.
			$list = \eoxia001\Array_Util::g()->to_int( $list );

		} elseif ( 'post' === $data['type'] ) {
			$model_name = '\digi\\' . $data['class'];
			$list = $model_name::g()->search( $data['term'], array(
				'option' => array( '_wpdigi_unique_identifier' ),
				'post_title'
			) );
		}

		return $list;
	}
}

Search_Class::g();
