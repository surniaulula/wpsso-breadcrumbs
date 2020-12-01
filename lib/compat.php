<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcCompat' ) ) {

	/**
	 * 3rd party plugin and theme compatibility actions and filters.
	 */
	class WpssoBcCompat {

		private $p;	// Wpsso class object.
		private $a;	// WpssoBc class object.

		/**
		 * Instantiated by WpssoBc->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			if ( ! is_admin() ) {

				/**
				 * Rank Math.
				 */
				if ( ! empty( $this->p->avail[ 'seo' ][ 'rankmath' ] ) ) {

					add_filter( 'rank_math/json_ld', array( $this, 'cleanup_rankmath_json_ld' ), PHP_INT_MAX );
				}

				/**
				 * WooCommerce.
				 */
				if ( ! empty( $this->p->avail[ 'ecom' ][ 'woocommerce' ] ) ) {

					/**
					 * Disable Schema BreadcrumbList JSON-LD markup from the WooCommerce WC_Structured_Data class (since v3.0.0).
					 */
					add_filter( 'woocommerce_structured_data_breadcrumblist', '__return_empty_array' );
				}
			}
		}

		public function cleanup_rankmath_json_ld( $data ) {

			/**
			 * Remove the Rank Math Schema BreadcrumbList markup.
			 */
			if ( isset( $data[ 'BreadcrumbList' ] ) ) {

				unset( $data[ 'BreadcrumbList' ] );
			}

			return $data;
		}
	}
}
