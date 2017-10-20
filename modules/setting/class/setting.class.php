<?php
/**
 * Classe gérant les configurations de DigiRisk.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 6.0.0
 * @version 6.4.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les configurations de DigiRisk.
 *
 * @return void
 */
class Setting_Class extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.0
	 * @version 6.2.9.0
	 */
	protected function construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'init_option' ) );
		add_action( 'admin_init', array( $this, 'init_preset_danger' ) );
	}

	/**
	 * Initialise les accronymes de DigiRisk.
	 *
	 * @return void
	 *
	 * @since 1.0
	 * @version 6.2.9.0
	 */
	public function init_option() {
		$file_content = file_get_contents( \eoxia\Config_Util::$init['digirisk']->setting->path . 'asset/json/default.json' );
		$data = json_decode( $file_content, true );
		$list_accronym = get_option( \eoxia\Config_Util::$init['digirisk']->accronym_option );

		if ( empty( $list_accronym ) ) {
			update_option( \eoxia\Config_Util::$init['digirisk']->accronym_option, json_encode( $data ) );
		}
	}

	/**
	 * Si les "preset danger" n'existent pas dans la bdd, cette méthode à pour but de les initialiser.
	 *
	 * @since 6.2.9
	 * @version 6.4.0
	 */
	public function init_preset_danger() {
		$digirisk_core = get_option( \eoxia\Config_Util::$init['digirisk']->core_option );

		if ( ! empty( $digirisk_core['installed'] ) ) {
			$preset_danger_installed = get_option( \eoxia\Config_Util::$init['digirisk']->setting->key_preset_danger, false );

			if ( ! $preset_danger_installed ) {
				$danger_category_list = Risk_Category_Class::g()->get();

				if ( ! empty( $danger_category_list ) ) {
					foreach ( $danger_category_list as $element ) {
						if ( ! empty( $element->thumbnail_id ) ) {
							Risk_Class::g()->update( array(
								'taxonomy' => array(
									'digi-category-risk' => array(
										$element->id,
									),
								),
								'preset' => true,
							) );
						}
					}
				}

				update_option( \eoxia\Config_Util::$init['digirisk']->setting->key_preset_danger, true );
			}
		}
	}
}

Setting_Class::g();
