<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcFiltersUpgrade' ) ) {

	class WpssoBcFiltersUpgrade {

		private $p;	// Wpsso class object.
		private $a;	// WpssoBc class object.

		/**
		 * Instantiated by WpssoBcFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'rename_md_options_keys'    => 1,
			) );
		}

		public function filter_rename_md_options_keys( $options_keys ) {

			$options_keys[ 'wpssobc' ] = array(
				7 => array(
					'bc_title' => 'schema_bc_title',
				),
			);

			return $options_keys;
		}
	}
}
