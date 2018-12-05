<?php
/**
 * Classe gérant les enfants (Liaison avec DigiRisk Dashboard)
 *
 * @author    Evarisk <dev@evarisk.com>
 * @copyright (c) 2006-2018 Evarisk <dev@evarisk.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   DigiRisk\Class
 *
 * @since     7.1.0
 */

namespace digi;

defined( 'ABSPATH' ) || exit;

/**
 * Child class.
 */
class Child_Class extends \eoxia\Singleton_Util {

	/**
	 * Construct
	 *
	 * @since 7.1.0
	 */
	protected function construct() {}

	public function generate_security_id() {
		$length            = 10;
		$characters        = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen( $characters );

		$security_id = '';

		for ( $i = 0; $i < $length; $i++ ) {
			$security_id .= $characters[ rand( 0, $characters_length - 1) ];
		}

		return $security_id;
	}

	public function check_hash( $hash ) {
		return true;
	}
}

new Child_Action();
