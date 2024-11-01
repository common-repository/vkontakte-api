<?php
/**
 * VL API plugin base
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 15.08.2019, Webcraftic
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WVAI_Plugin' ) ) {

	class WVAI_Plugin extends Wbcr_Factory446_Plugin {

		/**
		 * @var Wbcr_Factory446_Plugin
		 */
		private static $app;

		/**
		 * @param string $plugin_path
		 * @param array $data
		 *
		 * @throws Exception
		 */
		public function __construct( $plugin_path, $data ) {
			parent::__construct( $plugin_path, $data );

			self::$app = $this;
		}

		/**
		 * @return WVAI_Plugin
		 */
		public static function app() {
			return self::$app;
		}
	}
}
